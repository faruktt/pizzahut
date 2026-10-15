<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->default('#0f172a');
            $table->string('bg_color')->default('#f1f5f9');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Seed standard default statuses so existing orders and POS operate smoothly
        $now = now();
        $defaults = [
            ['name' => 'Pending', 'slug' => 'pending', 'color' => '#b45309', 'bg_color' => '#fef3c7', 'sort_order' => 1, 'is_default' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Preparing', 'slug' => 'preparing', 'color' => '#6d28d9', 'bg_color' => '#ede9fe', 'sort_order' => 2, 'is_default' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ready', 'slug' => 'ready', 'color' => '#0369a1', 'bg_color' => '#e0f2fe', 'sort_order' => 3, 'is_default' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Served', 'slug' => 'served', 'color' => '#0f766e', 'bg_color' => '#ccfbf1', 'sort_order' => 4, 'is_default' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Out for Delivery', 'slug' => 'out_for_delivery', 'color' => '#c2410c', 'bg_color' => '#ffedd5', 'sort_order' => 5, 'is_default' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Completed', 'slug' => 'completed', 'color' => '#15803d', 'bg_color' => '#dcfce7', 'sort_order' => 6, 'is_default' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cancelled', 'slug' => 'cancelled', 'color' => '#b91c1c', 'bg_color' => '#fee2e2', 'sort_order' => 7, 'is_default' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('order_statuses')->insert($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
