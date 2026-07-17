<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a `level` column to support the 3-level category hierarchy
     * (0 = main category, 1 = subcategory, 2 = sub-subcategory).
     * The categories table already has a self-referencing `parent_id`.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedTinyInteger('level')
                ->default(0)
                ->after('parent_id')
                ->comment('0 = main category, 1 = subcategory, 2 = sub-subcategory');
        });

        // Backfill existing rows from the current parent chain.
        \DB::statement('UPDATE categories SET level = 0 WHERE parent_id IS NULL');
        \DB::statement('UPDATE categories c JOIN categories p ON c.parent_id = p.id SET c.level = 1 WHERE p.parent_id IS NULL');
        \DB::statement('UPDATE categories c JOIN categories s ON c.parent_id = s.id JOIN categories p ON s.parent_id = p.id SET c.level = 2 WHERE p.parent_id IS NULL');
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
