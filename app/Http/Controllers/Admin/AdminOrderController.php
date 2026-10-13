<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Product;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['table', 'user', 'items.addons'])->latest();

        if ($request->filled('type')) {
            $query->where('order_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['table', 'user', 'items.addons'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'order_status' => 'required|in:pending,preparing,ready,served,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid',
        ]);

        $order->order_status = $request->order_status;
        if ($request->filled('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        // If completed or cancelled and was dine-in, free table
        if (in_array($request->order_status, ['completed', 'cancelled']) && $order->restaurant_table_id) {
            RestaurantTable::where('id', $order->restaurant_table_id)->update(['status' => 'available']);
        }

        $order->save();

        return back()->with('success', "Order #{$order->order_number} status updated to {$order->order_status}.");
    }

    /**
     * Create order directly by admin/cashier without requiring customer account.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->with(['sizes', 'crusts'])->get();
        $tables = RestaurantTable::where('status', 'available')->get();
        return view('admin.orders.create', compact('products', 'tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:delivery,takeaway,dine_in',
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += ($item['unit_price'] * $item['quantity']);
        }

        $deliveryCharge = ($request->order_type === 'delivery') ? 70 : 0;
        $vatTax = round($subtotal * 0.05, 2);
        $total = $subtotal + $deliveryCharge + $vatTax;

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(6)),
            'admin_id' => Auth::guard('admin')->id(),
            'order_type' => $request->order_type,
            'restaurant_table_id' => $request->restaurant_table_id,
            'customer_name' => $request->customer_name ?: 'Walk-in Customer',
            'customer_phone' => $request->customer_phone ?: 'N/A',
            'customer_address' => $request->customer_address,
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'vat_tax' => $vatTax,
            'total_amount' => $total,
            'payment_method' => $request->payment_method ?? 'cash',
            'payment_status' => $request->payment_status ?? 'paid',
            'order_status' => 'preparing',
            'special_instructions' => $request->special_instructions,
        ]);

        if ($request->order_type === 'dine_in' && $request->restaurant_table_id) {
            RestaurantTable::where('id', $request->restaurant_table_id)->update(['status' => 'occupied']);
        }

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['product_name'],
                'size_name' => $item['size_name'] ?? 'Regular',
                'crust_name' => $item['crust_name'] ?? 'Pan Crust',
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['unit_price'] * $item['quantity'],
            ]);
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order created successfully without customer registration!');
    }
}
