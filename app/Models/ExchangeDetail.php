<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'original_price' => 'decimal:2',
        'requested_price' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'is_reserved' => 'boolean',
    ];

    public function returnRequest(): BelongsTo
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function originalVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'original_variant_id');
    }

    public function requestedVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'requested_variant_id');
    }
}
