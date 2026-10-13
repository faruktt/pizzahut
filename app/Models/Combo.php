<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'max_select',
        'is_active',
        'sort_order',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'combo_products')
                    ->withPivot('sort_order')
                    ->orderBy('combo_products.sort_order');
    }
}
