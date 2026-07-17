<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Raziul\Sslcommerz\Facades\Sslcommerz;

class PaymentController extends Controller
{
    /**
     * Initiate an SSLCommerz hosted checkout session for an order
     * and redirect the customer to the gateway.
     *
     * The order is already created (payment_status = pending) by CheckoutController.
     * Never trust the browser redirect for marking payment as paid — the
     * authoritative update happens in paymentSuccess()/paymentIPN().
     */
    public function initiate(Order $order)
    {
        if ($order->payment_status !== PaymentStatus::PENDING) {
            toast_warning('This order has already been paid or is not eligible for payment.');
            return redirect()->route('orders.show', $order);
        }

        // Store id as the SSLCommerz tran_id (must be unique per transaction).
        $tranId = $order->order_number;

        try {
            $response = Sslcommerz::setOrder(
                (float) $order->total,
                $tranId,
                'Order ' . $tranId,
                'Fashion'
            )
                ->setCustomer(
                    $order->shipping_name ?? 'Customer',
                    $order->shipping_email ?? 'customer@example.com',
                    $order->shipping_phone ?? '',
                    $order->shipping_address ?? '',
                    $order->shipping_city ?? '',
                    '',
                    $order->shipping_postal_code ?? ''
                )
                ->setShippingInfo(
                    (int) $order->items_count,
                    $order->shipping_address ?? '',
                    $order->shipping_name ?? '',
                    $order->shipping_city ?? '',
                    '',
                    $order->shipping_postal_code ?? ''
                )
                ->makePayment();

            if (! $response->success()) {
                Log::error('SSLCommerz initiate failed', [
                    'order_id' => $order->id,
                    'response' => $response->getData(),
                ]);

                $order->update([
                    'payment_status' => PaymentStatus::FAILED,
                    'payment_response' => $response->getData(),
                ]);

                toast_error('Could not connect to the payment gateway. Please try again.');
                return redirect()->route('orders.show', $order);
            }

            // Persist the gateway tran_id / reference for later validation.
            $order->update([
                'transaction_id' => $tranId,
                'payment_response' => $response->getData(),
            ]);

            return redirect()->away($response->gatewayPageURL());
        } catch (\Throwable $e) {
            Log::error('SSLCommerz initiate exception: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString(),
            ]);

            toast_error('Payment initiation failed. Please try again.');
            return redirect()->route('orders.show', $order);
        }
    }

    /**
     * SSLCommerz redirects here after the customer completes/fails at the gateway.
     * This is NOT authoritative — the browser may close early. We validate
     * server-side via validatePayment() and only then mark paid. The IPN
     * remains the source of truth if it already updated the order.
     */
    public function success(Request $request)
    {
        $payload = $request->all();
        Log::info('SSLCommerz success callback', $payload);

        $order = $this->findOrderByPayload($payload);

        if (! $order) {
            Log::error('SSLCommerz success: order not found', $payload);
            return view('errors.404');
        }

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        // If IPN already marked this paid, just show confirmation.
        if ($order->payment_status === PaymentStatus::PAID) {
            return view('payment.success', compact('order'));
        }

        // Verify the hash + validate the transaction against SSLCommerz.
        $valid = $this->validateGatewayResponse($payload, $order);

        if (! $valid) {
            Log::warning('SSLCommerz success: validation failed', $payload);
            $order->update([
                'payment_response' => $payload,
            ]);
            return view('payment.failed', compact('order'));
        }

        DB::transaction(function () use ($order, $payload) {
            $order->update([
                'payment_method' => PaymentMethod::SSLCOMMERZ,
                'payment_method_name' => $payload['card_issuer'] ?? 'SSLCommerz',
                'payment_status' => PaymentStatus::PAID,
                'transaction_id' => $payload['tran_id'] ?? $order->transaction_id,
                'paid_at' => now(),
                'payment_response' => $payload,
            ]);
        });

        return view('payment.success', compact('order'));
    }

    /**
     * SSLCommerz redirects here when the payment fails at the gateway.
     * Order is kept (not deleted) so the customer can retry payment.
     */
    public function failed(Request $request)
    {
        $payload = $request->all();
        Log::info('SSLCommerz failed callback', $payload);

        $order = $this->findOrderByPayload($payload);

        if (! $order) {
            Log::error('SSLCommerz failed: order not found', $payload);
            return view('errors.404');
        }

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        // Do not overwrite an already-paid order (IPN may have succeeded first).
        if ($order->payment_status !== PaymentStatus::PAID) {
            $order->update([
                'payment_status' => PaymentStatus::FAILED,
                'payment_response' => $payload,
            ]);
        }

        return view('payment.failed', compact('order'));
    }

    /**
     * SSLCommerz redirects here when the customer cancels at the gateway.
     * Order is kept recoverable.
     */
    public function cancelled(Request $request)
    {
        $payload = $request->all();
        Log::info('SSLCommerz cancelled callback', $payload);

        $order = $this->findOrderByPayload($payload);

        if (! $order) {
            Log::error('SSLCommerz cancelled: order not found', $payload);
            return view('errors.404');
        }

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        if ($order->payment_status !== PaymentStatus::PAID) {
            DB::transaction(function () use ($order, $payload) {
                $order->update([
                    'status' => OrderStatus::CANCELLED,
                    'payment_status' => PaymentStatus::CANCELLED,
                    'cancellation_reason' => 'Cancelled at payment gateway',
                    'cancelled_at' => now(),
                    'payment_response' => $payload,
                ]);

                $order->statusHistories()->create([
                    'status' => OrderStatus::CANCELLED->value,
                    'comment' => 'Cancelled at payment gateway',
                    'updated_by' => $order->user_id ?? 'system',
                ]);
            });
        }

        return view('payment.cancelled', compact('order'));
    }

    /**
     * SSLCommerz server-to-server IPN (Instant Payment Notification).
     * This is the MOST reliable source of truth — it fires even if the
     * customer closes the browser. Validate the hash + transaction here
     * and treat it as authoritative.
     */
    public function ipn(Request $request)
    {
        $payload = $request->all();
        Log::info('SSLCommerz IPN received', $payload);

        $order = $this->findOrderByPayload($payload);

        if (! $order) {
            Log::error('SSLCommerz IPN: order not found', $payload);
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $valid = $this->validateGatewayResponse($payload, $order);

        if (! $valid) {
            Log::warning('SSLCommerz IPN: validation failed', $payload);
            $order->update(['payment_response' => $payload]);
            return response()->json(['status' => 'error', 'message' => 'Validation failed'], 400);
        }

        // IPN is authoritative — always reflect the real gateway status.
        $status = strtoupper((string) ($payload['status'] ?? ''));

        DB::transaction(function () use ($order, $payload, $status) {
            $data = [
                'payment_method' => PaymentMethod::SSLCOMMERZ,
                'payment_method_name' => $payload['card_issuer'] ?? 'SSLCommerz',
                'transaction_id' => $payload['tran_id'] ?? $order->transaction_id,
                'payment_response' => $payload,
            ];

            if ($status === 'VALID' || $status === 'VALIDATED') {
                $data['payment_status'] = PaymentStatus::PAID;
                $data['paid_at'] = now();
            } elseif ($status === 'FAILED') {
                $data['payment_status'] = PaymentStatus::FAILED;
            } elseif ($status === 'CANCELLED') {
                $data['payment_status'] = PaymentStatus::CANCELLED;
            }

            $order->update($data);
        });

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Resolve the order from the SSLCommerz callback payload.
     * Prefers tran_id (our order_number); falls back to payment_id.
     */
    protected function findOrderByPayload(array $payload): ?Order
    {
        $tranId = $payload['tran_id'] ?? null;

        if ($tranId) {
            $order = Order::where('order_number', $tranId)->first();
            if ($order) {
                return $order;
            }
        }

        $paymentId = $payload['payment_id'] ?? null;
        if ($paymentId) {
            return Order::where('transaction_id', $paymentId)->first();
        }

        return null;
    }

    /**
     * Validate an SSLCommerz callback payload server-side.
     * 1) Verify the response hash (tamper protection).
     * 2) Cross-check the transaction against SSLCommerz via validatePayment().
     */
    protected function validateGatewayResponse(array $payload, Order $order): bool
    {
        // 1) Hash integrity check (never trust raw redirect data).
        if (! Sslcommerz::verifyHash($payload)) {
            Log::warning('SSLCommerz hash verification failed', $payload);
            return false;
        }

        // 2) Server-to-server validation of the transaction amount + id.
        $tranId = $payload['tran_id'] ?? $order->order_number;
        $amount = (float) ($payload['amount'] ?? $order->total);
        $currency = $payload['currency'] ?? 'BDT';

        try {
            return (bool) Sslcommerz::validatePayment($payload, $tranId, $amount, $currency);
        } catch (\Throwable $e) {
            Log::error('SSLCommerz validatePayment exception: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);
            return false;
        }
    }
}
