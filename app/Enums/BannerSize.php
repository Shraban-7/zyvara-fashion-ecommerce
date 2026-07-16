<?php

namespace App\Enums;

enum BannerSize: string
{
    case SMALL = 'small';
    case WIDE = 'wide';
    case TALL = 'tall';
    case LARGE = 'large';

    public function label(): string
    {
        return match ($this) {
            self::SMALL => 'Small (1x1)',
            self::WIDE => 'Wide (2x1)',
            self::TALL => 'Tall (1x2)',
            self::LARGE => 'Large (2x2)',
        };
    }

    public function gridClass(): string
    {
        return match ($this) {
            self::SMALL => 'bento-item--small',
            self::WIDE => 'bento-item--wide',
            self::TALL => 'bento-item--tall',
            self::LARGE => 'bento-item--large',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
