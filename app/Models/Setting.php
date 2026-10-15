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

    /**
     * Calculate discount amount in Taka for given reward points based on admin setting.
     */
    public static function getPointDiscount(int $points): float
    {
        $pointsUnit = (float) static::getValue('point_rate_points', 1);
        $takaUnit = (float) static::getValue('point_rate_taka', 1);
        if ($pointsUnit <= 0) $pointsUnit = 1;
        $ratio = $takaUnit / $pointsUnit;
        return round($points * $ratio, 2);
    }

    /**
     * Calculate how many points are needed to get a specific Taka discount.
     */
    public static function getPointsForDiscount(float $taka): int
    {
        $pointsUnit = (float) static::getValue('point_rate_points', 1);
        $takaUnit = (float) static::getValue('point_rate_taka', 1);
        if ($takaUnit <= 0) return 0;
        return (int) ceil($taka * ($pointsUnit / $takaUnit));
    }
}
