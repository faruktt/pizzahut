@extends('layouts.admin')
@section('title', 'Cash Statement & Reports | Italian Pizza Hut Admin')
@section('page-title', 'Cash Statement & Reporting')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;" class="no-print">
    <div>
        <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin-bottom:4px;">Cash Statement & Financial Summary</h2>
        <p style="font-size:0.8rem;color:#64748b;margin:0;">Real-time cash in hand calculation: Sales, Customer Dues, Purchases & Operating Expenses</p>
    </div>
    <div style="display:flex;gap:10px;">
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fa-solid fa-print"></i> Print Statement
        </button>
    </div>
</div>

<!-- Statement Period Filter Card -->
<div class="card-panel no-print" style="margin-bottom:20px;">
    <div class="card-panel-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('admin.reports.cash-statement') }}" id="periodFilterForm" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            
            <div style="display:flex;background:#f1f5f9;padding:4px;border-radius:8px;gap:4px;">
                <label style="padding:6px 14px;border-radius:6px;font-size:0.82rem;font-weight:700;cursor:pointer;{{ $period === 'daily' ? 'background:#fff;color:var(--admin-primary);box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'color:#64748b;' }}">
                    <input type="radio" name="period" value="daily" onchange="document.getElementById('periodFilterForm').submit()" {{ $period === 'daily' ? 'checked' : '' }} style="display:none;">
                    <i class="fa-solid fa-calendar-day"></i> Daily
                </label>

                <label style="padding:6px 14px;border-radius:6px;font-size:0.82rem;font-weight:700;cursor:pointer;{{ $period === 'monthly' ? 'background:#fff;color:var(--admin-primary);box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'color:#64748b;' }}">
                    <input type="radio" name="period" value="monthly" onchange="document.getElementById('periodFilterForm').submit()" {{ $period === 'monthly' ? 'checked' : '' }} style="display:none;">
                    <i class="fa-solid fa-calendar-days"></i> Monthly
                </label>

                <label style="padding:6px 14px;border-radius:6px;font-size:0.82rem;font-weight:700;cursor:pointer;{{ $period === 'yearly' ? 'background:#fff;color:var(--admin-primary);box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'color:#64748b;' }}">
                    <input type="radio" name="period" value="yearly" onchange="document.getElementById('periodFilterForm').submit()" {{ $period === 'yearly' ? 'checked' : '' }} style="display:none;">
                    <i class="fa-solid fa-calendar"></i> Yearly
                </label>

                <label style="padding:6px 14px;border-radius:6px;font-size:0.82rem;font-weight:700;cursor:pointer;{{ $period === 'custom' ? 'background:#fff;color:var(--admin-primary);box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'color:#64748b;' }}">
                    <input type="radio" name="period" value="custom" onchange="document.getElementById('periodFilterForm').submit()" {{ $period === 'custom' ? 'checked' : '' }} style="display:none;">
                    <i class="fa-solid fa-sliders"></i> Custom
                </label>
            </div>

            <!-- Dynamic Inputs based on Period -->
            @if($period === 'daily')
                <div style="display:flex;align-items:center;gap:8px;">
                    <label class="form-label" style="margin:0;font-size:0.82rem;">Select Date:</label>
                    <input type="date" name="date" value="{{ $selectedDate }}" class="form-control" style="width:150px;padding:6px 10px;font-size:0.82rem;" onchange="this.form.submit()">
                </div>
            @elseif($period === 'monthly')
                <div style="display:flex;align-items:center;gap:8px;">
                    <select name="month" class="form-control" style="width:140px;padding:6px 10px;font-size:0.82rem;" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>

                    <select name="year" class="form-control" style="width:100px;padding:6px 10px;font-size:0.82rem;" onchange="this.form.submit()">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            @elseif($period === 'yearly')
                <div style="display:flex;align-items:center;gap:8px;">
                    <label class="form-label" style="margin:0;font-size:0.82rem;">Select Year:</label>
                    <select name="year" class="form-control" style="width:110px;padding:6px 10px;font-size:0.82rem;" onchange="this.form.submit()">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            @elseif($period === 'custom')
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control" style="width:140px;padding:6px 10px;font-size:0.82rem;">
                    <span style="color:#64748b;">to</span>
                    <input type="date" name="to_date" value="{{ $toDate }}" class="form-control" style="width:140px;padding:6px 10px;font-size:0.82rem;">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Apply</button>
                </div>
            @endif

        </form>
    </div>
</div>

