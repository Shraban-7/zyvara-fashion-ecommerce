<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Merinor',
            'Lianoa',
            'Fluxio',
            'Babee',
            'Sneaktra'
        ];

        foreach ($brands as $brand) {
            Brand::query()->updateOrCreate(
                ['name' => $brand],
                ['slug' => str_slug($brand), 'is_active' => true, 'own_brand' => true]
            );
        }
    }
}
