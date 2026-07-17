<?php

namespace App\Enums;

enum ReturnType: string
{
    case RETURN = 'return';
    case EXCHANGE = 'exchange';

    public function label(): string
    {
        return match ($this) {
            self::RETURN => 'Return (Refund)',
            self::EXCHANGE => 'Exchange',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
