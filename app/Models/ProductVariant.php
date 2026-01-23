<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'sku',
        'stock_in',
        'price_adjustment',
    ];

    protected $casts = [
        'stock_in' => 'integer',
        'price_adjustment' => 'decimal:2',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helpers
    public function isInStock(): bool
    {
        return $this->stock_in > 0;
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->product->price + ($this->price_adjustment ?? 0);
    }

    public function getVariantNameAttribute(): string
    {
        $parts = [];
        if ($this->size) {
            $parts[] = $this->size->name;
        }
        if ($this->color) {
            $parts[] = $this->color->name;
        }
        return implode(' / ', $parts);
    }
}
