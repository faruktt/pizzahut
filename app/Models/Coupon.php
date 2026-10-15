<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'expires_at',
        'usage_limit',
        'used_count',
        'description',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if coupon is currently valid for a given subtotal.
     * Returns array [ 'valid' => bool, 'message' => string, 'discount' => float ]
     */
    public function validateForSubtotal(float $subtotal): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'This coupon is no longer active.', 'discount' => 0];
        }

        if ($this->expires_at && Carbon::now()->greaterThan($this->expires_at)) {
            return ['valid' => false, 'message' => 'This coupon has expired on ' . $this->expires_at->format('d M Y') . '.', 'discount' => 0];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'This coupon usage limit has been reached.', 'discount' => 0];
        }

        if ($subtotal < $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'Minimum order amount of ৳' . number_format($this->min_order_amount) . ' required to use this coupon.',
                'discount' => 0
            ];
        }

        // Calculate discount
        if ($this->type === 'percentage') {
            $discount = ($subtotal * ($this->value / 100));
            if ($this->max_discount !== null && $discount > $this->max_discount) {
                $discount = (float) $this->max_discount;
            }
        } else {
            $discount = (float) $this->value;
        }

        // Cannot discount more than subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return [
            'valid' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => round($discount, 2),
        ];
    }
}
