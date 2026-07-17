<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Result object returned by CouponService::apply().
 */
class CouponResult
{
    public function __construct(
        public bool $success,
        public ?string $error = null,
        public ?string $errorCode = null,
        public ?Coupon $coupon = null,
        public float $discount = 0.0,
        public float $eligibleSubtotal = 0.0,
        public float $fullSubtotal = 0.0,
    ) {
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'error' => $this->error,
            'error_code' => $this->errorCode,
            'code' => $this->coupon?->code,
            'type' => $this->coupon?->type?->value,
            'value' => $this->coupon?->value,
            'discount' => $this->discount,
            'discount_formatted' => $this->discount > 0 ? money($this->discount) : null,
            'eligible_subtotal' => $this->eligibleSubtotal,
            'subtotal' => $this->fullSubtotal,
        ];
    }
}

class CouponService
{
    /**
     * Validate and compute the discount for a coupon applied to a cart.
     *
     * @param  string  $code   Raw (user-entered) coupon code
     * @param  Cart    $cart   Cart with items already loaded (product + variant)
     * @param  ?User   $user   Authenticated user (null for guests)
     */
    public function apply(string $code, Cart $cart, ?User $user = null): CouponResult
    {
        $normalized = Coupon::normalizeCode($code);
        $items = $cart->items;

        $coupon = Coupon::where('code', $normalized)->first();

        if (! $coupon) {
            return new CouponResult(false, 'Invalid coupon code. Please check and try again.', 'not_found');
        }

        if (! $coupon->is_active) {
            return new CouponResult(false, 'This coupon is no longer active.', 'inactive', $coupon);
        }

        $now = now();

        if ($coupon->starts_at && $coupon->starts_at->gt($now)) {
            return new CouponResult(false, 'This coupon is not valid yet.', 'not_started', $coupon);
        }

        if ($coupon->expires_at && $coupon->expires_at->lt($now)) {
            return new CouponResult(false, 'This coupon has expired.', 'expired', $coupon);
        }

        $fullSubtotal = (float) $items->sum('total_price');

        if ($fullSubtotal <= 0) {
            return new CouponResult(false, 'Your cart is empty.', 'empty_cart', $coupon);
        }

        if ($coupon->minimum_order_amount > 0 && $fullSubtotal < $coupon->minimum_order_amount) {
            return new CouponResult(
                false,
                'Minimum order amount of ' . money($coupon->minimum_order_amount) . ' required to use this coupon.',
                'min_order',
                $coupon
            );
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return new CouponResult(false, 'This coupon has reached its usage limit.', 'usage_limit', $coupon);
        }

        if ($user && $coupon->usage_limit_per_user) {
            $userUsage = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->count();

            if ($userUsage >= $coupon->usage_limit_per_user) {
                return new CouponResult(
                    false,
                    'You have already used this coupon the maximum number of times.',
                    'per_user_limit',
                    $coupon
                );
            }
        }

        if (! $coupon->appliesToCart($items)) {
            return new CouponResult(
                false,
                'This coupon does not apply to any items in your cart.',
                'not_applicable',
                $coupon
            );
        }

        $eligibleSubtotal = $coupon->eligibleSubtotal($items);
        $discount = $this->calculateDiscount($coupon, $eligibleSubtotal);

        return new CouponResult(
            true,
            null,
            null,
            $coupon,
            $discount,
            $eligibleSubtotal,
            $fullSubtotal
        );
    }

    /**
     * Compute the discount amount for an eligible subtotal.
     */
    public function calculateDiscount(Coupon $coupon, float $eligibleSubtotal): float
    {
        if ($eligibleSubtotal <= 0) {
            return 0.0;
        }

        $discount = match ($coupon->type) {
            \App\Enums\CouponType::PERCENTAGE => $eligibleSubtotal * ($coupon->value / 100),
            \App\Enums\CouponType::FIXED => $coupon->value,
        };

        if ($coupon->maximum_discount !== null && $coupon->maximum_discount > 0 && $discount > $coupon->maximum_discount) {
            $discount = $coupon->maximum_discount;
        }

        // Never exceed the eligible subtotal
        return min($discount, $eligibleSubtotal);
    }

    /**
     * Record the redemption inside a transaction and bump used_count.
     */
    public function recordUsage(Coupon $coupon, ?User $user, ?Order $order, float $discount): CouponUsage
    {
        return CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $user?->id,
            'order_id' => $order?->id,
            'discount_amount' => $discount,
            'used_at' => now(),
        ]);
    }
}
