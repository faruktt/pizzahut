<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'items.ingredient'])->latest();

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('purchase_number', 'like', "%{$search}%");
        }

        $totalPurchased = Purchase::sum('total_amount');
        $totalPaid = Purchase::sum('paid_amount');
        $totalDue = max(0, $totalPurchased - $totalPaid);

        $purchases = $query->paginate(15)->withQueryString();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('admin.purchases.index', compact('purchases', 'suppliers', 'totalPurchased', 'totalPaid', 'totalDue'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        return view('admin.purchases.create', compact('suppliers', 'ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'purchase_date'  => 'required|date',
            'payment_status' => 'required|in:paid,partial,unpaid',
            'payment_method' => 'required|string',
            'paid_amount'    => 'nullable|numeric|min:0',
            'note'           => 'nullable|string|max:1000',
            'items'          => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity'      => 'required|numeric|min:0.01',
            'items.*.unit_price'    => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += ($item['quantity'] * $item['unit_price']);
            }

            $paidAmount = $request->paid_amount ?? 0;
            if ($request->payment_status === 'paid') {
                $paidAmount = $totalAmount;
            } elseif ($request->payment_status === 'unpaid') {
                $paidAmount = 0;
            }

            $purchaseNumber = 'PUR-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            $purchase = Purchase::create([
                'purchase_number' => $purchaseNumber,
                'supplier_id'     => $request->supplier_id,
                'purchase_date'   => $request->purchase_date,
                'total_amount'    => $totalAmount,
                'paid_amount'     => $paidAmount,
                'payment_status'  => $request->payment_status,
                'payment_method'  => $request->payment_method,
                'note'            => $request->note,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];

                PurchaseItem::create([
                    'purchase_id'   => $purchase->id,
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $item['unit_price'],
                    'subtotal'      => $subtotal,
                ]);

                // Increment stock & update cost price
                $ingredient = Ingredient::find($item['ingredient_id']);
                if ($ingredient) {
                    $ingredient->increment('current_stock', $item['quantity']);
                    if ($item['unit_price'] > 0) {
                        $ingredient->update(['cost_price' => $item['unit_price']]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', "Purchase #{$purchaseNumber} created successfully and stock updated.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error saving purchase: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.ingredient'])->findOrFail($id);
        return view('admin.purchases.show', compact('purchase'));
    }

    public function destroy($id)
    {
        $purchase = Purchase::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Revert stock for each item
            foreach ($purchase->items as $item) {
                $ingredient = Ingredient::find($item->ingredient_id);
                if ($ingredient) {
                    $newStock = max(0, $ingredient->current_stock - $item->quantity);
                    $ingredient->update(['current_stock' => $newStock]);
                }
            }

            $purchase->delete();
            DB::commit();

            return redirect()->route('admin.purchases.index')->with('success', 'Purchase deleted and stock reverted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.purchases.index')->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }
}

