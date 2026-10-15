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
        // 1. Coupons table
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed'])->default('fixed'); // percentage or fixed amount
            $table->decimal('value', 10, 2); // 10% or 100 BDT
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable(); // Max cap for percentage
            $table->dateTime('expires_at')->nullable();
            $table->integer('usage_limit')->nullable(); // Total times can be used
            $table->integer('used_count')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Add reward_points to users table
        if (!Schema::hasColumn('users', 'reward_points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('reward_points')->default(0)->after('email');
            });
        }

        // 3. Add coupon & points tracking to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('discount');
                $table->decimal('coupon_discount', 10, 2)->default(0)->after('coupon_code');
                $table->integer('points_used')->default(0)->after('coupon_discount');
                $table->decimal('points_discount', 10, 2)->default(0)->after('points_used');
                $table->integer('points_earned')->default(0)->after('points_discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');

        if (Schema::hasColumn('users', 'reward_points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('reward_points');
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'coupon_code',
                'coupon_discount',
                'points_used',
                'points_discount',
                'points_earned',
            ]);
        });
    }
};
