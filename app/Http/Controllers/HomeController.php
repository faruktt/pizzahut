<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Product;
use App\Models\AddOn;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show frontend homepage (matching image 1).
     */
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        // Combo section
        $combos = Combo::where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        $bestSellers = Product::where('is_bestseller', true)
            ->where('is_active', true)
            ->with(['variations', 'sizes', 'crusts'])
            ->get();

        $addons = AddOn::where('is_active', true)->get();

        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();

        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('home', compact('categories', 'combos', 'bestSellers', 'addons', 'banners', 'cartCount'));
    }

    /**
     * Get product customization details for modal (matching image 2).
     */
    public function getProductDetails($id)
    {
        $product = Product::with(['variations', 'sizes', 'crusts'])->findOrFail($id);
        $addons = AddOn::where('is_active', true)->get();

        return response()->json([
            'product' => $product,
            'variations' => $product->variations,
            'sizes' => $product->sizes,
            'crusts' => $product->crusts,
            'addons' => $addons,
        ]);
    }

    /**
     * Show custom CMS page by slug.
     */
    public function showPage($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('page', compact('page', 'cartCount'));
    }
}
