<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, $default = null)
    {
        $row = static::where('key', $key)->first();
        return $row && $row->value !== null ? $row->value : $default;
    }

    /**
     * Store or update a setting value by key.
     */
    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Remove a setting by key.
     */
    public static function removeValue(string $key): void
    {
        static::where('key', $key)->delete();
    }
}
