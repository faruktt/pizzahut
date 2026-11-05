<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'unit',
        'cost_price',
        'current_stock',
        'alert_quantity',
        'is_active',
    ];

    protected $casts = [
        'cost_price'     => 'decimal:2',
        'current_stock'  => 'decimal:2',
        'alert_quantity' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->alert_quantity;
    }
}

