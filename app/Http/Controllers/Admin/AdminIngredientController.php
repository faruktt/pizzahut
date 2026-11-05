<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class AdminIngredientController extends Controller
{
    public function index(Request $request)
    {
        $query = Ingredient::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filter === 'low_stock') {
            $query->whereColumn('current_stock', '<=', 'alert_quantity');
        }

        $totalIngredients = Ingredient::count();
        $lowStockCount = Ingredient::whereColumn('current_stock', '<=', 'alert_quantity')->count();
        $ingredients = $query->paginate(15)->withQueryString();

        return view('admin.ingredients.index', compact('ingredients', 'totalIngredients', 'lowStockCount'));
    }

    public function create()
    {
        return view('admin.ingredients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:150',
            'code'           => 'nullable|string|max:50|unique:ingredients,code',
            'unit'           => 'required|string|max:20',
            'cost_price'     => 'required|numeric|min:0',
            'current_stock'  => 'nullable|numeric|min:0',
            'alert_quantity' => 'nullable|numeric|min:0',
        ]);

        Ingredient::create([
            'name'           => $request->name,
            'code'           => $request->code,
            'unit'           => $request->unit,
            'cost_price'     => $request->cost_price,
            'current_stock'  => $request->current_stock ?? 0,
            'alert_quantity' => $request->alert_quantity ?? 5,
            'is_active'      => $request->has('is_active'),
        ]);

        return redirect()->route('admin.ingredients.index')->with('success', 'Ingredient added successfully.');
    }

    public function edit($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:150',
            'code'           => 'nullable|string|max:50|unique:ingredients,code,' . $id,
            'unit'           => 'required|string|max:20',
            'cost_price'     => 'required|numeric|min:0',
            'current_stock'  => 'required|numeric|min:0',
            'alert_quantity' => 'required|numeric|min:0',
        ]);

        $ingredient->update([
            'name'           => $request->name,
            'code'           => $request->code,
            'unit'           => $request->unit,
            'cost_price'     => $request->cost_price,
            'current_stock'  => $request->current_stock,
            'alert_quantity' => $request->alert_quantity,
            'is_active'      => $request->has('is_active'),
        ]);

        return redirect()->route('admin.ingredients.index')->with('success', 'Ingredient updated successfully.');
    }

    public function destroy($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        return redirect()->route('admin.ingredients.index')->with('success', 'Ingredient deleted successfully.');
    }

    public function adjustStock(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);

        $request->validate([
            'action' => 'required|in:add,subtract',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($request->action === 'add') {
            $ingredient->increment('current_stock', $request->amount);
        } else {
            $newStock = max(0, $ingredient->current_stock - $request->amount);
            $ingredient->update(['current_stock' => $newStock]);
        }

        return redirect()->route('admin.ingredients.index')->with('success', 'Stock adjusted successfully.');
    }
}

