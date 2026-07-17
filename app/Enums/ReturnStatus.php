<?php

namespace App\Enums;

enum ReturnStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ITEM_RECEIVED = 'item_received';
    case REFUNDED = 'refunded';
    case EXCHANGED = 'exchanged';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::ITEM_RECEIVED => 'Item Received',
            self::REFUNDED => 'Refunded',
            self::EXCHANGED => 'Exchanged',
            self::COMPLETED => 'Completed',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning-50 text-warning-700 border-warning-200',
            self::APPROVED => 'bg-primary-50 text-primary-700 border-primary-200',
            self::REJECTED => 'bg-danger-50 text-danger-700 border-danger-200',
            self::ITEM_RECEIVED => 'bg-secondary-100 text-secondary-700 border-secondary-200',
            self::REFUNDED => 'bg-success-50 text-success-700 border-success-200',
            self::EXCHANGED => 'bg-accent-50 text-accent-700 border-accent-200',
            self::COMPLETED => 'bg-success-50 text-success-700 border-success-200',
        };
    }

    /**
     * Allowed forward transitions from each status.
     * Rejecting/completing is terminal-ish; transitions to a prior state are blocked
     * except the explicit override handled in the service.
     */
    public function next(): array
    {
        return match ($this) {
            self::PENDING => [self::APPROVED, self::REJECTED],
            self::APPROVED => [self::ITEM_RECEIVED, self::REJECTED],
            self::ITEM_RECEIVED => [self::REFUNDED, self::EXCHANGED, self::COMPLETED],
            self::REFUNDED => [self::COMPLETED],
            self::EXCHANGED => [self::COMPLETED],
            self::REJECTED, self::COMPLETED => [],
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::REJECTED, self::COMPLETED], true);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
