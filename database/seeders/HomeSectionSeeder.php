<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['section_key' => 'hero', 'title' => null, 'subtitle' => null, 'item_limit' => 10],
            ['section_key' => 'categories', 'title' => 'Shop by Category', 'subtitle' => 'Curated collections for every occasion', 'item_limit' => 6],
            ['section_key' => 'flash_sale', 'title' => null, 'subtitle' => null, 'item_limit' => 10],
            ['section_key' => 'new_arrivals', 'title' => 'New Arrivals', 'subtitle' => 'The latest additions to our collection', 'item_limit' => 10],
            ['section_key' => 'trending', 'title' => 'Trending Now', 'subtitle' => 'What everyone is adding to cart this week', 'item_limit' => 10],
            ['section_key' => 'best_selling', 'title' => 'Best Sellers', 'subtitle' => 'Loved by our customers', 'item_limit' => 10],
            ['section_key' => 'on_sale', 'title' => 'On Sale', 'subtitle' => 'Timeless pieces at exceptional prices', 'item_limit' => 10],
            ['section_key' => 'featured', 'title' => 'Featured', 'subtitle' => 'Handpicked for you', 'item_limit' => 10],
            ['section_key' => 'bento_events', 'title' => 'Explore The Collection', 'subtitle' => 'Curated', 'item_limit' => 8],
            ['section_key' => 'testimonials', 'title' => 'What Our Clients Say', 'subtitle' => null, 'item_limit' => 6],
            ['section_key' => 'festive_banner', 'title' => null, 'subtitle' => null, 'item_limit' => 1],
            ['section_key' => 'mens_collection', 'title' => "Men's Collection", 'subtitle' => 'Refined essentials', 'item_limit' => 10],
            ['section_key' => 'ladies_collection', 'title' => "Ladies' Collection", 'subtitle' => 'Elegance redefined', 'item_limit' => 10],
            ['section_key' => 'our_brands', 'title' => 'Our Brands', 'subtitle' => null, 'item_limit' => 5],
            ['section_key' => 'why_us', 'title' => 'Why Choose Us', 'subtitle' => null, 'item_limit' => 4],
            ['section_key' => 'showroom', 'title' => null, 'subtitle' => null, 'item_limit' => 1],
            ['section_key' => 'newsletter', 'title' => null, 'subtitle' => null, 'item_limit' => 1],
            ['section_key' => 'social_feed', 'title' => 'Follow Us', 'subtitle' => null, 'item_limit' => 8],
        ];

        foreach ($sections as $order => $data) {
            HomeSection::updateOrCreate(
                ['section_key' => $data['section_key']],
                array_merge($data, [
                    'is_visible' => true,
                    'display_order' => $order,
                ])
            );
        }
    }
}
