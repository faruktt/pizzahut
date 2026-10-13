<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Product variations: each row represents a specific (size, crust) combination with its own price and image
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('sizes')->cascadeOnDelete();
            $table->string('size_name')->nullable();
            $table->foreignId('crust_id')->nullable()->constrained('crusts')->cascadeOnDelete();
            $table->string('crust_name')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'size_id', 'crust_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
