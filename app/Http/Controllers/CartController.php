<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductCrust;
use App\Models\ProductVariation;
use App\Models\AddOn;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Show Cart Details page (matching image 3).
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['total_price'];
        }

        $deliveryCharge = count($cart) > 0 ? 70 : 0;
        $total = $subtotal + $deliveryCharge;

        // Sides for "You may also like"
        $recommendations = Product::whereHas('category', function ($q) {
            $q->where('slug', 'sides');
        })->where('is_active', true)->take(4)->get();

        if ($recommendations->isEmpty()) {
            $recommendations = Product::where('is_active', true)->where('is_bestseller', false)->take(4)->get();
        }

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('cart', compact('cart', 'subtotal', 'deliveryCharge', 'total', 'recommendations', 'cartCount'));
    }

    /**
     * Add item to cart with customization.
     */
    public function add(Request $request)
    {
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

        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $quantity;
                $cart[$key]['total_price'] = $cart[$key]['unit_price'] * $quantity;
            }
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $subtotal = array_sum(array_column($cart, 'total_price'));
            $delivery = count($cart) > 0 ? 70 : 0;
            return response()->json([
                'success' => true,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
                'itemTotal' => isset($cart[$key]) ? $cart[$key]['total_price'] : 0,
                'subtotal' => $subtotal,
                'deliveryCharge' => $delivery,
                'total' => $subtotal + $delivery,
            ]);
        }

        return redirect()->route('cart.index');
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

        if ($request->ajax() || $request->wantsJson()) {
            $subtotal = array_sum(array_column($cart, 'total_price'));
            $delivery = count($cart) > 0 ? 70 : 0;
            return response()->json([
                'success' => true,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
                'subtotal' => $subtotal,
                'deliveryCharge' => $delivery,
                'total' => $subtotal + $delivery,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }
}
