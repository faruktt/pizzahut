<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CustomerDuePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminDueCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        // Group orders with outstanding due by customer_phone (or customer_name if phone is null/empty)
        $query = Order::where('due_amount', '>', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // Summary Statistics
        $totalCustomerDue = Order::where('due_amount', '>', 0)->sum('due_amount');
        $totalDueCollected = CustomerDuePayment::sum('amount');
        $dueOrdersCount = Order::where('due_amount', '>', 0)->count();

        // Customer due grouped list
        $customers = $query->select(
                DB::raw("COALESCE(NULLIF(customer_phone, ''), customer_name) as customer_key"),
                DB::raw("MAX(customer_name) as name"),
                DB::raw("MAX(customer_phone) as phone"),
                DB::raw("MAX(user_id) as user_id"),
                DB::raw("COUNT(id) as due_orders_count"),
                DB::raw("SUM(total_amount) as total_bill"),
                DB::raw("SUM(paid_amount) as total_paid"),
                DB::raw("SUM(due_amount) as total_due"),
                DB::raw("MAX(created_at) as latest_order_date")
            )
            ->groupBy('customer_key')
            ->orderByDesc('total_due')
            ->paginate(15)
            ->withQueryString();

        $totalDueCustomers = $customers->total();

        return view('admin.due_customers.index', compact(
            'customers',
            'totalCustomerDue',
            'totalDueCollected',
            'dueOrdersCount',
            'totalDueCustomers'
        ));
    }

    public function show($customerKey)
    {
        $orders = Order::with('items')
            ->where(function ($q) use ($customerKey) {
                $q->where('customer_phone', $customerKey)
                  ->orWhere('customer_name', $customerKey);
            })
            ->latest()
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->route('admin.due-customers.index')->with('error', 'Customer not found.');
        }

        $customerName = $orders->first()->customer_name;
        $customerPhone = $orders->first()->customer_phone;
        $totalDue = $orders->where('due_amount', '>', 0)->sum('due_amount');
        $totalPaid = $orders->sum('paid_amount');
        $totalBill = $orders->sum('total_amount');

        $paymentHistory = CustomerDuePayment::where(function ($q) use ($customerKey, $customerPhone) {
                $q->where('customer_phone', $customerKey)
                  ->orWhere('customer_name', $customerKey);
                if ($customerPhone) {
                    $q->orWhere('customer_phone', $customerPhone);
                }
            })
            ->latest()
            ->get();

        return view('admin.due_customers.show', compact(
            'orders',
            'customerKey',
            'customerName',
            'customerPhone',
            'totalDue',
            'totalPaid',
            'totalBill',
            'paymentHistory'
        ));
    }

    public function collectPayment(Request $request)
    {
        $request->validate([
            'customer_key'   => 'required|string',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_date'   => 'required|date',
            'note'           => 'nullable|string|max:500',
        ]);

        $customerKey = $request->customer_key;
        $amountToCollect = (float) $request->amount;

        // Find customer's pending due orders (Oldest first)
        $dueOrders = Order::where(function ($q) use ($customerKey) {
                $q->where('customer_phone', $customerKey)
                  ->orWhere('customer_name', $customerKey);
            })
            ->where('due_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($dueOrders->isEmpty()) {
            return back()->with('error', 'This customer has no outstanding due.');
        }

        DB::beginTransaction();
        try {
            $remainingPayment = $amountToCollect;
            $customerName = $dueOrders->first()->customer_name;
            $customerPhone = $dueOrders->first()->customer_phone;
            $userId = $dueOrders->first()->user_id;
            $firstOrderId = $dueOrders->first()->id;

            foreach ($dueOrders as $order) {
                if ($remainingPayment <= 0) {
                    break;
                }

                $orderDue = (float) $order->due_amount;

                if ($remainingPayment >= $orderDue) {
                    // Fully pays this order
                    $order->paid_amount += $orderDue;
                    $order->due_amount = 0;
                    $order->payment_status = 'paid';
                    $remainingPayment -= $orderDue;
                } else {
                    // Partially pays this order
                    $order->paid_amount += $remainingPayment;
                    $order->due_amount -= $remainingPayment;
                    $order->payment_status = 'partial';
                    $remainingPayment = 0;
                }

                $order->save();
            }

            // Record into customer_due_payments
            CustomerDuePayment::create([
                'order_id'       => $firstOrderId,
                'user_id'        => $userId,
                'customer_name'  => $customerName,
                'customer_phone' => $customerPhone,
                'amount'         => $amountToCollect,
                'payment_method' => $request->payment_method,
                'payment_date'   => $request->payment_date,
                'note'           => $request->note,
                'admin_id'       => Auth::guard('admin')->id(),
            ]);

            DB::commit();
            return back()->with('success', "Payment of ৳" . number_format($amountToCollect, 2) . " received successfully from {$customerName}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to collect payment: ' . $e->getMessage());
        }
    }
}

