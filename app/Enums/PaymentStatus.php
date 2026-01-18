<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::PAID => 'green',
            self::FAILED => 'red',
            self::REFUNDED => 'blue',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'fa-clock',
            self::PAID => 'fa-check-circle',
            self::FAILED => 'fa-times-circle',
            self::REFUNDED => 'fa-undo',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
