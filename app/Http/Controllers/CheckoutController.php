<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show Checkout Review & Payment page.
     */
    public function index()
    {
        if (!Auth::guard('web')->check()) {
            session()->put('url.intended', route('checkout.index'));
            return redirect()->route('login')->with('error', 'অর্ডার করতে অনুগ্রহ করে প্রথমে লগইন করুন। (Please login to proceed with your order)');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items to checkout.');
        }

        $subtotal = 0;
        $pointsEarned = 0;
        foreach ($cart as $item) {
            $subtotal += $item['total_price'];
            $pPoints = isset($item['reward_points']) ? (int)$item['reward_points'] : 0;
            if ($pPoints === 0 && !empty($item['product_id'])) {
                $p = Product::find($item['product_id']);
                if ($p) $pPoints = (int)$p->reward_points;
            }
            $pointsEarned += ($pPoints * (int)($item['quantity'] ?? 1));
        }

        // Coupon calculation
        $couponSession = session()->get('coupon');
        $couponDiscount = 0;
        $couponCode = null;
        if ($couponSession) {
            $couponCode = $couponSession['code'];
            $couponDiscount = min((float)$couponSession['discount'], $subtotal);
        }

        // Reward points calculation using admin conversion rate
        $user = Auth::guard('web')->user();
        $userPoints = $user ? (int)$user->reward_points : 0;
        $pointsSession = session()->get('points');
        $pointsDiscount = 0;
        $pointsUsed = 0;
        if ($pointsSession && $user && $userPoints > 0) {
            $maxAllowed = max(0, $subtotal - $couponDiscount);
            $pointsUsed = min((int)$pointsSession['points'], $userPoints);
            $calcDiscount = Setting::getPointDiscount($pointsUsed);
            if ($calcDiscount > $maxAllowed) {
                $pointsDiscount = (int)$maxAllowed;
                $pointsUsed = Setting::getPointsForDiscount($maxAllowed);
            } else {
                $pointsDiscount = (int)$calcDiscount;
            }
        }

        $baseDeliveryCharge = Setting::getDeliveryCharge();
        $freeDeliveryMin = (float) Setting::getValue('free_delivery_min', 0);
        $deliveryCharge = ($freeDeliveryMin > 0 && $subtotal >= $freeDeliveryMin) ? 0 : $baseDeliveryCharge;
        $vatTax = round($subtotal * 0.05, 2); // 5% VAT
        $totalDiscount = $couponDiscount + $pointsDiscount;
        $total = max(0, $subtotal - $totalDiscount) + $deliveryCharge + $vatTax;

        return view('checkout', compact(
            'cart', 'subtotal', 'deliveryCharge', 'vatTax', 'total', 'user',
            'couponCode', 'couponDiscount', 'pointsUsed', 'pointsDiscount', 'totalDiscount', 'userPoints', 'pointsEarned'
        ));
    }

    /**
     * Store placed order (Supports both logged-in customer and guest / without customer).
     */
    public function store(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            session()->put('url.intended', route('checkout.index'));
            return redirect()->route('login')->with('error', 'অর্ডার সম্পন্ন করতে অনুগ্রহ করে প্রথমে লগইন করুন। (Please login to complete your order)');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required_if:order_type,delivery|nullable|string',
            'order_type' => 'required|in:delivery,takeaway',
            'payment_method' => 'required|in:cash,card,bkash',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['total_price'];
        }

        // Coupon calculation
        $couponSession = session()->get('coupon');
        $couponDiscount = 0;
        $couponCode = null;
        if ($couponSession) {
            $coupon = Coupon::where('code', $couponSession['code'])->where('is_active', true)->first();
            if ($coupon) {
                $validation = $coupon->validateForSubtotal($subtotal);
                if ($validation['valid']) {
                    $couponCode = $coupon->code;
                    $couponDiscount = $validation['discount'];
                    $coupon->increment('used_count');
                }
            }
        }

        // Reward points calculation using admin conversion rate
        $user = Auth::guard('web')->user();
        $userPoints = $user ? (int)$user->reward_points : 0;
        $pointsSession = session()->get('points');
        $pointsDiscount = 0;
        $pointsUsed = 0;
        if ($pointsSession && $user && $userPoints > 0) {
            $maxAllowed = max(0, $subtotal - $couponDiscount);
            $pointsUsed = min((int)$pointsSession['points'], $userPoints);
            $calcDiscount = Setting::getPointDiscount($pointsUsed);
            if ($calcDiscount > $maxAllowed) {
                $pointsDiscount = (int)$maxAllowed;
                $pointsUsed = Setting::getPointsForDiscount($maxAllowed);
            } else {
                $pointsDiscount = (int)$calcDiscount;
            }
        }

        $baseDeliveryCharge = Setting::getDeliveryCharge();
        $freeDeliveryMin = (float) Setting::getValue('free_delivery_min', 0);
        if ($request->order_type === 'delivery') {
            $deliveryCharge = ($freeDeliveryMin > 0 && $subtotal >= $freeDeliveryMin) ? 0 : $baseDeliveryCharge;
        } else {
            $deliveryCharge = 0;
        }
        $vatTax = round($subtotal * 0.05, 2);
        $totalDiscount = $couponDiscount + $pointsDiscount;
        $total = max(0, $subtotal - $totalDiscount) + $deliveryCharge + $vatTax;

        // Points earned from this order: sum of product reward points
        $pointsEarned = 0;
        foreach ($cart as $item) {
            $pPoints = isset($item['reward_points']) ? (int)$item['reward_points'] : 0;
            if ($pPoints === 0 && !empty($item['product_id'])) {
                $p = Product::find($item['product_id']);
                if ($p) $pPoints = (int)$p->reward_points;
            }
            $pointsEarned += ($pPoints * (int)($item['quantity'] ?? 1));
        }

        if ($user) {
            // Deduct redeemed points & credit newly earned points
            $newUserPoints = max(0, $user->reward_points - $pointsUsed) + $pointsEarned;
            $user->reward_points = $newUserPoints;
            $user->save();
        }

        $orderNumber = 'ORD-' . strtoupper(Str::random(6));

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user ? $user->id : null,
            'order_type' => $request->order_type,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'delivery_location' => $request->delivery_location ?? 'Dhaka, Bangladesh',
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'vat_tax' => $vatTax,
            'discount' => $totalDiscount,
            'coupon_code' => $couponCode,
            'coupon_discount' => $couponDiscount,
            'points_used' => $pointsUsed,
            'points_discount' => $pointsDiscount,
            'points_earned' => $pointsEarned,
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => ($request->payment_method === 'cash') ? 'pending' : 'paid',
            'order_status' => 'pending',
            'special_instructions' => $request->special_instructions,
        ]);

        foreach ($cart as $item) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'size_name' => $item['size_name'],
                'crust_name' => $item['crust_name'],
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['total_price'],
                'special_instructions' => $item['special_instructions'] ?? null,
                'combo_details' => $item['combo_summary'] ?? (isset($item['combo_items']) ? json_encode($item['combo_items']) : null),
            ]);

            if (!empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    OrderItemAddon::create([
                        'order_item_id' => $orderItem->id,
                        'add_on_name' => $addon['name'],
                        'price' => $addon['price'],
                    ]);
                }
            }
        }

        // Clear cart, coupon, and points session
        session()->forget(['cart', 'coupon', 'points']);

        return redirect()->route('checkout.success', $order->order_number)->with('success', 'Order placed successfully!');
    }

    /**
     * Show order success and live tracking details.
     */
    public function success($orderNumber)
    {
        $order = Order::with(['items.addons'])->where('order_number', $orderNumber)->firstOrFail();
        return view('order_success', compact('order'));
    }
}
