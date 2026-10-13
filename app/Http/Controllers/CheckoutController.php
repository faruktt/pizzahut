<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
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
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items to checkout.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['total_price'];
        }

        $deliveryCharge = 70;
        $vatTax = round($subtotal * 0.05, 2); // 5% VAT
        $total = $subtotal + $deliveryCharge + $vatTax;

        $user = Auth::guard('web')->user();

        return view('checkout', compact('cart', 'subtotal', 'deliveryCharge', 'vatTax', 'total', 'user'));
    }

    /**
     * Store placed order (Supports both logged-in customer and guest / without customer).
     */
    public function store(Request $request)
    {
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

        $deliveryCharge = ($request->order_type === 'delivery') ? 70 : 0;
        $vatTax = round($subtotal * 0.05, 2);
        $total = $subtotal + $deliveryCharge + $vatTax;

        $orderNumber = 'ORD-' . strtoupper(Str::random(6));

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::guard('web')->id(), // nullable if guest
            'order_type' => $request->order_type,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'delivery_location' => $request->delivery_location ?? 'Dhaka, Bangladesh',
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'vat_tax' => $vatTax,
            'discount' => 0,
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

        // Clear cart
        session()->forget('cart');

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
