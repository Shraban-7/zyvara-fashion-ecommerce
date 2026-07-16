<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id', 'desc');
    }
}
