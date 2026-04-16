<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('subcategory_id');
        });

        $this->updateBrands();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
    }

    private function updateBrands()
    {
        $products = \App\Models\Product::whereNotNull('brand')->select('id', 'brand')->get();
        foreach ($products as $product) {
            $brandName = $product->brand;
            $brand = \App\Models\Brand::firstOrCreate(['name' => $brandName], ['slug' => str_slug($brandName)]);
            $product->update(['brand_id' => $brand->id]);
        }
    }
};
