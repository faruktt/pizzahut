<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropColumn(['extra_price', 'sort_order']);
        });
        Schema::table('crusts', function (Blueprint $table) {
            $table->dropColumn(['extra_price']);
        });
    }

    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->decimal('extra_price', 10, 2)->default(0)->after('name');
            $table->integer('sort_order')->default(0)->after('extra_price');
        });
        Schema::table('crusts', function (Blueprint $table) {
            $table->decimal('extra_price', 10, 2)->default(0)->after('name');
        });
    }
};
