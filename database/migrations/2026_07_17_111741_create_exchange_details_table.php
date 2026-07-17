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
        Schema::create('exchange_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained('return_requests')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('original_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->foreignId('requested_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->decimal('original_price', 10, 2)->default(0);
            $table->decimal('requested_price', 10, 2)->default(0);
            $table->decimal('price_difference', 10, 2)->default(0); // + = customer pays more, - = partial refund
            $table->boolean('is_reserved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_details');
    }
};
