<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\CustomerDuePayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminReportController extends Controller
{
    public function cashStatement(Request $request)
    {
        $period = $request->input('period', 'daily'); // daily, monthly, yearly, custom
        $today = Carbon::today();

        $selectedDate = $request->input('date', $today->format('Y-m-d'));
        $selectedMonth = (int) $request->input('month', $today->month);
        $selectedYear = (int) $request->input('year', $today->year);
        $fromDate = $request->input('from_date', $today->copy()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', $today->format('Y-m-d'));

        $breakdown = [];
        $title = '';

        if ($period === 'daily') {
            $startDate = Carbon::parse($selectedDate)->startOfDay();
            $endDate = Carbon::parse($selectedDate)->endOfDay();
            $title = "Daily Cash Statement - " . $startDate->format('d F, Y');

            // Summary metrics for the specific day
            $salesOrders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
            $totalSalesGross = $salesOrders->sum('total_amount');
            $salesCashReceived = $salesOrders->sum('paid_amount');
            $dueGiven = $salesOrders->sum('due_amount');

            $dueCollected = CustomerDuePayment::whereDate('payment_date', $startDate->toDateString())->sum('amount');
            $purchasesPaid = Purchase::whereDate('purchase_date', $startDate->toDateString())->sum('paid_amount');
            $expensesPaid = Expense::whereDate('expense_date', $startDate->toDateString())->sum('amount');

            $totalCashIn = $salesCashReceived + $dueCollected;
            $totalCashOut = $purchasesPaid + $expensesPaid;
            $netCash = $totalCashIn - $totalCashOut;

            // Hourly / Activity log for daily
            $ordersList = Order::whereBetween('created_at', [$startDate, $endDate])
                ->latest()
                ->take(30)
                ->get()
                ->map(fn($o) => [
                    'time' => $o->created_at->format('h:i A'),
                    'type' => 'Sale',
                    'ref' => $o->order_number,
                    'desc' => $o->customer_name . " ({$o->order_type})",
                    'cash_in' => $o->paid_amount,
                    'cash_out' => 0,
                    'due' => $o->due_amount,
                ]);

            $duePaymentsList = CustomerDuePayment::whereDate('payment_date', $startDate->toDateString())
                ->get()
                ->map(fn($p) => [
                    'time' => $p->created_at ? $p->created_at->format('h:i A') : '—',
                    'type' => 'Due Collection',
                    'ref' => 'DUE-' . $p->id,
                    'desc' => "From {$p->customer_name}",
                    'cash_in' => $p->amount,
                    'cash_out' => 0,
                    'due' => 0,
                ]);

            $purchasesList = Purchase::whereDate('purchase_date', $startDate->toDateString())
                ->get()
                ->map(fn($p) => [
                    'time' => '—',
                    'type' => 'Purchase',
                    'ref' => $p->purchase_number,
                    'desc' => $p->supplier ? $p->supplier->name : 'Raw Materials',
                    'cash_in' => 0,
                    'cash_out' => $p->paid_amount,
                    'due' => $p->due_amount,
                ]);

            $expensesList = Expense::whereDate('expense_date', $startDate->toDateString())
                ->get()
                ->map(fn($e) => [
                    'time' => '—',
                    'type' => 'Expense',
                    'ref' => $e->expense_number,
                    'desc' => "{$e->category}: {$e->title}",
                    'cash_in' => 0,
                    'cash_out' => $e->amount,
                    'due' => 0,
                ]);

            $breakdown = $ordersList->concat($duePaymentsList)->concat($purchasesList)->concat($expensesList);

        } elseif ($period === 'monthly') {
            $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $title = "Monthly Cash Statement - " . $startDate->format('F Y');

            $salesOrders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
            $totalSalesGross = $salesOrders->sum('total_amount');
            $salesCashReceived = $salesOrders->sum('paid_amount');
            $dueGiven = $salesOrders->sum('due_amount');

            $dueCollected = CustomerDuePayment::whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('amount');
            $purchasesPaid = Purchase::whereBetween('purchase_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('paid_amount');
            $expensesPaid = Expense::whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('amount');

            $totalCashIn = $salesCashReceived + $dueCollected;
            $totalCashOut = $purchasesPaid + $expensesPaid;
            $netCash = $totalCashIn - $totalCashOut;

            // Generate daily rows for the month
            $daysInMonth = $startDate->daysInMonth;
            $breakdown = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $curDate = Carbon::createFromDate($selectedYear, $selectedMonth, $d);
                if ($curDate->isFuture()) break;

                $dayStart = $curDate->copy()->startOfDay();
                $dayEnd = $curDate->copy()->endOfDay();

                $daySales = Order::whereBetween('created_at', [$dayStart, $dayEnd])->get();
                $daySalesGross = $daySales->sum('total_amount');
                $dayCashInSales = $daySales->sum('paid_amount');
                $dayDueGiven = $daySales->sum('due_amount');

                $dayDueCollected = CustomerDuePayment::whereDate('payment_date', $curDate->toDateString())->sum('amount');
                $dayPurchases = Purchase::whereDate('purchase_date', $curDate->toDateString())->sum('paid_amount');
                $dayExpenses = Expense::whereDate('expense_date', $curDate->toDateString())->sum('amount');

                $dayIn = $dayCashInSales + $dayDueCollected;
                $dayOut = $dayPurchases + $dayExpenses;
                $dayNet = $dayIn - $dayOut;

                if ($daySalesGross > 0 || $dayIn > 0 || $dayOut > 0) {
                    $breakdown[] = [
                        'date' => $curDate->format('d M (D)'),
                        'sales_gross' => $daySalesGross,
                        'cash_sales' => $dayCashInSales,
                        'due_given' => $dayDueGiven,
                        'due_collected' => $dayDueCollected,
                        'purchases' => $dayPurchases,
                        'expenses' => $dayExpenses,
                        'total_in' => $dayIn,
                        'total_out' => $dayOut,
                        'net' => $dayNet,
                    ];
                }
            }

        } elseif ($period === 'yearly') {
            $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
            $endDate = $startDate->copy()->endOfYear();
            $title = "Yearly Cash Statement - " . $selectedYear;

            $salesOrders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
            $totalSalesGross = $salesOrders->sum('total_amount');
            $salesCashReceived = $salesOrders->sum('paid_amount');
            $dueGiven = $salesOrders->sum('due_amount');

            $dueCollected = CustomerDuePayment::whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('amount');
            $purchasesPaid = Purchase::whereBetween('purchase_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('paid_amount');
            $expensesPaid = Expense::whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('amount');

            $totalCashIn = $salesCashReceived + $dueCollected;
            $totalCashOut = $purchasesPaid + $expensesPaid;
            $netCash = $totalCashIn - $totalCashOut;

            // Generate month-by-month rows (1 to 12)
            $breakdown = [];
            for ($m = 1; $m <= 12; $m++) {
                $mStart = Carbon::createFromDate($selectedYear, $m, 1)->startOfMonth();
                $mEnd = $mStart->copy()->endOfMonth();

                $mSales = Order::whereBetween('created_at', [$mStart, $mEnd])->get();
                $mSalesGross = $mSales->sum('total_amount');
                $mCashSales = $mSales->sum('paid_amount');
                $mDueGiven = $mSales->sum('due_amount');

                $mDueCollected = CustomerDuePayment::whereBetween('payment_date', [$mStart->toDateString(), $mEnd->toDateString()])->sum('amount');
                $mPurchases = Purchase::whereBetween('purchase_date', [$mStart->toDateString(), $mEnd->toDateString()])->sum('paid_amount');
                $mExpenses = Expense::whereBetween('expense_date', [$mStart->toDateString(), $mEnd->toDateString()])->sum('amount');

                $mIn = $mCashSales + $mDueCollected;
                $mOut = $mPurchases + $mExpenses;
                $mNet = $mIn - $mOut;

                $breakdown[] = [
                    'month_name' => $mStart->format('F'),
                    'sales_gross' => $mSalesGross,
                    'cash_sales' => $mCashSales,
                    'due_given' => $mDueGiven,
                    'due_collected' => $mDueCollected,
                    'purchases' => $mPurchases,
                    'expenses' => $mExpenses,
                    'total_in' => $mIn,
                    'total_out' => $mOut,
                    'net' => $mNet,
                ];
            }
        } else {
            // Custom Range
            $startDate = Carbon::parse($fromDate)->startOfDay();
            $endDate = Carbon::parse($toDate)->endOfDay();
            $title = "Cash Statement from " . $startDate->format('d M, Y') . " to " . $endDate->format('d M, Y');

            $salesOrders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
            $totalSalesGross = $salesOrders->sum('total_amount');
            $salesCashReceived = $salesOrders->sum('paid_amount');
            $dueGiven = $salesOrders->sum('due_amount');

            $dueCollected = CustomerDuePayment::whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('amount');
            $purchasesPaid = Purchase::whereBetween('purchase_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('paid_amount');
            $expensesPaid = Expense::whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])->sum('amount');

            $totalCashIn = $salesCashReceived + $dueCollected;
            $totalCashOut = $purchasesPaid + $expensesPaid;
            $netCash = $totalCashIn - $totalCashOut;
        }

        // All-time Total Customer Due outstanding
        $totalCustomerDueAllTime = Order::where('due_amount', '>', 0)->sum('due_amount');

        return view('admin.reports.cash_statement', compact(
            'period',
            'title',
            'selectedDate',
            'selectedMonth',
            'selectedYear',
            'fromDate',
            'toDate',
            'totalSalesGross',
            'salesCashReceived',
            'dueGiven',
            'dueCollected',
            'purchasesPaid',
            'expensesPaid',
            'totalCashIn',
            'totalCashOut',
            'netCash',
            'totalCustomerDueAllTime',
            'breakdown'
        ));
    }
}

