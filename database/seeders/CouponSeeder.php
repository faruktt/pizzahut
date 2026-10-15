<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        if (Coupon::count() === 0) {
            Coupon::create([
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 400,
                'max_discount' => 250,
                'expires_at' => Carbon::now()->addMonths(6),
                'usage_limit' => 500,
                'description' => '10% discount for all customers (Min order ৳400)',
                'is_active' => true,
            ]);

            Coupon::create([
                'code' => 'PIZZA100',
                'type' => 'fixed',
                'value' => 100,
                'min_order_amount' => 800,
                'expires_at' => Carbon::now()->addMonths(6),
                'usage_limit' => 200,
                'description' => 'Flat ৳100 OFF on orders above ৳800',
                'is_active' => true,
            ]);
        }
    }
}
