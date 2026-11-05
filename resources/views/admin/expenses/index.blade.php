@extends('layouts.admin')
@section('title', 'Expenses | Italian Pizza Hut Admin')
@section('page-title', 'Expense Tracking')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin-bottom:4px;">Expenses Management</h2>
        <p style="font-size:0.8rem;color:#64748b;margin:0;">Track daily operational costs, utility bills, rent, and overheads</p>
    </div>
    <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Expense
    </a>
</div>

<!-- Expense Highlights -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Today's Expense</div>
            <div style="font-size:1.35rem;font-weight:800;color:#dc2626;">৳{{ number_format($totalToday, 2) }}</div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#fff7ed;color:#ea580c;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">This Month</div>
            <div style="font-size:1.35rem;font-weight:800;color:#ea580c;">৳{{ number_format($totalThisMonth, 2) }}</div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#f8fafc;color:#475569;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">All-Time Expenses</div>
            <div style="font-size:1.35rem;font-weight:800;color:#0f172a;">৳{{ number_format($totalAll, 2) }}</div>
        </div>
    </div>
</div>

<div class="card-panel">
    <div class="card-panel-header" style="flex-wrap:wrap;gap:12px;">
        <span class="card-panel-title"><i class="fa-solid fa-file-invoice-dollar"></i> Expense Records</span>

        <form method="GET" action="{{ route('admin.expenses.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;">
            <select name="category" class="form-control" style="width:160px;padding:6px 10px;font-size:0.82rem;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" style="width:135px;padding:6px 10px;font-size:0.82rem;" title="From Date">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" style="width:135px;padding:6px 10px;font-size:0.82rem;" title="To Date">

            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search title..." style="width:140px;padding:6px 10px;font-size:0.82rem;">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
            @if(request()->anyFilled(['category', 'from_date', 'to_date', 'search']))
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i></a>
            @endif
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Voucher #</th>
                <th>Category</th>
                <th>Title / Description</th>
                <th>Date</th>
                <th>Method</th>
                <th>Amount</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr>
                <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:0.75rem;">{{ $expense->expense_number }}</code></td>
                <td>
                    <span class="badge" style="background:#f1f5f9;color:#334155;">{{ $expense->category }}</span>
                </td>
                <td>
                    <strong style="color:#0f172a;display:block;">{{ $expense->title }}</strong>
                    @if($expense->note)
                        <small style="color:#64748b;font-size:0.75rem;">{{ Str::limit($expense->note, 45) }}</small>
                    @endif
                </td>
                <td>{{ $expense->expense_date->format('d M, Y') }}</td>
                <td><span style="text-transform:uppercase;font-size:0.75rem;font-weight:700;color:#64748b;">{{ $expense->payment_method }}</span></td>
                <td><strong style="color:#dc2626;font-size:0.95rem;">৳{{ number_format($expense->amount, 2) }}</strong></td>
                <td style="text-align:right;">
                    <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this expense?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:32px;color:#94a3b8;">
                    <i class="fa-solid fa-file-invoice-dollar" style="font-size:2rem;margin-bottom:8px;display:block;opacity:0.4;"></i>
                    No expenses recorded. Click "Add New Expense" to record one.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($expenses->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
        {{ $expenses->links() }}
    </div>
    @endif
</div>
@endsection

