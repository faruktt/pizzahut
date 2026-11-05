<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductCrust;
use App\Models\AddOn;
use App\Models\RestaurantTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminPosController extends Controller
{
    /**
     * Show POS Interface with Table Management & Menu Grid.
     */
    public function index()
    {
        $tables = RestaurantTable::with(['activeOrder.items.addons'])->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $products = Product::where('is_active', true)->with(['sizes', 'crusts'])->get();
        $addons = AddOn::where('is_active', true)->get();

        $restaurantName = Setting::getValue('site_name', 'Italian Pizza Hut');
        $restaurantAddress = Setting::getValue('site_address', 'House 14, Road 11, Block D, Gulshan-2, Dhaka');
        $restaurantPhone = Setting::getValue('site_phone', '+880 1711-223344');

        return view('admin.pos.index', compact('tables', 'categories', 'products', 'addons', 'restaurantName', 'restaurantAddress', 'restaurantPhone'));
    }

    /**
     * Get active order details for a specific table (AJAX).
     */
    public function getTableOrder($tableId)
    {
        $table = RestaurantTable::findOrFail($tableId);
        $order = Order::with(['items.addons', 'table', 'user'])
            ->where('restaurant_table_id', $tableId)
            ->whereIn('order_status', ['pending', 'preparing', 'ready', 'served'])
            ->latest()
            ->first();

        return response()->json([
            'table' => $table,
            'order' => $order,
        ]);
    }

    /**
     * Add item to table running order (Creates order if table is new).
     */
    public function addItemToTable(Request $request, $tableId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $table = RestaurantTable::findOrFail($tableId);
        $product = Product::findOrFail($request->product_id);
        $quantity = (int) ($request->quantity ?? 1);

        // Find or create active order for table
        $order = Order::where('restaurant_table_id', $tableId)
            ->whereIn('order_status', ['pending', 'preparing', 'ready', 'served'])
            ->latest()
            ->first();

        if (!$order) {
            $customerName = $request->filled('customer_name') ? $request->customer_name : "Dine-in ({$table->table_number})";
            $customerPhone = $request->filled('customer_phone') ? $request->customer_phone : 'Walk-in';
            $userId = $request->user_id ?? null;

            $order = Order::create([
                'order_number' => 'POS-' . strtoupper(Str::random(6)),
                'admin_id' => Auth::guard('admin')->id(),
                'user_id' => $userId,
                'order_type' => 'dine_in',
                'restaurant_table_id' => $tableId,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'subtotal' => 0,
                'delivery_charge' => 0,
                'vat_tax' => 0,
                'total_amount' => 0,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'order_status' => 'preparing',
            ]);
            $table->update(['status' => 'occupied']);
        } elseif ($request->filled('user_id') || ($request->filled('customer_phone') && $order->customer_phone === 'Walk-in')) {
            $order->update([
                'user_id' => $request->user_id ?? $order->user_id,
                'customer_name' => $request->customer_name ?? $order->customer_name,
                'customer_phone' => $request->customer_phone ?? $order->customer_phone,
            ]);
        }

        // Calculate pricing
        $hasSizes = $product->sizes()->exists();
        $hasCrusts = $product->crusts()->exists();

        $sizeName = null;
        if ($hasSizes && !empty($request->size_name) && strtolower($request->size_name) !== 'standard') {
            $sizeName = $request->size_name;
        }

        $crustName = null;
        if ($hasCrusts && !empty($request->crust_name) && strtolower($request->crust_name) !== 'standard') {
            $crustName = $request->crust_name;
        }

        $unitPrice = (float) ($request->unit_price ?? $product->base_price);
        $crustExtra = (float) ($request->crust_extra ?? 0);

        $addonsExtra = 0;
        $addonsData = [];
        if ($request->has('addons') && is_array($request->addons)) {
            foreach ($request->addons as $addonId) {
                $addon = AddOn::find($addonId);
                if ($addon) {
                    $addonsData[] = $addon;
                    $addonsExtra += (float) $addon->price;
                }
            }
        }

        $singlePrice = $unitPrice + $crustExtra + $addonsExtra;
        $totalPrice = $singlePrice * $quantity;

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size_name' => $sizeName,
            'crust_name' => $crustName,
            'unit_price' => $singlePrice,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'special_instructions' => $request->special_instructions,
        ]);

        foreach ($addonsData as $ad) {
            OrderItemAddon::create([
                'order_item_id' => $orderItem->id,
                'add_on_name' => $ad->name,
                'price' => $ad->price,
            ]);
        }

        // Recalculate order total
        $this->recalculateOrder($order);

        $order->load(['items.addons', 'table', 'user']);

        return response()->json([
            'success' => true,
            'message' => "Added {$product->name} to {$table->table_number}",
            'table' => $table,
            'order' => $order,
        ]);
    }

    /**
     * Update quantity of an item on table order.
     */
    public function updateItemQuantity(Request $request, $itemId)
    {
        $item = OrderItem::findOrFail($itemId);
        $order = $item->order;
        $tableId = $order->restaurant_table_id;

        $action = $request->input('action'); // increase, decrease, or set
        if ($action === 'increase') {
            $item->quantity += 1;
        } elseif ($action === 'decrease') {
            $item->quantity -= 1;
        } elseif ($request->filled('quantity')) {
            $item->quantity = max(0, (int) $request->quantity);
        }

        if ($item->quantity <= 0) {
            $item->delete();
        } else {
            $item->total_price = $item->unit_price * $item->quantity;
            $item->save();
        }

        // Check if order has any items left
        if ($order->items()->count() === 0) {
            $order->delete();
            if ($tableId) {
                RestaurantTable::where('id', $tableId)->update(['status' => 'available']);
            }
            return response()->json([
                'success' => true,
                'message' => 'Order cleared because all items were removed.',
                'order' => null,
            ]);
        }

        $this->recalculateOrder($order);
        $order->load(['items.addons', 'table', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated.',
            'order' => $order,
        ]);
    }

    /**
     * Remove or adjust item on table order.
     */
    public function removeItemFromTable($itemId)
    {
        $item = OrderItem::findOrFail($itemId);
        $order = $item->order;
        $tableId = $order->restaurant_table_id;

        $item->delete();

        // Check if order has any items left
        if ($order->items()->count() === 0) {
            $order->delete();
            if ($tableId) {
                RestaurantTable::where('id', $tableId)->update(['status' => 'available']);
            }
            return response()->json([
                'success' => true,
                'message' => 'Order cleared because all items were removed.',
                'order' => null,
            ]);
        }

        $this->recalculateOrder($order);
        $order->load(['items.addons', 'table', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Item removed.',
            'order' => $order,
        ]);
    }

    /**
     * Search customers by phone or name for POS selection.
     */
    public function searchCustomers(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (empty($q)) {
            return response()->json([]);
        }

        $users = User::where(function ($query) use ($q) {
                $query->where('phone', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get();

        $results = [];
        foreach ($users as $user) {
            $dueSum = Order::where(function ($oq) use ($user) {
                    $oq->where('user_id', $user->id);
                    if ($user->phone) {
                        $oq->orWhere('customer_phone', $user->phone);
                    }
                })
                ->where('due_amount', '>', 0)
                ->sum('due_amount');

            $results[] = [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone ?: '',
                'due_amount' => (float) $dueSum,
            ];
        }

        return response()->json($results);
    }

    /**
     * Quick register and select a customer from POS.
     */
    public function quickAddCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ]);

        $phone = trim($request->phone);
        $name = trim($request->name);

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            $user = User::create([
                'name' => $name,
                'phone' => $phone,
            ]);
        } else {
            if (!empty($name) && ($user->name !== $name)) {
                $user->update(['name' => $name]);
            }
        }

        $dueSum = Order::where(function ($oq) use ($user) {
                $oq->where('user_id', $user->id);
                if ($user->phone) {
                    $oq->orWhere('customer_phone', $user->phone);
                }
            })
            ->where('due_amount', '>', 0)
            ->sum('due_amount');

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'due_amount' => (float) $dueSum,
            ],
            'message' => 'Customer registered and selected successfully.'
        ]);
    }

    /**
     * Update customer linked to active table order.
     */
    public function updateTableCustomer(Request $request, $tableId)
    {
        $order = Order::where('restaurant_table_id', $tableId)
            ->whereIn('order_status', ['pending', 'preparing', 'ready', 'served'])
            ->latest()
            ->first();

        if ($order) {
            $order->update([
                'user_id' => $request->user_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Table customer updated.'
        ]);
    }

    /**
     * Update discount on active table order.
     */
    public function updateTableDiscount(Request $request, $tableId)
    {
        $order = Order::where('restaurant_table_id', $tableId)
            ->whereIn('order_status', ['pending', 'preparing', 'ready', 'served'])
            ->latest()
            ->first();

        if ($order) {
            $discount = max(0, (float) $request->input('discount', 0));
            $order->discount = $discount;
            $this->recalculateOrder($order);
            $order->load(['items.addons', 'table', 'user']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Discount updated.',
            'order' => $order,
        ]);
    }

    /**
     * Complete and bill table order.
     */
    public function completeTableOrder(Request $request, $tableId)
    {
        $table = RestaurantTable::findOrFail($tableId);
        $order = Order::where('restaurant_table_id', $tableId)
            ->whereIn('order_status', ['pending', 'preparing', 'ready', 'served'])
            ->latest()
            ->firstOrFail();

        // Apply discount if provided
        $discount = (float) ($request->has('discount') ? $request->input('discount') : ($order->discount ?? 0));
        $discount = max(0, $discount);
        $subtotal = (float) $order->items()->sum('total_price');
        $totalAmount = max(0, $subtotal - $discount);

        $paymentStatus = $request->input('payment_status', 'paid'); // paid, partial, due
        $paymentMethod = $request->input('payment_method', 'cash');

        if ($paymentStatus === 'paid') {
            $paidAmount = $totalAmount;
            $dueAmount = 0;
        } elseif ($paymentStatus === 'due') {
            $paidAmount = 0;
            $dueAmount = $totalAmount;
        } else {
            // Partial
            $paidAmount = min($totalAmount, max(0, (float) $request->input('paid_amount', 0)));
            $dueAmount = max(0, $totalAmount - $paidAmount);
            if ($dueAmount == 0) {
                $paymentStatus = 'paid';
            }
        }

        $customerName = $request->filled('customer_name') ? $request->customer_name : $order->customer_name;
        $customerPhone = $request->filled('customer_phone') ? $request->customer_phone : $order->customer_phone;

        $userId = $request->user_id ?? $order->user_id;

        // Auto find or create customer if phone is provided and not generic
        if ($customerPhone && !in_array(strtolower($customerPhone), ['walk-in', 'n/a', 'none', ''])) {
            $user = User::where('phone', $customerPhone)->first();
            if (!$user && $customerName && !in_array(strtolower($customerName), ['walk-in', 'walk-in customer', 'dine-in', ''])) {
                $user = User::create([
                    'name' => $customerName,
                    'phone' => $customerPhone,
                ]);
            }
            if ($user) {
                $userId = $user->id;
                if (empty($customerName) || Str::startsWith($customerName, 'Dine-in (')) {
                    $customerName = $user->name;
                }
            }
        }

        $order->update([
            'user_id'        => $userId,
            'customer_name'  => $customerName,
            'customer_phone' => $customerPhone,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'total_amount'   => $totalAmount,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'paid_amount'    => $paidAmount,
            'due_amount'     => $dueAmount,
            'order_status'   => 'completed',
        ]);

        $table->update(['status' => 'available']);

        $order->load(['items.addons', 'table', 'user']);

        return response()->json([
            'success' => true,
            'message' => "Order completed! Paid: ৳{$paidAmount}, Due: ৳{$dueAmount}. Table is now Available.",
            'order' => $order,
        ]);
    }

    /**
     * Helper to recompute order subtotal, vat, total.
     */
    private function recalculateOrder(Order $order)
    {
        $subtotal = $order->items()->sum('total_price');
        $vat = 0; // VAT 5% removed as requested
        $discount = max(0, (float) ($order->discount ?? 0));
        $total = max(0, $subtotal - $discount);

        $due = max(0, $total - (float)$order->paid_amount);

        $order->update([
            'subtotal' => $subtotal,
            'vat_tax' => $vat,
            'discount' => $discount,
            'total_amount' => $total,
            'due_amount' => ($order->payment_status === 'paid' && $order->paid_amount >= $total) ? 0 : $due,
        ]);
    }
}
