<?php

namespace App\Models;

use App\Enums\ReturnReason;
use App\Enums\ReturnStatus;
use App\Enums\ReturnType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ReturnType::class,
        'reason' => ReturnReason::class,
        'status' => ReturnStatus::class,
        'refund_amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function exchangeDetail(): HasOne
    {
        return $this->hasOne(ExchangeDetail::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReturnRequestImage::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReturnStatusHistory::class)->orderBy('changed_at');
    }

    // Helpers
    public function getIsReturnAttribute(): bool
    {
        return $this->type === ReturnType::RETURN;
    }

    public function getIsExchangeAttribute(): bool
    {
        return $this->type === ReturnType::EXCHANGE;
    }

    public function canTransitionTo(ReturnStatus $target): bool
    {
        if ($this->status === $target) {
            return false;
        }

        return in_array($target, $this->status->next(), true);
    }
}
