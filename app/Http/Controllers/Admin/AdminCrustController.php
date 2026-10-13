<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crust;
use Illuminate\Http\Request;

class AdminCrustController extends Controller
{
    public function index()
    {
        $crusts = Crust::orderBy('name')->get();
        return view('admin.crusts.index', compact('crusts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Crust::create([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.crusts.index')->with('success', 'Crust added.');
    }

    public function update(Request $request, $id)
    {
        $crust = Crust::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $crust->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.crusts.index')->with('success', 'Crust updated.');
    }

    public function destroy($id)
    {
        Crust::findOrFail($id)->delete();
        return redirect()->route('admin.crusts.index')->with('success', 'Crust deleted.');
    }
}
