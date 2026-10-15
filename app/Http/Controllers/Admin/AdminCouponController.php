<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code',
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount'     => 'nullable|numeric|min:0',
            'expires_at'       => 'nullable|date',
            'usage_limit'      => 'nullable|integer|min:1',
            'description'      => 'nullable|string|max:500',
        ]);

        Coupon::create([
            'code'             => strtoupper(trim($request->code)),
            'type'             => $request->type,
            'value'            => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount'     => $request->max_discount,
            'expires_at'       => $request->expires_at,
            'usage_limit'      => $request->usage_limit,
            'description'      => $request->description,
            'is_active'        => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount'     => 'nullable|numeric|min:0',
            'expires_at'       => 'nullable|date',
            'usage_limit'      => 'nullable|integer|min:1',
            'description'      => 'nullable|string|max:500',
        ]);

        $coupon->update([
            'code'             => strtoupper(trim($request->code)),
            'type'             => $request->type,
            'value'            => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount'     => $request->max_discount,
            'expires_at'       => $request->expires_at,
            'usage_limit'      => $request->usage_limit,
            'description'      => $request->description,
            'is_active'        => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon status updated.');
    }
}
