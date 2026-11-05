@extends('layouts.admin')
@section('title', 'Due Customers | Italian Pizza Hut Admin')
@section('page-title', 'Due Customers Management')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin-bottom:4px;">Due Customers (বাকি খাতা)</h2>
        <p style="font-size:0.8rem;color:#64748b;margin:0;">Track customer dues, partial payments, and collect outstanding balances</p>
    </div>
</div>

<!-- Financial Summary Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-hand-holding-dollar"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Total Due Balance</div>
            <div style="font-size:1.35rem;font-weight:800;color:#dc2626;">৳{{ number_format($totalCustomerDue, 2) }}</div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Due Customers Count</div>
            <div style="font-size:1.35rem;font-weight:800;color:#0f172a;">{{ $totalDueCustomers }}</div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-money-bill-transfer"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Total Due Collected</div>
            <div style="font-size:1.35rem;font-weight:800;color:#16a34a;">৳{{ number_format($totalDueCollected, 2) }}</div>
        </div>
    </div>
</div>

<div class="card-panel">
    <div class="card-panel-header" style="flex-wrap:wrap;gap:12px;">
        <span class="card-panel-title"><i class="fa-solid fa-user-clock"></i> Customers with Outstanding Balance</span>

        <form method="GET" action="{{ route('admin.due-customers.index') }}" style="display:flex;gap:8px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, phone..." style="width:220px;padding:6px 12px;font-size:0.82rem;">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.due-customers.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i></a>
            @endif
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Due Orders</th>
                <th>Total Invoiced</th>
                <th>Total Paid</th>
                <th>Due Balance</th>
                <th>Last Order</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $c)
            <tr>
                <td>
                    <strong style="color:#0f172a;display:block;">{{ $c->name ?? 'Walk-in Customer' }}</strong>
                </td>
                <td>
                    @if($c->phone)
                        <a href="tel:{{ $c->phone }}" style="color:#2563eb;font-weight:600;"><i class="fa-solid fa-phone" style="font-size:0.75rem;"></i> {{ $c->phone }}</a>
                    @else
                        <span style="color:#94a3b8;">No phone</span>
                    @endif
                </td>
                <td>
                    <span class="badge" style="background:#fee2e2;color:#b91c1c;">
                        {{ $c->due_orders_count }} orders due
                    </span>
                </td>
                <td>৳{{ number_format($c->total_bill, 2) }}</td>
                <td style="color:#16a34a;font-weight:600;">৳{{ number_format($c->total_paid, 2) }}</td>
                <td>
                    <strong style="color:#dc2626;font-size:1rem;">৳{{ number_format($c->total_due, 2) }}</strong>
                </td>
                <td>
                    <small style="color:#64748b;">{{ \Carbon\Carbon::parse($c->latest_order_date)->format('d M, Y') }}</small>
                </td>
                <td style="text-align:right;">
                    <button type="button" class="btn btn-primary btn-sm" onclick="openCollectModal('{{ addslashes($c->customer_key) }}', '{{ addslashes($c->name) }}', {{ (float)$c->total_due }})">
                        <i class="fa-solid fa-hand-holding-dollar"></i> Collect Due
                    </button>
                    <a href="{{ route('admin.due-customers.show', $c->customer_key) }}" class="btn btn-secondary btn-sm" title="View Ledger">
                        <i class="fa-solid fa-clock-rotate-left"></i> History
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:32px;color:#94a3b8;">
                    <i class="fa-solid fa-circle-check" style="font-size:2rem;margin-bottom:8px;display:block;color:#10b981;opacity:0.6;"></i>
                    No customers have outstanding dues right now! All payments are clear.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($customers->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
        {{ $customers->links() }}
    </div>
    @endif
</div>

<!-- Collect Payment Modal -->
<div id="collectModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:24px;width:420px;max-width:92vw;box-shadow:0 20px 40px rgba(0,0,0,0.18);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin:0;">
                <i class="fa-solid fa-hand-holding-dollar" style="color:#16a34a;"></i> Collect Customer Due
            </h3>
            <button type="button" onclick="closeCollectModal()" style="border:none;background:none;font-size:1.2rem;color:#94a3b8;cursor:pointer;">&times;</button>
        </div>

        <div style="background:#f8fafc;padding:12px 14px;border-radius:8px;margin-bottom:16px;font-size:0.85rem;">
            Customer: <strong id="modalCustName" style="color:#0f172a;"></strong><br>
            Current Due Balance: <strong id="modalCustDue" style="color:#dc2626;font-size:0.95rem;"></strong>
        </div>

        <form action="{{ route('admin.due-customers.collect') }}" method="POST">
            @csrf
            <input type="hidden" name="customer_key" id="modalCustKey">

            <div class="form-group">
                <label class="form-label">Payment Amount (৳) *</label>
                <input type="number" step="0.01" min="0.01" name="amount" id="modalAmountInput" class="form-control" required placeholder="0.00">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Payment Method *</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="cash">Cash</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="card">Card</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Payment Date *</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Note / Reference</label>
                <input type="text" name="note" class="form-control" placeholder="e.g. Paid in cash at counter">
            </div>

            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">
                    <i class="fa-solid fa-check"></i> Record Payment
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeCollectModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('admin-scripts')
<script>
function openCollectModal(key, name, due) {
    document.getElementById('modalCustKey').value = key;
    document.getElementById('modalCustName').textContent = name;
    document.getElementById('modalCustDue').textContent = '৳ ' + due.toFixed(2);
    document.getElementById('modalAmountInput').value = due.toFixed(2);
    document.getElementById('collectModal').style.display = 'flex';
}
function closeCollectModal() {
    document.getElementById('collectModal').style.display = 'none';
}
</script>
@endpush
@endsection

