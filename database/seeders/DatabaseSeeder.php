<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core data (order matters)
            ShippingZoneSeeder::class,
            DistrictSeeder::class,
            SizeSeeder::class,
            ColorSeeder::class,
            CategorySeeder::class,
            SettingSeeder::class,

            // Users
            AdminUserSeeder::class,

            // Sample data
            CouponSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
