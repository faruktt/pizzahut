<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'capacity',
        'status', // available, occupied, reserved, billed
    ];

    public function activeOrder()
    {
        return $this->hasOne(Order::class, 'restaurant_table_id')
            ->whereIn('order_status', ['pending', 'preparing', 'ready', 'served'])
            ->latest();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'restaurant_table_id');
    }
}
