<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of registered customers
     */
    public function index(Request $request)
    {
        $query = User::withCount('orders')->latest();

        // Search by Name, Email, Phone
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by Status (all, active, blocked)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_blocked', false);
            } elseif ($request->status === 'blocked') {
                $query->where('is_blocked', true);
            }
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_blocked', false)->count(),
            'blocked' => User::where('is_blocked', true)->count(),
            'total_points' => User::sum('reward_points'),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show customer profile and their orders
     */
    public function show($id)
    {
        $user = User::withCount('orders')->findOrFail($id);
        $orders = Order::where('user_id', $user->id)
            ->with(['items.product', 'orderStatus'])
            ->latest()
            ->paginate(10);

        $totalSpent = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        return view('admin.users.show', compact('user', 'orders', 'totalSpent'));
    }

    /**
     * Toggle Block/Unblock status of a user
     */
    public function toggleBlock(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = !$user->is_blocked;
        if ($user->is_blocked && $request->filled('reason')) {
            $user->blocked_reason = $request->reason;
        } elseif (!$user->is_blocked) {
            $user->blocked_reason = null;
        }
        $user->save();

        $statusText = $user->is_blocked ? 'ব্লক' : 'আনব্লক';
        return redirect()->back()->with('success', "ব্যবহারকারী '{$user->name}' সফলভাবে {$statusText} করা হয়েছে।");
    }

    /**
     * Delete user account
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "ব্যবহারকারী '{$name}' মুছে ফেলা হয়েছে।");
    }
}
