<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminComboController extends Controller
{
    public function index()
    {
        $combos = Combo::withCount('products')->orderBy('sort_order')->get();
        return view('admin.combos.index', compact('combos'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.combos.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'max_select'  => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'product_ids' => 'required|array|min:2',
            'product_ids.*' => 'exists:products,id',
        ]);

        $data = $request->only(['name', 'description', 'price', 'max_select', 'sort_order', 'is_active']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_combo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $combo = Combo::create($data);

        // Attach products with sort order
        $pivot = [];
        foreach ($request->product_ids as $index => $pid) {
            $pivot[$pid] = ['sort_order' => $index];
        }
        $combo->products()->sync($pivot);

        return redirect()->route('admin.combos.index')->with('success', 'Combo created successfully!');
    }

    public function edit($id)
    {
        $combo = Combo::with('products')->findOrFail($id);
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $selectedIds = $combo->products->pluck('id')->toArray();
        return view('admin.combos.edit', compact('combo', 'products', 'selectedIds'));
    }

    public function update(Request $request, $id)
    {
        $combo = Combo::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'max_select'  => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'product_ids' => 'required|array|min:2',
            'product_ids.*' => 'exists:products,id',
        ]);

        $data = $request->only(['name', 'description', 'price', 'max_select', 'sort_order']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image')) {
            // Remove old image
            if ($combo->image && file_exists(public_path('uploads/' . $combo->image))) {
                @unlink(public_path('uploads/' . $combo->image));
            }
            $file = $request->file('image');
            $filename = time() . '_combo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $combo->update($data);

        $pivot = [];
        foreach ($request->product_ids as $index => $pid) {
            $pivot[$pid] = ['sort_order' => $index];
        }
        $combo->products()->sync($pivot);

        return redirect()->route('admin.combos.index')->with('success', 'Combo updated successfully!');
    }

    public function destroy($id)
    {
        $combo = Combo::findOrFail($id);
        if ($combo->image && file_exists(public_path('uploads/' . $combo->image))) {
            @unlink(public_path('uploads/' . $combo->image));
        }
        $combo->products()->detach();
        $combo->delete();
        return redirect()->route('admin.combos.index')->with('success', 'Combo deleted.');
    }

    public function toggleStatus($id)
    {
        $combo = Combo::findOrFail($id);
        $combo->update(['is_active' => !$combo->is_active]);
        return redirect()->back()->with('success', 'Status updated.');
    }
}
