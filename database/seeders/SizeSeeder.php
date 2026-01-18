<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['name' => 'XS', 'code' => 'xs', 'sort_order' => 1],
            ['name' => 'S', 'code' => 's', 'sort_order' => 2],
            ['name' => 'M', 'code' => 'm', 'sort_order' => 3],
            ['name' => 'L', 'code' => 'l', 'sort_order' => 4],
            ['name' => 'XL', 'code' => 'xl', 'sort_order' => 5],
            ['name' => 'XXL', 'code' => 'xxl', 'sort_order' => 6],
            ['name' => '3XL', 'code' => '3xl', 'sort_order' => 7],
            ['name' => '4XL', 'code' => '4xl', 'sort_order' => 8],
            // Numeric sizes for pants/jeans
            ['name' => '28', 'code' => '28', 'sort_order' => 10],
            ['name' => '30', 'code' => '30', 'sort_order' => 11],
            ['name' => '32', 'code' => '32', 'sort_order' => 12],
            ['name' => '34', 'code' => '34', 'sort_order' => 13],
            ['name' => '36', 'code' => '36', 'sort_order' => 14],
            ['name' => '38', 'code' => '38', 'sort_order' => 15],
            ['name' => '40', 'code' => '40', 'sort_order' => 16],
            ['name' => '42', 'code' => '42', 'sort_order' => 17],
            // Free size
            ['name' => 'Free Size', 'code' => 'free', 'sort_order' => 20],
        ];

        foreach ($sizes as $size) {
            Size::updateOrCreate(
                ['code' => $size['code']],
                $size
            );
        }
    }
}
