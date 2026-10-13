<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'admin_id',
        'order_type', // delivery, takeaway, dine_in
        'restaurant_table_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'delivery_location',
        'subtotal',
        'delivery_charge',
        'vat_tax',
        'discount',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'special_instructions',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
