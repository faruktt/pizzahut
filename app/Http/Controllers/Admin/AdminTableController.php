<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::with('activeOrder')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables,table_number',
            'capacity' => 'required|integer|min:1',
        ]);

        RestaurantTable::create([
            'table_number' => $request->table_number,
            'capacity' => $request->capacity,
            'status' => 'available',
        ]);

        return back()->with('success', 'Table added successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $table = RestaurantTable::findOrFail($id);
        $request->validate([
            'status' => 'required|in:available,occupied,reserved,billed',
        ]);

        $table->update(['status' => $request->status]);

        return back()->with('success', "Table {$table->table_number} status updated.");
    }

    public function destroy($id)
    {
        $table = RestaurantTable::findOrFail($id);
        $table->delete();
        return back()->with('success', 'Table removed.');
    }
}
