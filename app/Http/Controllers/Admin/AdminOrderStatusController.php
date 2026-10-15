<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminOrderStatusController extends Controller
{
    public function index()
    {
        $statuses = OrderStatus::orderBy('sort_order', 'asc')->get();

        // Calculate count of orders in each status
        $statusCounts = Order::select('order_status', \DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        return view('admin.order_statuses.index', compact('statuses', 'statusCounts'));
    }

    public function create()
    {
        return view('admin.order_statuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:50|unique:order_statuses,name',
            'color'      => 'nullable|string|max:20',
            'bg_color'   => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $baseSlug = Str::slug($request->name, '_');
        $slug = $baseSlug;
        $counter = 1;
        while (OrderStatus::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '_' . $counter++;
        }

        OrderStatus::create([
            'name'       => $request->name,
            'slug'       => $slug,
            'color'      => $request->color ?: '#0f172a',
            'bg_color'   => $request->bg_color ?: '#f1f5f9',
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => $request->has('is_active') ? true : true,
        ]);

        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status "' . $request->name . '" created successfully!');
    }

    public function edit($id)
    {
        $status = OrderStatus::findOrFail($id);
        return view('admin.order_statuses.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $status = OrderStatus::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:50|unique:order_statuses,name,' . $status->id,
            'color'      => 'nullable|string|max:20',
            'bg_color'   => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $status->name = $request->name;
        $status->color = $request->color ?: '#0f172a';
        $status->bg_color = $request->bg_color ?: '#f1f5f9';
        $status->sort_order = $request->input('sort_order', 0);
        $status->is_active = $request->has('is_active') ? true : false;
        $status->save();

        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status updated successfully!');
    }

    public function destroy($id)
    {
        $status = OrderStatus::findOrFail($id);

        $orderCount = Order::where('order_status', $status->slug)->orWhere('order_status', $status->name)->count();
        if ($orderCount > 0) {
            return redirect()->back()->with('error', "Cannot delete '{$status->name}' because {$orderCount} order(s) currently use this status.");
        }

        $status->delete();
        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $status = OrderStatus::findOrFail($id);
        $status->is_active = !$status->is_active;
        $status->save();

        return redirect()->back()->with('success', "Status '{$status->name}' " . ($status->is_active ? 'activated' : 'deactivated') . ".");
    }
}
