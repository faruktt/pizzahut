<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class AdminSizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('name')->get();
        return view('admin.sizes.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Size::create([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sizes.index')->with('success', 'Size added.');
    }

    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $size->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated.');
    }

    public function destroy($id)
    {
        Size::findOrFail($id)->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted.');
    }
}
