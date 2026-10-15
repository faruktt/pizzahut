<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'base_price',
        'reward_points',
        'is_deal',
        'is_bestseller',
        'is_new',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function crusts()
    {
        return $this->hasMany(ProductCrust::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->where('is_active', true);
    }

    public function allVariations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function comboProducts()
    {
        return $this->belongsToMany(Product::class, 'deal_products', 'deal_id', 'product_id')
                    ->withTimestamps();
    }
}
