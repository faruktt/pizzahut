<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\RestaurantTable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todaySales = Order::whereDate('created_at', $today)
            ->whereIn('payment_status', ['paid', 'completed'])
            ->sum('total_amount');

        $totalSales = Order::sum('total_amount');
        $todayOrdersCount = Order::whereDate('created_at', $today)->count();
        $totalOrdersCount = Order::count();

        $activeTablesCount = RestaurantTable::where('status', 'occupied')->count();
        $totalTablesCount = RestaurantTable::count();

        $totalProducts = Product::count();
        $totalCustomers = User::count();

        $recentOrders = Order::with(['table', 'user', 'items'])
            ->latest()
            ->take(8)
            ->get();

        $tables = RestaurantTable::with('activeOrder')->get();

        return view('admin.dashboard', compact(
            'todaySales',
            'totalSales',
            'todayOrdersCount',
            'totalOrdersCount',
            'activeTablesCount',
            'totalTablesCount',
            'totalProducts',
            'totalCustomers',
            'recentOrders',
            'tables'
        ));
    }
}
