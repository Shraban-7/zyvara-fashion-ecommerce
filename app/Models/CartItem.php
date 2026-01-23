<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // Helpers
    public function getVariantDescriptionAttribute(): ?string
    {
        if (!$this->variant) {
            return null;
        }

        return $this->variant->variant_name;
    }

    public function updateQuantity(int $quantity): void
    {
        $this->update([
            'quantity' => $quantity,
            'total_price' => $this->unit_price * $quantity,
        ]);
    }

    public function incrementQuantity(int $amount = 1): void
    {
        $this->updateQuantity($this->quantity + $amount);
    }

    public function decrementQuantity(int $amount = 1): void
    {
        $newQuantity = max(1, $this->quantity - $amount);
        $this->updateQuantity($newQuantity);
    }

    public function hasStock(): bool
    {
        if ($this->variant) {
            return $this->variant->stock_in >= $this->quantity;
        }

        return $this->product->stock_in >= $this->quantity;
    }
}
