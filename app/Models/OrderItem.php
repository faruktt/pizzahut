<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'size_name',
        'crust_name',
        'unit_price',
        'quantity',
        'total_price',
        'special_instructions',
        'combo_details',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function addons()
    {
        return $this->hasMany(OrderItemAddon::class);
    }
}
