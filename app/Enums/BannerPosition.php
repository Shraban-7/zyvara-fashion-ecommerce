<?php

namespace App\Enums;

enum BannerPosition: string
{
    case HERO = 'hero';
    case PROMOTIONAL = 'promotional';
    case CATEGORY = 'category';

    public function label(): string
    {
        return match ($this) {
            self::HERO => 'Hero Slider',
            self::PROMOTIONAL => 'Promotional Banner',
            self::CATEGORY => 'Category Banner',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
