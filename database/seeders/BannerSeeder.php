<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Enums\BannerPosition;
use App\Enums\BannerSize;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        //1. Generate 3 Hero Sliders (Left Side)
        $heroSliders = [
            ['image' => 'banners/left1.png', 'order' => 1],
            ['image' => 'banners/left2.png', 'order' => 2],
            ['image' => 'banners/left3.png', 'order' => 3],
            ['image' => 'banners/left4.png', 'order' => 4],
            ['image' => 'banners/left5.png', 'order' => 5],
        ];

        foreach ($heroSliders as $slider) {
            Banner::create([
                'title' => 'Hero Slider ' . $slider['order'],
                'image' => $slider['image'],
                'position' => BannerPosition::HERO->value,
                'sort_order' => $slider['order'],
                'is_active' => true,
            ]);
        }

        // 2. Generate 2 Promotional Banners (Right Side)
        $promoBanners = [
            ['image' => 'banners/right1.png', 'order' => 1],
            ['image' => 'banners/right2.png', 'order' => 2],
        ];

        foreach ($promoBanners as $promo) {
            Banner::create([
                'title' => 'Right Promo ' . $promo['order'],
                'image' => $promo['image'],
                'position' => BannerPosition::PROMOTIONAL->value,
                'sort_order' => $promo['order'],
                'is_active'  => true,
            ]);
        }

        //festival banner

        Banner::create([
            'title' => 'Eid Special Collection',
            'subtitle' => 'Celebrate in style with our exclusive festive wear. Premium quality at amazing prices!',
            'image' => 'banners/festive1.png',
            'button_text' => 'Shop Now',
            'position' => BannerPosition::FESTIVAL->value,
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        // Bento grid banners
        $bento = [
            ['title' => 'New Season', 'subtitle' => 'Autumn / Winter', 'image' => 'banners/left1.png', 'size' => BannerSize::LARGE, 'button_text' => 'Explore', 'button_link' => '/products', 'order' => 1],
            ['title' => 'Accessories', 'subtitle' => 'Complete the look', 'image' => 'banners/right1.png', 'size' => BannerSize::SMALL, 'button_text' => 'Shop', 'button_link' => '/products', 'order' => 2],
            ['title' => 'Footwear', 'subtitle' => 'Step out', 'image' => 'banners/right2.png', 'size' => BannerSize::SMALL, 'button_text' => 'Shop', 'button_link' => '/products', 'order' => 3],
            ['title' => 'The Edit', 'subtitle' => 'Curated picks', 'image' => 'banners/left2.png', 'size' => BannerSize::WIDE, 'button_text' => 'Discover', 'button_link' => '/products', 'order' => 4],
        ];

        foreach ($bento as $item) {
            Banner::create([
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'image' => $item['image'],
                'button_text' => $item['button_text'],
                'button_link' => $item['button_link'],
                'position' => BannerPosition::BENTO->value,
                'size' => $item['size']->value,
                'sort_order' => $item['order'],
                'is_active' => true,
            ]);
        }
    }
}