<!-- Printable Header for physical paper / PDF -->
<div class="print-only" style="display:none;margin-bottom:20px;text-align:center;">
    <h1 style="font-size:1.6rem;font-weight:900;margin:0;">Italian Pizza & Restaurant</h1>
    <h2 style="font-size:1.15rem;font-weight:700;margin:4px 0;">{{ $title }}</h2>
    <p style="font-size:0.8rem;color:#555;margin:0;">Generated on: {{ date('d M, Y h:i A') }}</p>
    <div style="border-bottom:2px solid #000;margin:12px 0;"></div>
</div>

<!-- Net Cash Balance Hero Card -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:16px;margin-bottom:24px;">
    <!-- NET CASH IN HAND -->
    <div style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%);color:#fff;border-radius:12px;padding:22px;box-shadow:0 8px 24px rgba(15,23,42,0.15);position:relative;overflow:hidden;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div>
                <span style="font-size:0.75rem;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#94a3b8;">
                    Net Cash In Hand / ব্যালেন্স
                </span>
                <div style="font-size:2rem;font-weight:900;margin-top:6px;color:{{ $netCash >= 0 ? '#4ade80' : '#f87171' }};">
                    ৳ {{ number_format($netCash, 2) }}
                </div>
            </div>
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="fa-solid fa-vault" style="color:#facc15;"></i>
            </div>
        </div>
        <div style="margin-top:14px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.1);display:flex;justify-content:space-between;font-size:0.8rem;">
            <span>Total Cash In: <strong style="color:#4ade80;">+৳{{ number_format($totalCashIn, 2) }}</strong></span>
            <span>Total Cash Out: <strong style="color:#f87171;">-৳{{ number_format($totalCashOut, 2) }}</strong></span>
        </div>
    </div>

    <!-- CASH IN BREAKDOWN -->
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.03);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-size:0.75rem;font-weight:800;text-transform:uppercase;color:#15803d;display:flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-arrow-down" style="color:#16a34a;"></i> Total Cash In (নগদ জমা)
            </span>
            <strong style="font-size:1.35rem;font-weight:900;color:#16a34a;">৳ {{ number_format($totalCashIn, 2) }}</strong>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.82rem;">
            <div style="display:flex;justify-content:space-between;color:#475569;">
                <span>Instant Sales Cash:</span>
                <strong>৳ {{ number_format($salesCashReceived, 2) }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;color:#475569;">
                <span>Customer Dues Collected:</span>
                <strong style="color:#2563eb;">+৳ {{ number_format($dueCollected, 2) }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;color:#94a3b8;font-size:0.75rem;border-top:1px dashed #e2e8f0;padding-top:4px;">
                <span>(Total Sales Volume: ৳{{ number_format($totalSalesGross, 2) }})</span>
            </div>
        </div>
    </div>

    <!-- CASH OUT BREAKDOWN -->
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.03);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-size:0.75rem;font-weight:800;text-transform:uppercase;color:#b91c1c;display:flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-arrow-up" style="color:#dc2626;"></i> Total Cash Out (নগদ খরচ)
            </span>
            <strong style="font-size:1.35rem;font-weight:900;color:#dc2626;">৳ {{ number_format($totalCashOut, 2) }}</strong>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.82rem;">
            <div style="display:flex;justify-content:space-between;color:#475569;">
                <span>Raw Material Purchases:</span>
                <strong>৳ {{ number_format($purchasesPaid, 2) }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;color:#475569;">
                <span>Operating Expenses / Bills:</span>
                <strong style="color:#dc2626;">৳ {{ number_format($expensesPaid, 2) }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;color:#94a3b8;font-size:0.75rem;border-top:1px dashed #e2e8f0;padding-top:4px;">
                <span>(Rent, Salaries, Electric, Gas, Supplies)</span>
            </div>
        </div>
    </div>

    <!-- CUSTOMER DUE METRICS -->
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.03);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-size:0.75rem;font-weight:800;text-transform:uppercase;color:#b45309;display:flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-hand-holding-dollar" style="color:#d97706;"></i> Customer Dues (বাকি)
            </span>
            <a href="{{ route('admin.due-customers.index') }}" class="btn btn-secondary btn-sm" style="font-size:0.72rem;padding:3px 8px;">
                Manage Dues
            </a>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.82rem;">
            <div style="display:flex;justify-content:space-between;color:#475569;">
                <span>New Due Given in this period:</span>
                <strong style="color:#d97706;">৳ {{ number_format($dueGiven, 2) }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;color:#475569;">
                <span>All-Time Outstanding Due:</span>
                <strong style="color:#dc2626;font-size:0.95rem;">৳ {{ number_format($totalCustomerDueAllTime, 2) }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;color:#94a3b8;font-size:0.75rem;border-top:1px dashed #e2e8f0;padding-top:4px;">
                <span>Total amount owed by customers</span>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statement Table -->
<div class="card-panel">
    <div class="card-panel-header" style="justify-content:space-between;">
        <span class="card-panel-title">
            <i class="fa-solid fa-table-list"></i> {{ $title }} - Transaction Breakdown
        </span>
        <span style="font-size:0.8rem;color:#64748b;">
            @if($period === 'daily')
                Showing individual cash movements for the day
            @elseif($period === 'monthly')
                Daily summary for each active day
            @elseif($period === 'yearly')
                Month-by-month financial summary
            @endif
        </span>
    </div>

    @if($period === 'daily')
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Reference #</th>
                    <th>Description</th>
                    <th style="text-align:right;">Cash In (+)</th>
                    <th style="text-align:right;">Cash Out (-)</th>
                    <th style="text-align:right;">Due / Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($breakdown as $row)
                <tr>
                    <td><small style="color:#64748b;font-weight:600;">{{ $row['time'] }}</small></td>
                    <td>
                        @if($row['type'] === 'Sale')
                            <span class="badge" style="background:#dcfce7;color:#15803d;"><i class="fa-solid fa-cart-shopping"></i> Sale</span>
                        @elseif($row['type'] === 'Due Collection')
                            <span class="badge" style="background:#e0f2fe;color:#0369a1;"><i class="fa-solid fa-hand-holding-dollar"></i> Due Paid</span>
                        @elseif($row['type'] === 'Purchase')
                            <span class="badge" style="background:#fff7ed;color:#ea580c;"><i class="fa-solid fa-dolly"></i> Purchase</span>
                        @elseif($row['type'] === 'Expense')
                            <span class="badge" style="background:#fee2e2;color:#b91c1c;"><i class="fa-solid fa-file-invoice"></i> Expense</span>
                        @endif
                    </td>
                    <td><strong>{{ $row['ref'] }}</strong></td>
                    <td>{{ $row['desc'] }}</td>
                    <td style="text-align:right;font-weight:700;color:{{ $row['cash_in'] > 0 ? '#16a34a' : '#94a3b8' }};">
                        {{ $row['cash_in'] > 0 ? '+৳' . number_format($row['cash_in'], 2) : '—' }}
                    </td>
                    <td style="text-align:right;font-weight:700;color:{{ $row['cash_out'] > 0 ? '#dc2626' : '#94a3b8' }};">
                        {{ $row['cash_out'] > 0 ? '-৳' . number_format($row['cash_out'], 2) : '—' }}
                    </td>
                    <td style="text-align:right;">
                        @if($row['due'] > 0)
                            <span style="color:#d97706;font-size:0.8rem;font-weight:700;">Due: ৳{{ number_format($row['due'], 2) }}</span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:32px;color:#94a3b8;">
                        No transactions recorded on this day.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:#f8fafc;font-weight:800;font-size:0.92rem;">
                    <td colspan="4" style="text-align:right;padding:14px;">Total For The Day:</td>
                    <td style="text-align:right;color:#16a34a;padding:14px;">+৳ {{ number_format($totalCashIn, 2) }}</td>
                    <td style="text-align:right;color:#dc2626;padding:14px;">-৳ {{ number_format($totalCashOut, 2) }}</td>
                    <td style="text-align:right;color:{{ $netCash >= 0 ? '#16a34a' : '#dc2626' }};padding:14px;">
                        Net: ৳ {{ number_format($netCash, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @elseif($period === 'monthly')
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th style="text-align:right;">Gross Sales</th>
                    <th style="text-align:right;">Cash Sales</th>
                    <th style="text-align:right;">Due Given</th>
                    <th style="text-align:right;">Due Collected</th>
                    <th style="text-align:right;">Purchases</th>
                    <th style="text-align:right;">Expenses</th>
                    <th style="text-align:right;">Net Day Cash</th>
                </tr>
            </thead>
            <tbody>
                @forelse($breakdown as $row)
                <tr>
                    <td><strong>{{ $row['date'] }}</strong></td>
                    <td style="text-align:right;">৳ {{ number_format($row['sales_gross'], 2) }}</td>
                    <td style="text-align:right;color:#16a34a;font-weight:700;">৳ {{ number_format($row['cash_sales'], 2) }}</td>
                    <td style="text-align:right;color:#d97706;">৳ {{ number_format($row['due_given'], 2) }}</td>
                    <td style="text-align:right;color:#2563eb;font-weight:700;">+৳ {{ number_format($row['due_collected'], 2) }}</td>
                    <td style="text-align:right;color:#ea580c;">৳ {{ number_format($row['purchases'], 2) }}</td>
                    <td style="text-align:right;color:#dc2626;">৳ {{ number_format($row['expenses'], 2) }}</td>
                    <td style="text-align:right;font-weight:900;color:{{ $row['net'] >= 0 ? '#16a34a' : '#dc2626' }};">
                        ৳ {{ number_format($row['net'], 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:32px;color:#94a3b8;">
                        No activity recorded in this month.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:#f8fafc;font-weight:800;font-size:0.92rem;">
                    <td>Month Total:</td>
                    <td style="text-align:right;">৳ {{ number_format($totalSalesGross, 2) }}</td>
                    <td style="text-align:right;color:#16a34a;">৳ {{ number_format($salesCashReceived, 2) }}</td>
                    <td style="text-align:right;color:#d97706;">৳ {{ number_format($dueGiven, 2) }}</td>
                    <td style="text-align:right;color:#2563eb;">+৳ {{ number_format($dueCollected, 2) }}</td>
                    <td style="text-align:right;color:#ea580c;">৳ {{ number_format($purchasesPaid, 2) }}</td>
                    <td style="text-align:right;color:#dc2626;">৳ {{ number_format($expensesPaid, 2) }}</td>
                    <td style="text-align:right;font-weight:900;color:{{ $netCash >= 0 ? '#16a34a' : '#dc2626' }};">
                        ৳ {{ number_format($netCash, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @elseif($period === 'yearly')
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th style="text-align:right;">Gross Sales</th>
                    <th style="text-align:right;">Cash Sales</th>
                    <th style="text-align:right;">Due Given</th>
                    <th style="text-align:right;">Due Collected</th>
                    <th style="text-align:right;">Purchases</th>
                    <th style="text-align:right;">Expenses</th>
                    <th style="text-align:right;">Net Month Cash</th>
                </tr>
            </thead>
            <tbody>
                @foreach($breakdown as $row)
                <tr>
                    <td><strong>{{ $row['month_name'] }}</strong></td>
                    <td style="text-align:right;">৳ {{ number_format($row['sales_gross'], 2) }}</td>
                    <td style="text-align:right;color:#16a34a;font-weight:700;">৳ {{ number_format($row['cash_sales'], 2) }}</td>
                    <td style="text-align:right;color:#d97706;">৳ {{ number_format($row['due_given'], 2) }}</td>
                    <td style="text-align:right;color:#2563eb;font-weight:700;">+৳ {{ number_format($row['due_collected'], 2) }}</td>
                    <td style="text-align:right;color:#ea580c;">৳ {{ number_format($row['purchases'], 2) }}</td>
                    <td style="text-align:right;color:#dc2626;">৳ {{ number_format($row['expenses'], 2) }}</td>
                    <td style="text-align:right;font-weight:900;color:{{ $row['net'] >= 0 ? '#16a34a' : '#dc2626' }};">
                        ৳ {{ number_format($row['net'], 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f8fafc;font-weight:800;font-size:0.92rem;">
                    <td>Year Total:</td>
                    <td style="text-align:right;">৳ {{ number_format($totalSalesGross, 2) }}</td>
                    <td style="text-align:right;color:#16a34a;">৳ {{ number_format($salesCashReceived, 2) }}</td>
                    <td style="text-align:right;color:#d97706;">৳ {{ number_format($dueGiven, 2) }}</td>
                    <td style="text-align:right;color:#2563eb;">+৳ {{ number_format($dueCollected, 2) }}</td>
                    <td style="text-align:right;color:#ea580c;">৳ {{ number_format($purchasesPaid, 2) }}</td>
                    <td style="text-align:right;color:#dc2626;">৳ {{ number_format($expensesPaid, 2) }}</td>
                    <td style="text-align:right;font-weight:900;color:{{ $netCash >= 0 ? '#16a34a' : '#dc2626' }};">
                        ৳ {{ number_format($netCash, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @else
        <div style="padding:24px;text-align:center;color:#64748b;">
            Custom date range summary displayed in top cards.
        </div>
    @endif
</div>
@endsection

