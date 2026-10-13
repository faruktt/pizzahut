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
        // 1. Admins table for separate admin guard
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('admin'); // admin, cashier, manager
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Products table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->boolean('is_deal')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Product Sizes table (Per product or default templates)
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->string('name'); // Personal, Medium, Family, Large
            $table->decimal('price', 10, 2);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 5. Product Crusts table
        Schema::create('product_crusts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->string('name'); // Pan Crust, Stuffed Crust, Sausage Crust, Thin Crust
            $table->decimal('extra_price', 10, 2)->default(0);
            $table->timestamps();
        });

        // 6. Add Ons table
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Extra Cheese, Chicken, Spicy Chicken, etc.
            $table->string('icon')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Restaurant Tables for POS Dine-In Management
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->unique(); // Table 1, Table 2, etc.
            $table->integer('capacity')->default(4);
            $table->string('status')->default('available'); // available, occupied, reserved, billed
            $table->timestamps();
        });

        // 8. Orders table (supports Customer & Guest / Without Customer & POS Dine-in)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete(); // Created by admin/cashier
            $table->string('order_type')->default('delivery'); // delivery, takeaway, dine_in
            $table->foreignId('restaurant_table_id')->nullable()->constrained('restaurant_tables')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('delivery_location')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('vat_tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_method')->default('cash'); // cash, card, bkash
            $table->string('payment_status')->default('pending'); // pending, paid
            $table->string('order_status')->default('pending'); // pending, preparing, ready, served, completed, cancelled
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });

        // 9. Order Items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('size_name')->nullable();
            $table->string('crust_name')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });

        // 10. Order Item Add-Ons
        Schema::create('order_item_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->string('add_on_name');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_addons');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('restaurant_tables');
        Schema::dropIfExists('add_ons');
        Schema::dropIfExists('product_crusts');
        Schema::dropIfExists('product_sizes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('admins');
    }
};
