<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'bg_color',
        'sort_order',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name, '_');
            }
        });
    }

    /**
     * Helper to get status object by slug or name
     */
    public static function findBySlugOrName($val)
    {
        return static::where('slug', $val)->orWhere('name', $val)->first();
    }
}
