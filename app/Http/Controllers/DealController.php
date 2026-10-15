<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DealController extends Controller
{
    /**
     * Show Deal Combo Details page with admin-selected products for customer to choose.
     */
    public function show($id)
    {
        $deal = Product::with('comboProducts')->findOrFail($id);

        if (!$deal->is_deal) {
            return redirect()->route('home');
        }

        // If no combo products assigned yet, fallback to all active products
        $eligibleProducts = $deal->comboProducts;
        if ($eligibleProducts->isEmpty()) {
            $eligibleProducts = Product::where('is_deal', false)->where('is_active', true)->get();
        }

        $comboSlotsCount = $deal->combo_items_count ?: 2;
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('deal_details', compact('deal', 'eligibleProducts', 'comboSlotsCount', 'cartCount'));
    }

    /**
     * Add selected combo deal to cart.
     */
    public function addComboToCart(Request $request, $id)
    {
        if (!\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            session()->put('url.intended', route('deal.show', $id));
            return redirect()->route('login')->with('error', 'কম্বো ডিল কার্টে যোগ করতে অনুগ্রহ করে প্রথমে লগইন করুন। (Please login to add deal combo to cart)');
        }

        $deal = Product::with('comboProducts')->findOrFail($id);
        $comboSlotsCount = $deal->combo_items_count ?: 2;

        $request->validate([
            'combo_items' => 'required|array|min:' . $comboSlotsCount,
            'combo_items.*' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = (int) ($request->quantity ?? 1);

        // Fetch selected products details
        $selectedItems = [];
        $selectedItemNames = [];
        foreach ($request->combo_items as $index => $prodId) {
            $prod = Product::find($prodId);
            if ($prod) {
                $selectedItems[] = [
                    'slot' => $index + 1,
                    'product_id' => $prod->id,
                    'name' => $prod->name,
                ];
                $selectedItemNames[] = $prod->name;
            }
        }

        $cartKey = md5('deal_' . $deal->id . '_' . implode('_', $request->combo_items) . '_' . ($request->special_instructions ?? ''));

        $cart = session()->get('cart', []);

        $unitPrice = (float) $deal->base_price;
        $totalPrice = $unitPrice * $quantity;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['total_price'] = $cart[$cartKey]['unit_price'] * $cart[$cartKey]['quantity'];
        } else {
            $cart[$cartKey] = [
                'cart_key' => $cartKey,
                'product_id' => $deal->id,
                'is_combo' => true,
                'name' => $deal->name,
                'image' => $deal->image,
                'size_name' => 'Combo (' . $comboSlotsCount . ' Items)',
                'crust_name' => 'Assorted Crust',
                'combo_items' => $selectedItems,
                'combo_summary' => implode(' + ', $selectedItemNames),
                'addons' => [],
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'special_instructions' => $request->special_instructions ?? '',
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Combo added to cart!',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Combo deal added to your cart!');
    }
}
