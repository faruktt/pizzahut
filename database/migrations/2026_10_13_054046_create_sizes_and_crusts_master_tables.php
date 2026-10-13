<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Global Sizes table (reusable master list)
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Small, Medium, Large, Family
            $table->decimal('extra_price', 10, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Global Crusts table (reusable master list)
        Schema::create('crusts', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Pan, Thin, Stuffed, Sausage
            $table->decimal('extra_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crusts');
        Schema::dropIfExists('sizes');
    }
};
