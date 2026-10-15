<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductCrust;
use App\Models\ProductVariation;
use App\Models\Size;
use App\Models\Crust;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variations', 'sizes', 'crusts'])->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products   = $query->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $sizes      = Size::where('is_active', true)->orderBy('name')->get();
        $crusts     = Crust::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'sizes', 'crusts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:150',
            'category_id'   => 'required|exists:categories,id',
            'base_price'    => 'required|numeric|min:0',
            'reward_points' => 'nullable|integer|min:0',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = [
            'name'          => $request->name,
            'slug'          => Str::slug($request->name) . '-' . rand(100, 999),
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'base_price'    => $request->base_price,
            'reward_points' => (int) $request->input('reward_points', 0),
            'is_bestseller' => $request->has('is_bestseller'),
            'is_new'        => $request->has('is_new'),
            'is_deal'       => false,
            'is_active'     => true,
        ];

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $product = Product::create($data);

        // Process Product Variations (Size + Crust with individual price and image)
        $this->syncVariations($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product    = Product::with(['allVariations', 'sizes', 'crusts'])->findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $sizes      = Size::where('is_active', true)->orderBy('name')->get();
        $crusts     = Crust::where('is_active', true)->orderBy('name')->get();

        // Map variations by size_id and crust_id: [size_id => [crust_id => variation]]
        $existingVariations = [];
        foreach ($product->allVariations as $var) {
            $existingVariations[$var->size_id][$var->crust_id] = $var;
        }

        $selectedSizeIds = array_keys($existingVariations);

        return view('admin.products.edit', compact(
            'product', 'categories', 'sizes', 'crusts',
            'existingVariations', 'selectedSizeIds'
        ));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:150',
            'category_id'   => 'required|exists:categories,id',
            'base_price'    => 'required|numeric|min:0',
            'reward_points' => 'nullable|integer|min:0',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = [
            'name'          => $request->name,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'base_price'    => $request->base_price,
            'reward_points' => (int) $request->input('reward_points', 0),
            'is_bestseller' => $request->has('is_bestseller'),
            'is_new'        => $request->has('is_new'),
            'is_active'     => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
                @unlink(public_path('uploads/' . $product->image));
            }
            $file     = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $product->update($data);

        // Process Product Variations
        $this->syncVariations($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
            @unlink(public_path('uploads/' . $product->image));
        }

        // Clean variation images
        foreach ($product->allVariations as $var) {
            if ($var->image && file_exists(public_path('uploads/' . $var->image))) {
                @unlink(public_path('uploads/' . $var->image));
            }
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    /**
     * Helper to sync variations and keep backward-compatible product_sizes/crusts.
     */
    private function syncVariations(Request $request, Product $product)
    {
        // Delete previous variations
        $product->allVariations()->delete();

        $uniqueSizes = [];
        $uniqueCrusts = [];

        if ($request->has('variations') && is_array($request->variations)) {
            foreach ($request->variations as $sizeId => $crustsData) {
                // Check if size itself was selected/enabled
                if (!$request->has("size_selected_{$sizeId}")) {
                    continue;
                }

                $size = Size::find($sizeId);
                if (!$size) continue;

                foreach ($crustsData as $crustId => $vData) {
                    if (empty($vData['enabled'])) {
                        continue;
                    }

                    $crust = Crust::find($crustId);
                    if (!$crust) continue;

                    $price = isset($vData['price']) && $vData['price'] !== '' ? (float) $vData['price'] : $product->base_price;
                    $varImage = $vData['existing_image'] ?? null;

                    // Check if a new image was uploaded for this variation
                    $fileKey = "variation_image_{$sizeId}_{$crustId}";
                    if ($request->hasFile($fileKey)) {
                        $vFile = $request->file($fileKey);
                        $vFilename = 'var_' . time() . '_' . rand(100, 999) . '.' . $vFile->getClientOriginalExtension();
                        $vFile->move(public_path('uploads'), $vFilename);
                        $varImage = $vFilename;
                    }

                    ProductVariation::create([
                        'product_id' => $product->id,
                        'size_id'    => $size->id,
                        'size_name'  => $size->name,
                        'crust_id'   => $crust->id,
                        'crust_name' => $crust->name,
                        'price'      => $price,
                        'image'      => $varImage,
                        'is_active'  => true,
                    ]);

                    if (!isset($uniqueSizes[$size->name])) {
                        $uniqueSizes[$size->name] = $price;
                    }
                    if (!isset($uniqueCrusts[$crust->name])) {
                        $uniqueCrusts[$crust->name] = true;
                    }
                }
            }
        }

        // Backward-compatibility: sync product_sizes & product_crusts
        $product->sizes()->delete();
        foreach ($uniqueSizes as $sName => $sPrice) {
            ProductSize::create([
                'product_id' => $product->id,
                'name'       => $sName,
                'price'      => $sPrice,
            ]);
        }

        $product->crusts()->delete();
        foreach ($uniqueCrusts as $cName => $_) {
            ProductCrust::create([
                'product_id'  => $product->id,
                'name'        => $cName,
                'extra_price' => 0,
            ]);
        }
    }
}
