<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Ayesha Rahman',
                'location' => 'Dhaka',
                'quote' => 'The quality is unreal for the price. My linen shirt still looks brand new after a dozen washes.',
                'rating' => 5,
                'sort_order' => 1,
            ],
            [
                'name' => 'Tanvir Ahmed',
                'location' => 'Chittagong',
                'quote' => 'Effortless, clean styling. I get compliments every time I wear their panjabi to events.',
                'rating' => 5,
                'sort_order' => 2,
            ],
            [
                'name' => 'Nusrat Jahan',
                'location' => 'Sylhet',
                'quote' => 'Fast delivery and the fit is true to size. Easily my go-to for everyday essentials now.',
                'rating' => 4,
                'sort_order' => 3,
            ],
            [
                'name' => 'Rafiq Islam',
                'location' => 'Khulna',
                'quote' => 'Beautiful packaging and the fabric feels premium. Will definitely shop again.',
                'rating' => 5,
                'sort_order' => 4,
            ],
        ];

        foreach ($items as $item) {
            Testimonial::updateOrCreate(
                ['name' => $item['name']],
                array_merge($item, ['is_active' => true, 'is_approved' => true])
            );
        }
    }
}
