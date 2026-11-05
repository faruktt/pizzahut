<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminExpenseController extends Controller
{
    public const CATEGORIES = [
        'Rent',
        'Electricity & Utility',
        'Gas',
        'Staff Salary',
        'Kitchen Supplies',
        'Maintenance & Repair',
        'Cleaning & Sanitation',
        'Marketing & Promotion',
        'Transport & Delivery',
        'Internet & Software',
        'Packaging Material',
        'Waste / Spoilage',
        'Other',
    ];

    public function index(Request $request)
    {
        $query = Expense::latest('expense_date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('expense_number', 'like', "%{$search}%");
            });
        }

        $totalAll = Expense::sum('amount');
        $totalThisMonth = Expense::whereYear('expense_date', date('Y'))
            ->whereMonth('expense_date', date('m'))
            ->sum('amount');
        $totalToday = Expense::whereDate('expense_date', date('Y-m-d'))->sum('amount');

        $expenses = $query->paginate(15)->withQueryString();
        $categories = self::CATEGORIES;

        return view('admin.expenses.index', compact('expenses', 'categories', 'totalAll', 'totalThisMonth', 'totalToday'));
    }

    public function create()
    {
        $categories = self::CATEGORIES;
        return view('admin.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'       => 'required|string|max:100',
            'title'          => 'required|string|max:200',
            'amount'         => 'required|numeric|min:0.01',
            'expense_date'   => 'required|date',
            'payment_method' => 'required|string|max:50',
            'note'           => 'nullable|string|max:1000',
        ]);

        $expenseNumber = 'EXP-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        Expense::create([
            'expense_number' => $expenseNumber,
            'category'       => $request->category,
            'title'          => $request->title,
            'amount'         => $request->amount,
            'expense_date'   => $request->expense_date,
            'payment_method' => $request->payment_method,
            'note'           => $request->note,
        ]);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $categories = self::CATEGORIES;
        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $request->validate([
            'category'       => 'required|string|max:100',
            'title'          => 'required|string|max:200',
            'amount'         => 'required|numeric|min:0.01',
            'expense_date'   => 'required|date',
            'payment_method' => 'required|string|max:50',
            'note'           => 'nullable|string|max:1000',
        ]);

        $expense->update([
            'category'       => $request->category,
            'title'          => $request->title,
            'amount'         => $request->amount,
            'expense_date'   => $request->expense_date,
            'payment_method' => $request->payment_method,
            'note'           => $request->note,
        ]);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted successfully.');
    }
}

