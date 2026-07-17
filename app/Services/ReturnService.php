<?php

namespace App\Services;

use App\Enums\ReturnReason;
use App\Enums\ReturnStatus;
use App\Enums\ReturnType;
use App\Enums\SystemNotificationType;
use App\Mail\ReturnStatusMail;
use App\Models\ExchangeDetail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\ReturnRequest;
use App\Models\ReturnStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ReturnService
{
    /**
     * Configurable return window in days from delivery.
     */
    public int $returnWindowDays = 14;

    /**
     * Lightweight static check used by the storefront to decide whether to
     * surface the "Return / Exchange" button for a delivered item.
     */
    public static function eligibilityQuick(OrderItem $item): bool
    {
        $svc = new self();

        return $svc->checkEligibility($item)['eligible'];
    }

    /**
     * Check whether an order item is eligible for a return/exchange request.
     * Returns an array with 'eligible' bool and a human message.
     */
    public function checkEligibility(OrderItem $item): array
    {
        $order = $item->order;

        if (! $order) {
            return ['eligible' => false, 'message' => 'Order not found.'];
        }

        if ($order->status?->value !== 'delivered') {
            return ['eligible' => false, 'message' => 'Returns are only available for delivered orders.'];
        }

        if ($item->return_item_id) {
            return ['eligible' => false, 'message' => 'This item has already been returned.'];
        }

        if ($this->hasOpenRequest($item)) {
            return ['eligible' => false, 'message' => 'A return/exchange request already exists for this item.'];
        }

        $deliveredAt = $order->delivered_at ?? $order->updated_at;

        if ($deliveredAt && $deliveredAt->copy()->addDays($this->returnWindowDays)->isPast()) {
            return [
                'eligible' => false,
                'message' => "The return window ({$this->returnWindowDays} days from delivery) has passed.",
            ];
        }

        return ['eligible' => true, 'message' => 'Eligible for return or exchange.'];
    }

    public function hasOpenRequest(OrderItem $item): bool
    {
        return ReturnRequest::where('order_item_id', $item->id)
            ->whereNotIn('status', [ReturnStatus::REJECTED, ReturnStatus::COMPLETED])
            ->exists();
    }

    /**
     * Create a return or exchange request (within a transaction).
     */
    public function createRequest(array $data, ?User $user, OrderItem $item): ReturnRequest
    {
        return DB::transaction(function () use ($data, $user, $item) {
            $eligibility = $this->checkEligibility($item);

            if (! $eligibility['eligible']) {
                throw new \Exception($eligibility['message']);
            }

            $type = ReturnType::from($data['type']);

            $request = ReturnRequest::create([
                'order_id' => $item->order_id,
                'order_item_id' => $item->id,
                'user_id' => $user?->id,
                'type' => $type,
                'reason' => ReturnReason::from($data['reason']),
                'reason_note' => $data['reason_note'] ?? null,
                'status' => ReturnStatus::PENDING,
                'requested_at' => now(),
            ]);

            // Persist uploaded images
            if (! empty($data['images'])) {
                foreach ($data['images'] as $path) {
                    $request->images()->create(['image_path' => $path]);
                }
            }

            // Exchange specifics
            if ($type === ReturnType::EXCHANGE) {
                $this->createExchangeDetail($request, $item, $data);
            }

            $this->logStatus($request, ReturnStatus::PENDING, 'Request submitted by customer.', $user?->id);

            return $request;
        });
    }

    protected function createExchangeDetail(ReturnRequest $request, OrderItem $item, array $data): void
    {
        $originalVariant = $item->variant;
        $requestedVariant = isset($data['requested_variant_id'])
            ? ProductVariant::find($data['requested_variant_id'])
            : null;

        if (! $requestedVariant) {
            throw new \Exception('Please select a new size/color for the exchange.');
        }

        if (($requestedVariant->currentStock ?? 0) < $item->quantity) {
            throw new \Exception('The selected variant is out of stock for the requested quantity.');
        }

        $originalPrice = (float) ($originalVariant?->final_price ?? $item->unit_price);
        $requestedPrice = (float) ($requestedVariant->final_price ?? $requestedVariant->price ?? 0);
        $priceDifference = round(($requestedPrice - $originalPrice) * $item->quantity, 2);

        // Reserve stock so it cannot be sold while the exchange is pending
        $requestedVariant->decrement('stock_in', $item->quantity);

        ExchangeDetail::create([
            'return_request_id' => $request->id,
            'product_id' => $item->product_id,
            'original_variant_id' => $originalVariant?->id,
            'requested_variant_id' => $requestedVariant->id,
            'original_price' => $originalPrice,
            'requested_price' => $requestedPrice,
            'price_difference' => $priceDifference,
            'is_reserved' => true,
        ]);
    }

    /**
     * Transition a request to a new status with validation + history + notification.
     * Wrap side-effects (stock) in a transaction.
     */
    public function transition(
        ReturnRequest $request,
        ReturnStatus $target,
        ?string $note = null,
        ?User $admin = null,
        array $extra = []
    ): ReturnRequest {
        if (! $request->canTransitionTo($target)) {
            throw new \Exception("Illegal status transition: {$request->status->value} → {$target->value}.");
        }

        return DB::transaction(function () use ($request, $target, $note, $admin, $extra) {
            // Side-effects keyed by target status
            match ($target) {
                ReturnStatus::APPROVED => $this->onApproved($request, $extra),
                ReturnStatus::REJECTED => $this->onRejected($request),
                ReturnStatus::ITEM_RECEIVED => null,
                ReturnStatus::REFUNDED => $this->onRefunded($request, $extra),
                ReturnStatus::EXCHANGED => $this->onExchanged($request),
                ReturnStatus::COMPLETED => null,
                default => null,
            };

            $request->update([
                'status' => $target,
                'admin_note' => $note ?? $request->admin_note,
                'resolved_at' => $target->isTerminal() ? now() : $request->resolved_at,
                'resolved_by' => $admin?->id,
                'refund_amount' => $extra['refund_amount'] ?? $request->refund_amount,
                'refund_method' => $extra['refund_method'] ?? $request->refund_method,
            ]);

            $this->logStatus($request, $target, $note, $admin?->id);
            $this->notify($request, $target);

            return $request;
        });
    }

    protected function onApproved(ReturnRequest $request, array $extra): void
    {
        // For returns, mark the order item as return-pending via return_item_id reference
        // (kept lightweight; full refund handled on REFUNDED).
        if ($request->isReturn && isset($extra['refund_amount'])) {
            $request->refund_amount = $extra['refund_amount'];
        }
    }

    protected function onRejected(ReturnRequest $request): void
    {
        // Release any reserved exchange stock
        if ($request->isExchange && $request->exchangeDetail?->is_reserved) {
            $variant = $request->exchangeDetail->requestedVariant;
            if ($variant) {
                $variant->increment('stock_in', $request->orderItem->quantity);
            }
            $request->exchangeDetail->update(['is_reserved' => false]);
        }
    }

    protected function onRefunded(ReturnRequest $request, array $extra): void
    {
        $amount = $extra['refund_amount'] ?? $request->refund_amount
            ?? (float) $request->orderItem->subtotal;

        $request->refund_amount = $amount;

        // Restock the returned item
        $item = $request->orderItem;
        if ($item->product_variant_id) {
            $variant = ProductVariant::find($item->product_variant_id);
            if ($variant) {
                $variant->increment('stock_in', $item->quantity);
            }
        } else {
            $product = $item->product;
            if ($product) {
                $product->increment('stock_in', $item->quantity);
            }
        }

        // Link to the order item so the storefront badge shows "Returned"
        $item->return_item_id = $request->id;
        $item->save();
    }

    protected function onExchanged(ReturnRequest $request): void
    {
        $detail = $request->exchangeDetail;
        if (! $detail) {
            return;
        }

        // Restock the original returned variant
        $item = $request->orderItem;
        if ($item->product_variant_id) {
            $variant = ProductVariant::find($item->product_variant_id);
            if ($variant) {
                $variant->increment('stock_in', $item->quantity);
            }
        }

        // The requested variant was already reserved; mark released from reservation
        if ($detail->is_reserved) {
            $detail->update(['is_reserved' => false]);
        }

        $item->return_item_id = $request->id;
        $item->save();
    }

    protected function logStatus(ReturnRequest $request, ReturnStatus $status, ?string $note, ?int $userId): void
    {
        ReturnStatusHistory::create([
            'return_request_id' => $request->id,
            'status' => $status,
            'note' => $note,
            'changed_by' => $userId,
            'changed_at' => now(),
        ]);
    }

    /**
     * Notify the customer (in-app Notification + email, email flagged if not sent).
     */
    protected function notify(ReturnRequest $request, ReturnStatus $target): void
    {
        $user = $request->user;
        $orderNumber = $request->order?->order_number ?? 'N/A';
        $type = $request->isExchange ? 'Exchange' : 'Return';

        $messages = [
            ReturnStatus::APPROVED->value => "Your {$type} request #{$request->id} for order {$orderNumber} has been approved.",
            ReturnStatus::REJECTED->value => "Your {$type} request #{$request->id} for order {$orderNumber} was rejected.",
            ReturnStatus::ITEM_RECEIVED->value => "We've received your item for request #{$request->id} (order {$orderNumber}).",
            ReturnStatus::REFUNDED->value => "Your refund for request #{$request->id} (order {$orderNumber}) has been processed.",
            ReturnStatus::EXCHANGED->value => "Your exchange for request #{$request->id} (order {$orderNumber}) has been shipped.",
            ReturnStatus::COMPLETED->value => "Your {$type} request #{$request->id} (order {$orderNumber}) is complete.",
            ReturnStatus::PENDING->value => "Your {$type} request #{$request->id} for order {$orderNumber} has been submitted.",
        ];

        $message = $messages[$target->value] ?? "Your {$type} request #{$request->id} status changed to {$target->label()}.";

        // In-app notification
        if ($user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => "{$type} Request " . $target->label(),
                'message' => $message,
                'type' => SystemNotificationType::SUPPORT_TICKET,
                'action_url' => route('orders.returns.show', $request->id),
            ]);
        }

        // Email (flagged if mail not configured)
        try {
            if ($user?->email && class_exists(ReturnStatusMail::class)) {
                Mail::to($user->email)->send(new ReturnStatusMail($request, $target, $message));
            }
        } catch (\Exception $e) {
            Log::warning("Return email not sent for request #{$request->id}: " . $e->getMessage());
        }
    }

    /**
     * Return the list of statuses this request can legally transition to next.
     * @return ReturnStatus[]
     */
    public function nextAllowedStatuses(ReturnRequest $request): array
    {
        $all = ReturnStatus::cases();

        return array_values(array_filter($all, function ($status) use ($request) {
            return $status !== $request->status && $request->canTransitionTo($status);
        }));
    }

    public function mailConfigured(): bool
    {
        return ! empty(config('mail.from.address')) && config('mail.default') !== 'array';
    }
}
