<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use Illuminate\Http\Request;

class AdminAddOnController extends Controller
{
    public function index()
    {
        $addons = AddOn::latest()->paginate(15);
        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        AddOn::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'price' => $request->price,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created successfully.');
    }

    public function edit($id)
    {
        $addon = AddOn::findOrFail($id);
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(Request $request, $id)
    {
        $addon = AddOn::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $addon->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'price' => $request->price,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated successfully.');
    }

    public function destroy($id)
    {
        $addon = AddOn::findOrFail($id);
        $addon->delete();
        return redirect()->route('admin.addons.index')->with('success', 'Add-on deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $addon = AddOn::findOrFail($id);
        $addon->update(['is_active' => !$addon->is_active]);
        return response()->json([
            'success' => true,
            'is_active' => $addon->is_active,
            'message' => $addon->is_active ? 'Add-on activated.' : 'Add-on deactivated.',
        ]);
    }
}