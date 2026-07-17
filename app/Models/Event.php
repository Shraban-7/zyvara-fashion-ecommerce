<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link_url',
        'badge_text',
        'priority',
        'start_date',
        'end_date',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'display_order' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public const CACHE_KEY = 'homepage_bento_layout';

    // ---- Scopes ----

    public function scopeActive($query)
    {
        $now = now()->toDateString();

        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('priority')->orderBy('display_order')->orderBy('title');
    }

    // ---- Status helpers (admin) ----

    public function isCurrentlyVisible(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now()->toDateString();

        if ($this->start_date && $this->start_date->toDateString() > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date->toDateString() < $now) {
            return false;
        }

        return true;
    }

    public function statusKey(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        $now = now()->toDateString();

        if ($this->start_date && $this->start_date->toDateString() > $now) {
            return 'scheduled';
        }

        if ($this->end_date && $this->end_date->toDateString() < $now) {
            return 'expired';
        }

        return 'active';
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }
}
