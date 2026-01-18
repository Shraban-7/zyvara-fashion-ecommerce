<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men',
                'icon' => 'fa-male',
                'is_featured' => true,
                'children' => [
                    [
                        'name' => 'T-Shirts',
                        'children' => [
                            ['name' => 'Round Neck'],
                            ['name' => 'V-Neck'],
                            ['name' => 'Polo'],
                            ['name' => 'Henley'],
                        ],
                    ],
                    [
                        'name' => 'Shirts',
                        'children' => [
                            ['name' => 'Casual Shirts'],
                            ['name' => 'Formal Shirts'],
                            ['name' => 'Denim Shirts'],
                        ],
                    ],
                    [
                        'name' => 'Pants',
                        'children' => [
                            ['name' => 'Jeans'],
                            ['name' => 'Chinos'],
                            ['name' => 'Formal Pants'],
                            ['name' => 'Joggers'],
                        ],
                    ],
                    [
                        'name' => 'Panjabi',
                        'children' => [
                            ['name' => 'Cotton Panjabi'],
                            ['name' => 'Silk Panjabi'],
                            ['name' => 'Embroidered Panjabi'],
                        ],
                    ],
                    ['name' => 'Jackets & Hoodies'],
                    ['name' => 'Shorts'],
                    ['name' => 'Activewear'],
                ],
            ],
            [
                'name' => 'Women',
                'icon' => 'fa-female',
                'is_featured' => true,
                'children' => [
                    [
                        'name' => 'Saree',
                        'children' => [
                            ['name' => 'Cotton Saree'],
                            ['name' => 'Silk Saree'],
                            ['name' => 'Jamdani'],
                            ['name' => 'Katan'],
                        ],
                    ],
                    [
                        'name' => 'Salwar Kameez',
                        'children' => [
                            ['name' => 'Unstitched'],
                            ['name' => 'Ready-Made'],
                            ['name' => 'Designer'],
                        ],
                    ],
                    [
                        'name' => 'Kurti',
                        'children' => [
                            ['name' => 'Short Kurti'],
                            ['name' => 'Long Kurti'],
                            ['name' => 'A-Line Kurti'],
                        ],
                    ],
                    ['name' => 'Tops & Tunics'],
                    ['name' => 'Palazzo & Pants'],
                    ['name' => 'Western Wear'],
                    ['name' => 'Abaya & Hijab'],
                ],
            ],
            [
                'name' => 'Kids',
                'icon' => 'fa-child',
                'is_featured' => true,
                'children' => [
                    [
                        'name' => 'Boys',
                        'children' => [
                            ['name' => 'T-Shirts'],
                            ['name' => 'Shirts'],
                            ['name' => 'Pants'],
                            ['name' => 'Panjabi'],
                        ],
                    ],
                    [
                        'name' => 'Girls',
                        'children' => [
                            ['name' => 'Frocks'],
                            ['name' => 'Salwar Kameez'],
                            ['name' => 'Tops'],
                        ],
                    ],
                    ['name' => 'Baby (0-2 Years)'],
                ],
            ],
            [
                'name' => 'Accessories',
                'icon' => 'fa-glasses',
                'is_featured' => false,
                'children' => [
                    ['name' => 'Watches'],
                    ['name' => 'Bags'],
                    ['name' => 'Belts'],
                    ['name' => 'Wallets'],
                    ['name' => 'Caps & Hats'],
                    ['name' => 'Sunglasses'],
                    ['name' => 'Jewelry'],
                ],
            ],
            [
                'name' => 'Footwear',
                'icon' => 'fa-shoe-prints',
                'is_featured' => false,
                'children' => [
                    ['name' => 'Sneakers'],
                    ['name' => 'Sandals'],
                    ['name' => 'Formal Shoes'],
                    ['name' => 'Sports Shoes'],
                    ['name' => 'Loafers'],
                    ['name' => 'Heels'],
                    ['name' => 'Flats'],
                ],
            ],
        ];

        $sortOrder = 1;
        foreach ($categories as $categoryData) {
            $this->createCategory($categoryData, null, $sortOrder++);
        }
    }

    private function createCategory(array $data, ?int $parentId, int $sortOrder): void
    {
        $children = $data['children'] ?? [];
        unset($data['children']);

        $category = Category::updateOrCreate(
            [
                'slug' => Str::slug($data['name']),
                'parent_id' => $parentId,
            ],
            [
                'name' => $data['name'],
                'icon' => $data['icon'] ?? null,
                'is_featured' => $data['is_featured'] ?? false,
                'is_active' => true,
                'sort_order' => $sortOrder,
            ]
        );

        $childSortOrder = 1;
        foreach ($children as $childData) {
            $this->createCategory($childData, $category->id, $childSortOrder++);
        }
    }
}
