<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom category_id ke menus
        Schema::table('menus', function (Blueprint $table) {
            $table->uuid('category_id')->nullable()->after('category');
            $table->foreign('category_id')->references('id')->on('menu_categories')->onDelete('restrict');
        });

        // 2. Migrasi data: buat kategori dari string unik yang sudah ada
        $existingCategories = DB::table('menus')->select('category')->distinct()->whereNotNull('category')->pluck('category');

        $sortOrder = 0;
        foreach ($existingCategories as $categoryName) {
            $categoryId = Str::uuid()->toString();
            DB::table('menu_categories')->insert([
                'id' => $categoryId,
                'name' => $categoryName,
                'sort_order' => $sortOrder++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update semua menu dengan kategori ini
            DB::table('menus')
                ->where('category', $categoryName)
                ->update(['category_id' => $categoryId]);
        }

        // 3. Hapus kolom category string lama
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Tambah kembali kolom category string
        Schema::table('menus', function (Blueprint $table) {
            $table->string('category')->nullable()->after('image_url');
        });

        // 2. Migrasi data kembali
        $categories = DB::table('menu_categories')->get();
        foreach ($categories as $category) {
            DB::table('menus')
                ->where('category_id', $category->id)
                ->update(['category' => $category->name]);
        }

        // 3. Hapus kolom category_id dan FK
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
