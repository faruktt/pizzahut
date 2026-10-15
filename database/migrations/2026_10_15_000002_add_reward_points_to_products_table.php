<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'reward_points')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('reward_points')->default(0)->after('base_price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'reward_points')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('reward_points');
            });
        }
    }
};
