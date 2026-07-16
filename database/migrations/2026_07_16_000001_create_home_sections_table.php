<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->unsignedInteger('item_limit')->default(10);
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();

            $table->index(['is_visible', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
