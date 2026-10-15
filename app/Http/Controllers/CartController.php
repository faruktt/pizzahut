<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductCrust;
use App\Models\ProductVariation;
use App\Models\AddOn;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Show Cart Details page (matching image 3).
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $totals = $this->calculateTotals();
        $subtotal = $totals['subtotal'];
        $couponDiscount = $totals['couponDiscount'];
        $pointsDiscount = $totals['pointsDiscount'];
        $pointsUsed = $totals['pointsUsed'] ?? 0;
        $deliveryCharge = $totals['deliveryCharge'];
        $total = $totals['total'];
        $earnedPoints = $totals['earnedPoints'];

        $appliedCoupon = session()->get('coupon');
        $appliedPoints = session()->get('points');

        // Reward points
        $userPoints = Auth::check() ? (int) Auth::user()->reward_points : 0;
        $pointRatePoints = (float) Setting::getValue('point_rate_points', 1);
        $pointRateTaka   = (float) Setting::getValue('point_rate_taka', 1);
        $minRedeemPoints = (int) Setting::getValue('min_redeem_points', 1);

        // Sides for "You may also like"
        $recommendations = Product::whereHas('category', function ($q) {
            $q->where('slug', 'sides');
        })->where('is_active', true)->take(4)->get();

        if ($recommendations->isEmpty()) {
            $recommendations = Product::where('is_active', true)->where('is_bestseller', false)->take(4)->get();
        }

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('cart', compact(
            'cart', 'subtotal', 'deliveryCharge', 'total', 'recommendations', 'cartCount',
            'appliedCoupon', 'couponDiscount', 'appliedPoints', 'pointsDiscount', 'pointsUsed',
            'userPoints', 'earnedPoints', 'pointRatePoints', 'pointRateTaka', 'minRedeemPoints'
        ));
    }

    /**
     * Add item to cart with customization.
     */
    public function add(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'status' => 'auth_required',
                    'redirect' => route('login'),
                    'message' => 'কার্টে পণ্য যোগ করতে অনুগ্রহ করে প্রথমে লগইন করুন। (Please login to add items to your cart)'
                ], 401);
            }
            session()->put('url.intended', url()->previous());
            return redirect()->route('login')->with('error', 'কার্টে পণ্য যোগ করতে অনুগ্রহ করে প্রথমে লগইন করুন। (Please login to add items to your cart)');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) ($request->quantity ?? 1);

        // 1. Try finding variation (Size + Crust combo with individual price & image)
        $variation = null;
        if ($request->filled('size_id') && $request->filled('crust_id')) {
            $variation = ProductVariation::where('product_id', $product->id)
                ->where('size_id', $request->size_id)
                ->where('crust_id', $request->crust_id)
                ->first();
        }
        if (!$variation && $request->filled('size_name') && $request->filled('crust_name')) {
            $variation = ProductVariation::where('product_id', $product->id)
                ->where('size_name', $request->size_name)
                ->where('crust_name', $request->crust_name)
                ->first();
        }

        if ($variation) {
            $sizeName = $variation->size_name;
            $crustName = $variation->crust_name;
            $unitPrice = (float) $variation->price;
            $crustExtra = 0;
            $itemImage = $variation->image ?: $product->image;
        } else {
            // Fallback to legacy size/crust calculation
            $sizeName = 'Standard';
            $unitPrice = $product->base_price;
            $itemImage = $product->image;

            if ($request->filled('size_id')) {
                $size = ProductSize::find($request->size_id);
                if ($size) {
                    $sizeName = $size->name;
                    $unitPrice = $size->price;
                }
            } elseif ($request->filled('size_name')) {
                $size = ProductSize::where('product_id', $product->id)->where('name', $request->size_name)->first();
                if ($size) {
                    $sizeName = $size->name;
                    $unitPrice = $size->price;
                } else {
                    $sizeName = $request->size_name;
                }
            }

            $crustName = 'Pan Crust';
            $crustExtra = 0;
            if ($request->filled('crust_id')) {
                $crust = ProductCrust::find($request->crust_id);
                if ($crust) {
                    $crustName = $crust->name;
                    $crustExtra = $crust->extra_price;
                }
            } elseif ($request->filled('crust_name')) {
                $crust = ProductCrust::where('product_id', $product->id)->where('name', $request->crust_name)->first();
                if ($crust) {
                    $crustName = $crust->name;
                    $crustExtra = $crust->extra_price;
                } else {
                    $crustName = $request->crust_name;
                }
            }
        }

        // Add-ons
        $selectedAddons = [];
        $addonsExtra = 0;
        if ($request->has('addons') && is_array($request->addons)) {
            foreach ($request->addons as $addonId) {
                $addon = AddOn::find($addonId);
                if ($addon) {
                    $selectedAddons[] = [
                        'id' => $addon->id,
                        'name' => $addon->name,
                        'price' => (float) $addon->price,
                    ];
                    $addonsExtra += (float) $addon->price;
                }
            }
        }

        $singleItemPrice = $unitPrice + $crustExtra + $addonsExtra;
        $totalPrice = $singleItemPrice * $quantity;

        // Generate unique cart key based on product, size, crust, addons
        $addonIds = array_column($selectedAddons, 'id');
        sort($addonIds);
        $cartKey = md5($product->id . '_' . $sizeName . '_' . $crustName . '_' . implode(',', $addonIds) . '_' . ($request->special_instructions ?? ''));

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['total_price'] = $cart[$cartKey]['unit_price'] * $cart[$cartKey]['quantity'];
        } else {
            $cart[$cartKey] = [
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'image' => $itemImage,
                'reward_points' => (int) ($product->reward_points ?? 0),
                'size_name' => $sizeName,
                'crust_name' => $crustName,
                'addons' => $selectedAddons,
                'unit_price' => $singleItemPrice,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'special_instructions' => $request->special_instructions ?? '',
            ];
        }

        session()->put('cart', $cart);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Added to cart successfully!',
                'cartCount' => $cartCount,
                'cart' => $cart,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to your cart!');
    }

    /**
     * Update quantity of cart item.
     */
    public function update(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        $quantity = (int) $request->input('quantity', 1);

        $itemTotal = 0;
        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $quantity;
                $cart[$key]['total_price'] = $cart[$key]['unit_price'] * $quantity;
                $itemTotal = $cart[$key]['total_price'];
            }
            session()->put('cart', $cart);
        }

        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'cartCount' => array_sum(array_column($cart, 'quantity')),
            'itemTotal' => $itemTotal,
            'subtotal' => $totals['subtotal'],
            'deliveryCharge' => $totals['deliveryCharge'],
            'couponDiscount' => $totals['couponDiscount'],
            'pointsDiscount' => $totals['pointsDiscount'],
            'total' => $totals['total'],
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'cartCount' => array_sum(array_column($cart, 'quantity')),
            'subtotal' => $totals['subtotal'],
            'deliveryCharge' => $totals['deliveryCharge'],
            'couponDiscount' => $totals['couponDiscount'],
            'pointsDiscount' => $totals['pointsDiscount'],
            'total' => $totals['total'],
        ]);
    }

    /**
     * Apply coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $cart = session()->get('cart', []);
        $subtotal = array_sum(array_column($cart, 'total_price'));

        if ($subtotal <= 0) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
        }

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code "' . $code . '".']);
        }

        $validation = $coupon->validateForSubtotal($subtotal);
        if (!$validation['valid']) {
            return response()->json(['success' => false, 'message' => $validation['message']]);
        }

        $appliedCoupon = [
            'code' => $coupon->code,
            'discount' => $validation['discount'],
            'description' => $coupon->description,
            'type' => $coupon->type,
            'value' => $coupon->value,
        ];
        session()->put('coupon', $appliedCoupon);

        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Coupon ' . $coupon->code . ' applied! You saved ৳' . number_format($validation['discount']) . '.',
            'coupon' => $appliedCoupon,
            'subtotal' => $totals['subtotal'],
            'couponDiscount' => $totals['couponDiscount'],
            'pointsDiscount' => $totals['pointsDiscount'],
            'deliveryCharge' => $totals['deliveryCharge'],
            'total' => $totals['total'],
        ]);
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon()
    {
        session()->forget('coupon');
        session()->save();
        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'subtotal' => $totals['subtotal'],
            'couponDiscount' => 0,
            'pointsDiscount' => $totals['pointsDiscount'],
            'earnedPoints' => $totals['earnedPoints'],
            'deliveryCharge' => $totals['deliveryCharge'],
            'total' => $totals['total'],
        ]);
    }

    /**
     * Redeem customer reward points.
     */
    public function applyPoints(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in to redeem your reward points.']);
        }

        $user = Auth::user();
        $userPoints = (int) $user->reward_points;

        if ($userPoints <= 0) {
            return response()->json(['success' => false, 'message' => 'You currently have 0 reward points.']);
        }

        $cart = session()->get('cart', []);
        $subtotal = array_sum(array_column($cart, 'total_price'));

        if ($subtotal <= 0) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
        }

        $minPoints = (int) Setting::getValue('min_redeem_points', 1);
        if ($userPoints < $minPoints) {
            return response()->json(['success' => false, 'message' => 'Minimum ' . $minPoints . ' reward points required to redeem.']);
        }

        $requestedPoints = (int) $request->input('points', $userPoints);
        if ($requestedPoints <= 0) {
            return response()->json(['success' => false, 'message' => 'Please specify points to redeem.']);
        }

        if ($requestedPoints < $minPoints) {
            return response()->json(['success' => false, 'message' => 'Please enter at least ' . $minPoints . ' points.']);
        }

        if ($requestedPoints > $userPoints) {
            return response()->json(['success' => false, 'message' => 'You only have ' . $userPoints . ' points available.']);
        }

        $coupon = session()->get('coupon');
        $couponDiscount = $coupon ? (float)$coupon['discount'] : 0;
        $maxAllowed = max(0, $subtotal - $couponDiscount);

        // Calculate discount using admin's configured redemption rate
        $calculatedDiscount = Setting::getPointDiscount($requestedPoints);
        if ($calculatedDiscount > $maxAllowed) {
            $pointsDiscount = (int) $maxAllowed;
            $requestedPoints = Setting::getPointsForDiscount($maxAllowed);
        } else {
            $pointsDiscount = (int) $calculatedDiscount;
        }

        if ($pointsDiscount <= 0) {
            return response()->json(['success' => false, 'message' => 'Total order is already fully covered by discounts.']);
        }

        $appliedPoints = [
            'points' => $requestedPoints,
            'discount' => $pointsDiscount,
        ];
        session()->put('points', $appliedPoints);
        session()->save();

        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Redeemed ' . $requestedPoints . ' points! (৳' . number_format($pointsDiscount) . ' discount)',
            'points' => $appliedPoints,
            'subtotal' => $totals['subtotal'],
            'couponDiscount' => $totals['couponDiscount'],
            'pointsDiscount' => $totals['pointsDiscount'],
            'earnedPoints' => $totals['earnedPoints'],
            'deliveryCharge' => $totals['deliveryCharge'],
            'total' => $totals['total'],
        ]);
    }

    /**
     * Remove redeemed reward points.
     */
    public function removePoints()
    {
        session()->forget('points');
        session()->save();
        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Reward points removed.',
            'subtotal' => $totals['subtotal'],
            'couponDiscount' => $totals['couponDiscount'],
            'pointsDiscount' => 0,
            'earnedPoints' => $totals['earnedPoints'],
            'deliveryCharge' => $totals['deliveryCharge'],
            'total' => $totals['total'],
        ]);
    }

    /**
     * Calculate current subtotal, discounts, earned points and total.
     */
    private function calculateTotals(): array
    {
        $cart = session()->get('cart', []);
        $subtotal = array_sum(array_column($cart, 'total_price'));

        // Calculate earned reward points based on each product's setting
        $earnedPoints = 0;
        foreach ($cart as $item) {
            $pPoints = isset($item['reward_points']) ? (int)$item['reward_points'] : 0;
            if ($pPoints === 0 && !empty($item['product_id'])) {
                $prod = Product::find($item['product_id']);
                if ($prod) $pPoints = (int)$prod->reward_points;
            }
            $earnedPoints += ($pPoints * (int)($item['quantity'] ?? 1));
        }

        $coupon = session()->get('coupon');
        $couponDiscount = 0;
        if ($coupon && $subtotal > 0) {
            $couponModel = Coupon::where('code', $coupon['code'])->where('is_active', true)->first();
            if ($couponModel) {
                $val = $couponModel->validateForSubtotal($subtotal);
                if ($val['valid']) {
                    $couponDiscount = $val['discount'];
                } else {
                    session()->forget('coupon');
                    session()->save();
                }
            } else {
                session()->forget('coupon');
                session()->save();
            }
        } else {
            session()->forget('coupon');
        }

        $userPoints = Auth::check() ? (int)Auth::user()->reward_points : 0;
        $points = session()->get('points');
        $pointsDiscount = 0;
        $pointsUsed = 0;
        if ($points && Auth::check() && $userPoints > 0 && $subtotal > 0) {
            $maxAllowed = max(0, $subtotal - $couponDiscount);
            $pointsUsed = min((int)$points['points'], $userPoints);
            $calculatedDiscount = Setting::getPointDiscount($pointsUsed);
            if ($calculatedDiscount > $maxAllowed) {
                $pointsDiscount = (int) $maxAllowed;
                $pointsUsed = Setting::getPointsForDiscount($maxAllowed);
            } else {
                $pointsDiscount = (int) $calculatedDiscount;
            }
            if ($pointsDiscount > 0) {
                session()->put('points', ['points' => $pointsUsed, 'discount' => $pointsDiscount]);
            } else {
                session()->forget('points');
                session()->save();
            }
        } else {
            session()->forget('points');
        }

        $baseDeliveryCharge = \App\Models\Setting::getDeliveryCharge();
        $freeDeliveryMin = (float) \App\Models\Setting::getValue('free_delivery_min', 0);
        if (count($cart) > 0) {
            if ($freeDeliveryMin > 0 && $subtotal >= $freeDeliveryMin) {
                $deliveryCharge = 0;
            } else {
                $deliveryCharge = $baseDeliveryCharge;
            }
        } else {
            $deliveryCharge = 0;
        }

        $discountTotal = $couponDiscount + $pointsDiscount;
        $total = max(0, $subtotal - $discountTotal) + $deliveryCharge;

        return [
            'subtotal' => $subtotal,
            'couponDiscount' => $couponDiscount,
            'pointsDiscount' => $pointsDiscount,
            'pointsUsed' => $pointsUsed,
            'earnedPoints' => $earnedPoints,
            'deliveryCharge' => $deliveryCharge,
            'total' => $total,
        ];
    }
}
