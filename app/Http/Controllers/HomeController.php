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

    /**
     * Show Category products page.
     */
    public function category(Request $request, $slug)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $query = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['variations', 'sizes', 'crusts', 'category']);

        // Search within category
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter: bestseller, new
        if ($request->input('filter') === 'bestseller') {
            $query->where('is_bestseller', true);
        } elseif ($request->input('filter') === 'new') {
            $query->where('is_new', true);
        }

        // Sort: price_asc, price_desc
        if ($request->input('sort') === 'price_asc') {
            $query->orderBy('base_price', 'asc');
        } elseif ($request->input('sort') === 'price_desc') {
            $query->orderBy('base_price', 'desc');
        } else {
            $query->orderBy('id', 'asc');
        }

        $products = $query->get();

        // If deals category, also fetch combos
        $combos = collect();
        if (strtolower($slug) === 'deals' || $category->id === 1) {
            $combos = Combo::where('is_active', true)
                ->withCount('products')
                ->orderBy('sort_order')
                ->get();
        }

        $addons = AddOn::where('is_active', true)->get();
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('category', compact('category', 'categories', 'products', 'combos', 'addons', 'cartCount'));
    }

    /**
     * Show All Menu / All Products page.
     */
    public function menu(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $query = Product::where('is_active', true)
            ->with(['variations', 'sizes', 'crusts', 'category']);

        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->input('category'))->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->input('filter') === 'bestseller') {
            $query->where('is_bestseller', true);
        } elseif ($request->input('filter') === 'new') {
            $query->where('is_new', true);
        }

        if ($request->input('sort') === 'price_asc') {
            $query->orderBy('base_price', 'asc');
        } elseif ($request->input('sort') === 'price_desc') {
            $query->orderBy('base_price', 'desc');
        } else {
            $query->orderBy('id', 'asc');
        }

        $products = $query->get();

        $combos = Combo::where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        $addons = AddOn::where('is_active', true)->get();
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $category = null;

        return view('category', compact('category', 'categories', 'products', 'combos', 'addons', 'cartCount'));
    }
}
