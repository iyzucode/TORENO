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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('table_id');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('menu_name')->nullable()->after('menu_id');
            $table->decimal('price', 10, 2)->nullable()->after('quantity');
            $table->decimal('subtotal', 10, 2)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['menu_name', 'price', 'subtotal']);
        });
    }
};
