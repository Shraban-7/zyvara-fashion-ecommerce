<?php

namespace Database\Seeders;

use App\Models\SocialPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SocialPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            ['platform' => 'instagram', 'caption' => '#OOTD', 'image' => 'social/s1.jpg', 'sort_order' => 1],
            ['platform' => 'instagram', 'caption' => 'New In', 'image' => 'social/s2.jpg', 'sort_order' => 2],
            ['platform' => 'facebook', 'caption' => 'Behind The Seams', 'image' => 'social/s3.jpg', 'sort_order' => 3],
            ['platform' => 'instagram', 'caption' => 'Studio Look', 'image' => 'social/s4.jpg', 'sort_order' => 4],
            ['platform' => 'facebook', 'caption' => 'Customer Spotlight', 'image' => 'social/s5.jpg', 'sort_order' => 5],
            ['platform' => 'instagram', 'caption' => 'Weekend Edit', 'image' => 'social/s6.jpg', 'sort_order' => 6],
        ];

        foreach ($posts as $post) {
            SocialPost::create(array_merge($post, [
                'is_active' => true,
                'post_url' => null,
            ]));
        }
    }
}
