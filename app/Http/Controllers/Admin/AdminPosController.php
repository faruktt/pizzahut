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

        return view('admin.pos.index', compact('tables', 'categories', 'products', 'addons'));
    }

    /**
     * Get active order details for a specific table (AJAX).
     */
    public function getTableOrder($tableId)
    {
        $table = RestaurantTable::findOrFail($tableId);
        $order = Order::with(['items.addons'])
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
            $order = Order::create([
                'order_number' => 'POS-' . strtoupper(Str::random(6)),
                'admin_id' => Auth::guard('admin')->id(),
                'order_type' => 'dine_in',
                'restaurant_table_id' => $tableId,
                'customer_name' => "Dine-in ({$table->table_number})",
                'customer_phone' => 'Walk-in',
                'subtotal' => 0,
                'delivery_charge' => 0,
                'vat_tax' => 0,
                'total_amount' => 0,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'order_status' => 'preparing',
            ]);
            $table->update(['status' => 'occupied']);
        }

        // Calculate pricing
        $sizeName = $request->size_name ?? 'Standard';
        $unitPrice = (float) ($request->unit_price ?? $product->base_price);
        $crustName = $request->crust_name ?? 'Pan Crust';
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

        $order->load(['items.addons']);

        return response()->json([
            'success' => true,
            'message' => "Added {$product->name} to {$table->table_number}",
            'table' => $table,
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
        $order->load(['items.addons']);

        return response()->json([
            'success' => true,
            'message' => 'Item removed.',
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

        $order->update([
            'payment_method' => $request->payment_method ?? 'cash',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        $table->update(['status' => 'available']);

        return response()->json([
            'success' => true,
            'message' => "Payment completed! {$table->table_number} is now Available.",
            'order' => $order,
        ]);
    }

    /**
     * Helper to recompute order subtotal, vat, total.
     */
    private function recalculateOrder(Order $order)
    {
        $subtotal = $order->items()->sum('total_price');
        $vat = round($subtotal * 0.05, 2);
        $total = $subtotal + $vat;

        $order->update([
            'subtotal' => $subtotal,
            'vat_tax' => $vat,
            'total_amount' => $total,
        ]);
    }
}
