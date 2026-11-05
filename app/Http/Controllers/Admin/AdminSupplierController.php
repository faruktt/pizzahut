<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AdminSupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchases')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->paginate(15)->withQueryString();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'company_name' => 'nullable|string|max:150',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:150',
            'address'      => 'nullable|string|max:500',
        ]);

        Supplier::create([
            'name'         => $request->name,
            'company_name' => $request->company_name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'is_active'    => $request->has('is_active'),
        ]);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier added successfully.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name'         => 'required|string|max:150',
            'company_name' => 'nullable|string|max:150',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:150',
            'address'      => 'nullable|string|max:500',
        ]);

        $supplier->update([
            'name'         => $request->name,
            'company_name' => $request->company_name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'is_active'    => $request->has('is_active'),
        ]);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}

