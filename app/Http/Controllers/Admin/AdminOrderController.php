<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\RestaurantTable;
use App\Models\Setting;
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

        $perPage = in_array((int)$request->input('per_page'), [10, 15, 25, 50, 100]) ? (int)$request->input('per_page') : 15;
        $orders = $query->paginate($perPage)->withQueryString();
        $orderStatuses = OrderStatus::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.orders.index', compact('orders', 'orderStatuses', 'perPage'));
    }

    public function show($id)
    {
        $order = Order::with(['table', 'user', 'items.addons'])->findOrFail($id);
        $orderStatuses = OrderStatus::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.orders.show', compact('order', 'orderStatuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'order_status' => 'required|string|max:50',
            'payment_status' => 'nullable|in:pending,paid,partial,due',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $order->order_status = $request->order_status;

        // Update Payment Method
        if ($request->filled('payment_method')) {
            $order->payment_method = strtolower($request->payment_method);
        }

        // Payment status & amount logic
        if ($request->filled('payment_status')) {
            $newStatus = $request->payment_status;
            if ($newStatus === 'paid') {
                $order->payment_status = 'paid';
                $order->paid_amount = $order->total_amount;
                $order->due_amount = 0;
            } elseif ($newStatus === 'due') {
                $order->payment_status = 'due';
                $order->paid_amount = 0;
                $order->due_amount = $order->total_amount;
            } elseif ($newStatus === 'pending') {
                $order->payment_status = 'pending';
                if ($request->filled('paid_amount') && (float)$request->paid_amount > 0) {
                    $order->paid_amount = (float)$request->paid_amount;
                    $order->due_amount = max(0, $order->total_amount - $order->paid_amount);
                } else {
                    $order->paid_amount = 0;
                    $order->due_amount = $order->total_amount;
                }
            } elseif ($newStatus === 'partial') {
                $paid = (float)$request->input('paid_amount', 0);
                if ($paid >= $order->total_amount) {
                    $order->payment_status = 'paid';
                    $order->paid_amount = $order->total_amount;
                    $order->due_amount = 0;
                } elseif ($paid <= 0) {
                    $order->payment_status = 'due';
                    $order->paid_amount = 0;
                    $order->due_amount = $order->total_amount;
                } else {
                    $order->payment_status = 'partial';
                    $order->paid_amount = $paid;
                    $order->due_amount = max(0, $order->total_amount - $paid);
                }
            }
        } elseif ($request->filled('paid_amount')) {
            $paid = (float)$request->paid_amount;
            if ($paid >= $order->total_amount) {
                $order->payment_status = 'paid';
                $order->paid_amount = $order->total_amount;
                $order->due_amount = 0;
            } elseif ($paid <= 0) {
                $order->payment_status = 'due';
                $order->paid_amount = 0;
                $order->due_amount = $order->total_amount;
            } else {
                $order->payment_status = 'partial';
                $order->paid_amount = $paid;
                $order->due_amount = max(0, $order->total_amount - $paid);
            }
        }

        // If completed or cancelled and was dine-in, free table
        if (in_array($request->order_status, ['completed', 'cancelled']) && $order->restaurant_table_id) {
            RestaurantTable::where('id', $order->restaurant_table_id)->update(['status' => 'available']);
        }

        $order->save();

        $statusObj = OrderStatus::findBySlugOrName($request->order_status);
        $statusName = $statusObj ? $statusObj->name : ucfirst(str_replace('_', ' ', $order->order_status));

        return back()->with('success', "Order #{$order->order_number} details updated successfully.");
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $orderNumber = $order->order_number;

        // If dine-in order with a table, release table if no other active order is on that table
        if ($order->order_type === 'dine_in' && $order->restaurant_table_id) {
            $otherActive = Order::where('restaurant_table_id', $order->restaurant_table_id)
                ->where('id', '!=', $order->id)
                ->whereNotIn('order_status', ['completed', 'cancelled'])
                ->exists();
            if (!$otherActive) {
                RestaurantTable::where('id', $order->restaurant_table_id)->update(['status' => 'available']);
            }
        }

        // Delete items and their addons
        foreach ($order->items as $item) {
            $item->addons()->delete();
            $item->delete();
        }

        // Delete associated due payments if any
        if (method_exists($order, 'duePayments')) {
            $order->duePayments()->delete();
        }

        $order->delete();

        if (url()->previous() === route('admin.orders.show', $id)) {
            return redirect()->route('admin.orders.index')->with('success', "Order #{$orderNumber} deleted successfully.");
        }

        return back()->with('success', "Order #{$orderNumber} deleted successfully.");
    }

    /**
     * Create order directly by admin/cashier without requiring customer account.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->with(['sizes', 'crusts'])->get();
        $tables = RestaurantTable::where('status', 'available')->get();
        $addons = AddOn::where('is_active', true)->get();
        return view('admin.orders.create', compact('products', 'tables', 'addons'));
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
            $subtotal += ((float)$item['unit_price'] * (int)$item['quantity']);
        }

        $baseDeliveryCharge = Setting::getDeliveryCharge();
        $freeDeliveryMin = (float) Setting::getValue('free_delivery_min', 0);
        $deliveryCharge = ($request->order_type === 'delivery') ? (($freeDeliveryMin > 0 && $subtotal >= $freeDeliveryMin) ? 0 : $baseDeliveryCharge) : 0;
        $vatTax = 0;
        $total = $subtotal + $deliveryCharge;

        $initialStatus = OrderStatus::where('is_default', true)->value('slug') ?? 'preparing';

        $paymentStatus = $request->payment_status ?? 'paid';
        if ($paymentStatus === 'paid') {
            $paidAmount = $total;
            $dueAmount = 0;
        } elseif ($paymentStatus === 'due') {
            $paidAmount = 0;
            $dueAmount = $total;
        } else {
            $paidAmount = min($total, max(0, (float)$request->input('paid_amount', 0)));
            $dueAmount = max(0, $total - $paidAmount);
            if ($dueAmount == 0) $paymentStatus = 'paid';
        }

        // Auto associate user or create customer if phone provided
        $customerPhone = $request->customer_phone ?: null;
        $customerName = $request->customer_name ?: 'Walk-in Customer';
        $userId = null;
        if ($customerPhone && !in_array(strtolower($customerPhone), ['walk-in', 'n/a', 'none', ''])) {
            $user = User::where('phone', $customerPhone)->first();
            if (!$user && $customerName && !in_array(strtolower($customerName), ['walk-in', 'walk-in customer'])) {
                $user = User::create([
                    'name' => $customerName,
                    'phone' => $customerPhone,
                ]);
            }
            if ($user) {
                $userId = $user->id;
                $customerName = $user->name ?: $customerName;
            }
        }

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(6)),
            'admin_id' => Auth::guard('admin')->id(),
            'user_id' => $userId,
            'order_type' => $request->order_type,
            'restaurant_table_id' => $request->restaurant_table_id,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone ?: 'Walk-in',
            'customer_address' => $request->customer_address,
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'vat_tax' => $vatTax,
            'total_amount' => $total,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'payment_method' => $request->payment_method ?? 'cash',
            'payment_status' => $paymentStatus,
            'order_status' => $initialStatus,
            'special_instructions' => $request->special_instructions,
        ]);

        if ($request->order_type === 'dine_in' && $request->restaurant_table_id) {
            RestaurantTable::where('id', $request->restaurant_table_id)->update(['status' => 'occupied']);
        }

        foreach ($request->items as $item) {
            $product = isset($item['product_id']) ? Product::find($item['product_id']) : null;
            $prodName = $product ? $product->name : ($item['product_name'] ?? 'Item');

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $prodName,
                'size_name' => !empty($item['size_name']) ? $item['size_name'] : null,
                'crust_name' => !empty($item['crust_name']) ? $item['crust_name'] : null,
                'unit_price' => (float)$item['unit_price'],
                'quantity' => (int)$item['quantity'],
                'total_price' => (float)$item['unit_price'] * (int)$item['quantity'],
            ]);

            // Save Addons if selected
            if (!empty($item['addons']) && is_array($item['addons'])) {
                foreach ($item['addons'] as $addonId) {
                    $addon = AddOn::find($addonId);
                    if ($addon) {
                        OrderItemAddon::create([
                            'order_item_id' => $orderItem->id,
                            'add_on_name' => $addon->name,
                            'price' => $addon->price,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order created successfully!');
    }
}
