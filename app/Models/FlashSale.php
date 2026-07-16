<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class FlashSale extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('active_flash_sales'));
        static::deleted(fn () => Cache::forget('active_flash_sales'));
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'flash_sale_products')
            ->withPivot(['sale_price', 'sort_order'])
            ->withTimestamps()
            ->orderBy('flash_sale_products.sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $now = now();

        return $query->where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('ends_at');
    }

    public function getIsRunningAttribute(): bool
    {
        $now = now();

        return $this->is_active && $this->starts_at <= $now && $this->ends_at >= $now;
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->is_active && $this->starts_at > now();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->ends_at < now();
    }
}
