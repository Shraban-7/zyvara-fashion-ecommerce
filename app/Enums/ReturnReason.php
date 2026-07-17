<?php

namespace App\Enums;

enum ReturnReason: string
{
    case WRONG_SIZE = 'wrong_size';
    case DAMAGED = 'damaged';
    case NOT_AS_DESCRIBED = 'not_as_described';
    case CHANGED_MIND = 'changed_mind';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WRONG_SIZE => 'Wrong Size',
            self::DAMAGED => 'Damaged / Defective',
            self::NOT_AS_DESCRIBED => 'Not as Described',
            self::CHANGED_MIND => 'Changed Mind',
            self::OTHER => 'Other',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
