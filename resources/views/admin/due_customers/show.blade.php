@extends('layouts.admin')
@section('title', 'Customer Due Ledger: ' . $customerName . ' | Italian Pizza Hut Admin')
@section('page-title', 'Customer Due Ledger')

@section('admin-content')
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.due-customers.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to Due Customers
    </a>
</div>

<!-- Customer Profile Summary -->
<div class="card-panel" style="margin-bottom:20px;">
    <div class="card-panel-body" style="padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="width:52px;height:52px;border-radius:50%;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h2 style="font-size:1.2rem;font-weight:800;color:#0f172a;margin:0;">{{ $customerName }}</h2>
                    <div style="font-size:0.85rem;color:#64748b;margin-top:2px;">
                        @if($customerPhone)
                            <a href="tel:{{ $customerPhone }}" style="color:#2563eb;font-weight:600;"><i class="fa-solid fa-phone" style="font-size:0.75rem;"></i> {{ $customerPhone }}</a>
                        @else
                            No Phone Number
                        @endif
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:20px;text-align:right;">
                <div>
                    <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Total Bill</div>
                    <div style="font-size:1.15rem;font-weight:800;color:#0f172a;">৳{{ number_format($totalBill, 2) }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Total Paid</div>
                    <div style="font-size:1.15rem;font-weight:800;color:#16a34a;">৳{{ number_format($totalPaid, 2) }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Due Balance</div>
                    <div style="font-size:1.35rem;font-weight:900;color:#dc2626;">৳{{ number_format($totalDue, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-row" style="align-items:flex-start;">
    <!-- Due & Completed Orders -->
    <div style="flex:1.4;">
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-receipt"></i> Orders History</span>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" style="font-weight:700;color:var(--admin-primary);">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>{{ $order->created_at->format('d M, Y') }}</td>
                        <td>৳{{ number_format($order->total_amount, 2) }}</td>
                        <td style="color:#16a34a;font-weight:600;">৳{{ number_format($order->paid_amount, 2) }}</td>
                        <td>
                            @if($order->due_amount > 0)
                                <strong style="color:#dc2626;">৳{{ number_format($order->due_amount, 2) }}</strong>
                            @else
                                <span style="color:#16a34a;">৳0.00</span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge" style="background:#dcfce7;color:#15803d;">Paid</span>
                            @elseif($order->payment_status === 'partial')
                                <span class="badge" style="background:#fef3c7;color:#b45309;">Partial</span>
                            @else
                                <span class="badge" style="background:#fee2e2;color:#b91c1c;">Due</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:20px;">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Collect Payment & Payment History -->
    <div style="flex:1;">
        <!-- Quick Pay Card -->
        @if($totalDue > 0)
        <div class="card-panel" style="margin-bottom:20px;">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-hand-holding-dollar"></i> Collect Due Payment</span>
            </div>
            <div class="card-panel-body">
                <form action="{{ route('admin.due-customers.collect') }}" method="POST">
                    @csrf
                    <input type="hidden" name="customer_key" value="{{ $customerKey }}">

                    <div class="form-group">
                        <label class="form-label">Payment Amount (৳) *</label>
                        <input type="number" step="0.01" min="0.01" max="{{ $totalDue }}" name="amount" class="form-control" value="{{ $totalDue }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Method *</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cash">Cash</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="card">Card</option>
                                <option value="bank">Bank</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Date *</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Note</label>
                        <input type="text" name="note" class="form-control" placeholder="Remarks...">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        <i class="fa-solid fa-check"></i> Collect Payment
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Payments Collected History -->
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-money-bill-transfer"></i> Payment Collections</span>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Method</th>
                        <th style="text-align:right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentHistory as $pay)
                    <tr>
                        <td>
                            <strong>{{ $pay->payment_date->format('d M, Y') }}</strong>
                            @if($pay->note)
                                <div style="font-size:0.75rem;color:#64748b;">{{ $pay->note }}</div>
                            @endif
                        </td>
                        <td><span style="text-transform:uppercase;font-size:0.75rem;font-weight:700;">{{ $pay->payment_method }}</span></td>
                        <td style="text-align:right;color:#16a34a;font-weight:800;">+৳{{ number_format($pay->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:20px;">No payment collections recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

