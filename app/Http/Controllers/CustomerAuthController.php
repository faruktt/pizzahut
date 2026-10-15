<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        $featuredProducts = Product::where('is_active', true)
            ->whereNotNull('image')
            ->orderBy('is_bestseller', 'desc')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        return view('auth.customer_login', compact('featuredProducts'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->remember)) {
            $user = Auth::guard('web')->user();

            if ($user->is_blocked) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'আপনার একাউন্টটি সাময়িকভাবে ব্লক করা হয়েছে। দয়া করে সাপোর্টে যোগাযোগ করুন।'
                ])->withInput();
            }

            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Welcome back, ' . $user->name);
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    public function showRegister()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        $featuredProducts = Product::where('is_active', true)
            ->whereNotNull('image')
            ->orderBy('is_bestseller', 'desc')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        return view('auth.customer_register', compact('featuredProducts'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('success', 'Account created successfully!');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    /**
     * Customer Dashboard
     */
    public function dashboard()
    {
        $user = Auth::guard('web')->user();
        $recentOrders = Order::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->whereIn('order_status', ['pending', 'preparing', 'ready'])->count(),
            'completed_orders' => Order::where('user_id', $user->id)->where('order_status', 'completed')->count(),
            'total_spent' => Order::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('customer.dashboard', compact('user', 'recentOrders', 'stats'));
    }

    /**
     * Customer Orders List
     */
    public function orders()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    /**
     * Customer Order Detail
     */
    public function orderDetail($id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('user_id', $user->id)
            ->with('items.addons')
            ->findOrFail($id);

        return view('customer.order_detail', compact('order'));
    }

    /**
     * Update Profile (Name, Email, Phone)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}