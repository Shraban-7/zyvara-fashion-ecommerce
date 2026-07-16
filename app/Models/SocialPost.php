<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialPost extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id', 'desc');
    }

    public function getPlatformIconAttribute(): string
    {
        return $this->platform === 'facebook' ? 'fab fa-facebook-f' : 'fab fa-instagram';
    }

    public function getPlatformLabelAttribute(): string
    {
        return $this->platform === 'facebook' ? 'Facebook' : 'Instagram';
    }
}
