<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add paid_amount and due_amount to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'due_amount')) {
                $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount');
            }
        });

        // Populate existing orders with paid_amount based on payment_status
        DB::statement("UPDATE orders SET paid_amount = total_amount, due_amount = 0 WHERE payment_status = 'paid'");
        DB::statement("UPDATE orders SET paid_amount = 0, due_amount = total_amount WHERE payment_status != 'paid'");

        // 2. Create customer_due_payments table
        if (!Schema::hasTable('customer_due_payments')) {
            Schema::create('customer_due_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('customer_name');
                $table->string('customer_phone')->nullable()->index();
                $table->decimal('amount', 10, 2);
                $table->string('payment_method')->default('cash'); // cash, card, bkash, nagad, etc.
                $table->date('payment_date');
                $table->text('note')->nullable();
                $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_due_payments');

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'due_amount')) {
                $table->dropColumn('due_amount');
            }
            if (Schema::hasColumn('orders', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });
    }
};
