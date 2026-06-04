<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $leftPages = [
            'About Us',
            'Contact Us',
            'FAQ'
        ];

        $rightPages = [
            'Privacy Policy',
            'Terms of Service',
            'Return Policy',
            'Shipping Information'
        ];

        $order = 1;
        foreach ($leftPages as $page) {
            \App\Models\StaticPage::firstOrCreate([
                'title' => $page,
                'slug' => str_slug($page),
                'sort_order' => $order++,
                'footer_position' => 1,
            ]);
        }

        foreach ($rightPages as $page) {
            \App\Models\StaticPage::firstOrCreate([
                'title' => $page,
                'slug' => str_slug($page),
                'sort_order' => $order++,
                'footer_position' => 2,
            ]);
        }
    }
}
