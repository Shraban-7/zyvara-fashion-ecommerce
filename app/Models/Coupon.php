<?php

namespace App\Models;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'applicable_categories',
        'applicable_products',
        'type',
        'value',
        'minimum_order_amount',
        'maximum_discount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'applicable_categories' => 'array',
        'applicable_products' => 'array',
        'type' => CouponType::class,
    ];

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }

    // Helpers
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->minimum_order_amount) {
            return 0;
        }

        $discount = match ($this->type) {
            CouponType::PERCENTAGE => $subtotal * ($this->value / 100),
            CouponType::FIXED => $this->value,
        };

        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        return min($discount, $subtotal);
    }

    public function getFormattedValueAttribute(): string
    {
        return $this->type->formatDiscount($this->value);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    // Code normalization (prevent case-sensitive conflicts)
    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = $value !== null ? strtoupper(trim($value)) : null;
    }

    public static function normalizeCode(string $code): string
    {
        return strtoupper(trim($code));
    }

    // Status helpers (for admin badges)
    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'inactive';
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'scheduled';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'expired';
        }

        return 'active';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Active',
            'scheduled' => 'Scheduled',
            'expired' => 'Expired',
            'inactive' => 'Inactive',
        };
    }

    // Restriction helpers
    public function hasCategoryRestrictions(): bool
    {
        return ! empty($this->applicable_categories);
    }

    public function hasProductRestrictions(): bool
    {
        return ! empty($this->applicable_products);
    }

    public function isRestricted(): bool
    {
        return $this->hasCategoryRestrictions() || $this->hasProductRestrictions();
    }

    /**
     * Given cart items, return the portion of the subtotal that is eligible
     * for this coupon (items matching category/product restrictions).
     * When no restrictions exist, the entire subtotal is eligible.
     *
     * @param  \Illuminate\Support\Collection  $items  CartItem collection (already loaded with product + variant)
     */
    public function eligibleSubtotal($items): float
    {
        if (! $this->isRestricted()) {
            return (float) $items->sum('total_price');
        }

        return (float) $items->sum(function ($item) {
            $product = $item->product;

            $inCategories = $this->hasCategoryRestrictions()
                && $product
                && in_array($product->category_id, $this->applicable_categories, true);

            $inProducts = $this->hasProductRestrictions()
                && in_array($item->product_id, $this->applicable_products, true);

            return ($inCategories || $inProducts) ? (float) $item->total_price : 0.0;
        });
    }

    /**
     * Whether the coupon applies to at least one item currently in the cart.
     */
    public function appliesToCart($items): bool
    {
        if (! $this->isRestricted()) {
            return $items->isNotEmpty();
        }

        foreach ($items as $item) {
            $product = $item->product;

            if ($this->hasProductRestrictions() && in_array($item->product_id, $this->applicable_products, true)) {
                return true;
            }

            if ($this->hasCategoryRestrictions() && $product && in_array($product->category_id, $this->applicable_categories, true)) {
                return true;
            }
        }

        return false;
    }
}
