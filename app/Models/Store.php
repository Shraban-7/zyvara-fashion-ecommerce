<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Store extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'opening_hours' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_flagship' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    /**
     * Combined mailing address, skipping empty components.
     */
    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address_line1,
            $this->address_line2,
            implode(', ', array_filter([$this->city, $this->state, $this->postal_code])),
            $this->country,
        ])->filter()->implode(', ');
    }

    /**
     * Today's opening hours string, e.g. "10:00-20:00" or "closed".
     */
    public function getTodayHoursAttribute(): string
    {
        $day = strtolower(now()->englishDayOfWeek);

        return $this->opening_hours[$day] ?? 'closed';
    }

    /**
     * Whether the store is open right now, based on today's range and the
     * application timezone. (Single-timezone assumption; if stores ever span
     * timezones, add a per-store `timezone` column and compare via now($tz).)
     */
    public function getIsOpenNowAttribute(): bool
    {
        $range = $this->today_hours;

        if (! $range || strtolower($range) === 'closed' || ! str_contains($range, '-')) {
            return false;
        }

        [$open, $close] = explode('-', $range);
        $now = now()->format('H:i');

        return $now >= trim($open) && $now <= trim($close);
    }

    /**
     * Directions link: explicit Google Maps URL if provided, otherwise a
     * Maps search built from the full address.
     */
    public function getDirectionsUrlAttribute(): string
    {
        if ($this->google_maps_url) {
            return $this->google_maps_url;
        }

        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->full_address);
    }

    public static function clearCache(): void
    {
        Cache::forget('active_stores');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }
}
