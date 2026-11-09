<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use App\Models\ProductVariation;
use App\Models\Size;
use App\Models\Crust;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    /**
     * Show combo detail page - customer selects products with size/crust.
     */
    public function show($id)
    {
        $combo = Combo::with(['products.variations.size', 'products.variations.crust'])->findOrFail($id);

        if (!$combo->is_active) {
            return redirect()->route('home');
        }

        // Enrich products with variation data
        foreach ($combo->products as $product) {
            $pizzaMap = [];
            foreach ($product->variations as $v) {
                $sizeName  = $v->size->name ?? 'Regular';
                $crustName = $v->crust->name ?? 'Pan Crust';
                if (!isset($pizzaMap[$sizeName])) {
                    $pizzaMap[$sizeName] = [];
                }
                $pizzaMap[$sizeName][$crustName] = [
                    'price' => $v->price,
                    'image' => $v->image,
                    'variation_id' => $v->id,
                ];
            }
            $product->pizza_map = $pizzaMap;
        }

        $cart     = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('combo_details', compact('combo', 'cartCount'));
    }

    /**
     * Add selected combo items to cart.
     */
    public function addToCart(Request $request, $id)
    {
        $combo = Combo::findOrFail($id);

        $request->validate([
            'selected_items'             => 'required|array|min:1',
            'selected_items.*.product_id' => 'required|exists:products,id',
            'selected_items.*.size'       => 'required|string',
            'selected_items.*.crust'      => 'required|string',
        ]);

        $selectedItems   = [];
        $selectedNames   = [];
        $totalItemsPrice = 0;

        foreach ($request->selected_items as $item) {
            $variation = ProductVariation::whereHas('size', fn($q) => $q->where('name', $item['size']))
                ->whereHas('crust', fn($q) => $q->where('name', $item['crust']))
                ->where('product_id', $item['product_id'])
                ->first();

            $price = $variation ? $variation->price : 0;
            $image = $variation ? $variation->image : null;

            $product = \App\Models\Product::find($item['product_id']);

            $selectedItems[] = [
                'product_id'   => $item['product_id'],
                'product_name' => $product->name ?? '',
                'size'         => $item['size'],
                'crust'        => $item['crust'],
                'price'        => $price,
                'image'        => $image,
            ];
            $selectedNames[]  = ($product->name ?? '') . ' (' . $item['size'] . ')';
            $totalItemsPrice += $price;
        }

        // Use combo's fixed price (admin set), or sum of items if 0
        $unitPrice = (float) $combo->price > 0 ? (float) $combo->price : $totalItemsPrice;
        $quantity  = (int) ($request->quantity ?? 1);

        $cartKey = md5('combo_' . $combo->id . '_' . json_encode($request->selected_items));

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']    += $quantity;
            $cart[$cartKey]['total_price']  = $cart[$cartKey]['unit_price'] * $cart[$cartKey]['quantity'];
        } else {
            $cart[$cartKey] = [
                'cart_key'         => $cartKey,
                'product_id'       => null,
                'combo_id'         => $combo->id,
                'is_combo'         => true,
                'name'             => $combo->name,
                'image'            => $combo->image,
                'size_name'        => 'Combo (' . count($selectedItems) . ' Items)',
                'crust_name'       => '',
                'combo_items'      => $selectedItems,
                'combo_summary'    => implode(' + ', $selectedNames),
                'addons'           => [],
                'unit_price'       => $unitPrice,
                'quantity'         => $quantity,
                'total_price'      => $unitPrice * $quantity,
                'special_instructions' => $request->special_instructions ?? '',
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Combo added to cart!',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Combo added to your cart!');
    }
}
