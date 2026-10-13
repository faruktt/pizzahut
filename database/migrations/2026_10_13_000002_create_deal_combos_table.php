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
        // 1. Add combo_items_count to products table
        Schema::table('products', function (Blueprint $table) {
            $table->integer('combo_items_count')->default(2)->after('is_deal');
        });

        // 2. Pivot table deal_products (admin selects which 2, 3, 4 products are in the combo)
        Schema::create('deal_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Add combo_details to order_items to persist the customer's selected combo items
        Schema::table('order_items', function (Blueprint $table) {
            $table->text('combo_details')->nullable()->after('special_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('combo_details');
        });

        Schema::dropIfExists('deal_products');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('combo_items_count');
        });
    }
};
