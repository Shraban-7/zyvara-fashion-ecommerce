<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ShippingZoneSeeder::class,
            DistrictSeeder::class,
            SizeSeeder::class,
            ColorSeeder::class,
            CategorySeeder::class,
            SettingSeeder::class,

            AdminUserSeeder::class,

            CouponSeeder::class,
            BannerSeeder::class,
            BrandSeeder::class,
            HomeSectionSeeder::class,
            TestimonialSeeder::class,
            SocialPostSeeder::class,

            StaticPageSeeder::class,
        ]);

        if (app()->environment('local')) {
            $this->call([
                ProductSeeder::class,
                EmployeeSeeder::class,
                FlashSaleSeeder::class,
            ]);
        }
    }
}
