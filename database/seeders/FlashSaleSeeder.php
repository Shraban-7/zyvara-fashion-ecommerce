<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('is_active', true)->inRandomOrder()->take(16)->get();

        if ($products->isEmpty()) {
            return;
        }

        $sales = [
            [
                'title' => 'Weekend Flash Deals',
                'subtitle' => 'Ends Sunday Midnight',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(2),
                'display_order' => 0,
            ],
            [
                'title' => 'Midnight Madness',
                'subtitle' => 'Lightning Offers',
                'starts_at' => now()->subHours(2),
                'ends_at' => now()->addHours(10),
                'display_order' => 1,
            ],
        ];

        $chunks = $products->chunk(8)->values();

        foreach ($sales as $index => $data) {
            $sale = FlashSale::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, ['is_active' => true])
            );

            $chunk = $chunks->get($index) ?? $chunks->first();

            $sync = [];
            foreach ($chunk->values() as $sortOrder => $product) {
                $sync[$product->id] = [
                    'sale_price' => round($product->price * 0.7, 2),
                    'sort_order' => $sortOrder,
                ];
            }

            $sale->products()->sync($sync);
        }
    }
}
