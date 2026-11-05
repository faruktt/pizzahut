@extends('layouts.admin')
@section('title', 'Purchase Invoice ' . $purchase->purchase_number . ' | Italian Pizza Hut Admin')
@section('page-title', 'Purchase Invoice')

@section('admin-content')
<div style="max-width:850px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;" class="no-print">
        <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Purchases
        </a>
        <div style="display:flex;gap:8px;">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print"></i> Print Invoice
            </button>
        </div>
    </div>

    <div class="card-panel" style="padding:32px;background:#fff;" id="invoiceCard">
        <!-- Invoice Header -->
        <div style="display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #f1f5f9;padding-bottom:24px;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
            <div>
                <h1 style="font-size:1.5rem;font-weight:900;color:var(--admin-primary);margin-bottom:4px;">PURCHASE INVOICE</h1>
                <div style="font-size:0.95rem;font-weight:700;color:#0f172a;"># {{ $purchase->purchase_number }}</div>
                <div style="font-size:0.8rem;color:#64748b;margin-top:2px;">Date: {{ $purchase->purchase_date->format('d M, Y') }}</div>
            </div>

            <div style="text-align:right;">
                <div style="font-size:1.1rem;font-weight:800;color:#0f172a;">Italian Pizza & POS</div>
                <div style="font-size:0.8rem;color:#64748b;">Inventory & Material Management</div>
                <div style="margin-top:8px;">
                    @if($purchase->payment_status === 'paid')
                        <span class="badge" style="background:#dcfce7;color:#15803d;font-size:0.8rem;padding:4px 12px;"><i class="fa-solid fa-check"></i> FULLY PAID</span>
                    @elseif($purchase->payment_status === 'partial')
                        <span class="badge" style="background:#fef3c7;color:#b45309;font-size:0.8rem;padding:4px 12px;"><i class="fa-solid fa-circle-half-stroke"></i> PARTIAL PAID</span>
                    @else
                        <span class="badge" style="background:#fee2e2;color:#b91c1c;font-size:0.8rem;padding:4px 12px;"><i class="fa-solid fa-clock"></i> UNPAID</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Supplier & Payment Meta -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;padding:16px 20px;background:#f8fafc;border-radius:10px;">
            <div>
                <div style="font-size:0.75rem;font-weight:800;color:#64748b;text-transform:uppercase;margin-bottom:4px;">Vendor / Supplier:</div>
                @if($purchase->supplier)
                    <div style="font-size:1rem;font-weight:800;color:#0f172a;">{{ $purchase->supplier->name }}</div>
                    @if($purchase->supplier->company_name)
                        <div style="font-size:0.82rem;color:#475569;font-weight:600;">{{ $purchase->supplier->company_name }}</div>
                    @endif
                    @if($purchase->supplier->phone)
                        <div style="font-size:0.8rem;color:#64748b;margin-top:2px;"><i class="fa-solid fa-phone" style="font-size:0.75rem;"></i> {{ $purchase->supplier->phone }}</div>
                    @endif
                    @if($purchase->supplier->address)
                        <div style="font-size:0.78rem;color:#64748b;margin-top:2px;">{{ $purchase->supplier->address }}</div>
                    @endif
                @else
                    <div style="font-size:0.95rem;font-weight:700;color:#64748b;">Walk-in / Cash Vendor</div>
                @endif
            </div>

            <div style="text-align:right;">
                <div style="font-size:0.75rem;font-weight:800;color:#64748b;text-transform:uppercase;margin-bottom:4px;">Payment Info:</div>
                <div style="font-size:0.85rem;color:#0f172a;"><strong>Method:</strong> {{ strtoupper($purchase->payment_method) }}</div>
                <div style="font-size:0.85rem;color:#0f172a;margin-top:2px;"><strong>Paid Amount:</strong> ৳{{ number_format($purchase->paid_amount, 2) }}</div>
                @if($purchase->due_amount > 0)
                    <div style="font-size:0.85rem;color:#dc2626;margin-top:2px;font-weight:700;"><strong>Due Balance:</strong> ৳{{ number_format($purchase->due_amount, 2) }}</div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="admin-table" style="margin-bottom:24px;">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Ingredient Item</th>
                    <th style="text-align:center;">Quantity</th>
                    <th style="text-align:right;">Unit Price</th>
                    <th style="text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong style="color:#0f172a;">{{ $item->ingredient->name ?? 'Deleted Item' }}</strong>
                        @if($item->ingredient && $item->ingredient->code)
                            <small style="color:#64748b;margin-left:6px;">({{ $item->ingredient->code }})</small>
                        @endif
                    </td>
                    <td style="text-align:center;font-weight:700;">
                        {{ (float)$item->quantity }} {{ $item->ingredient->unit ?? '' }}
                    </td>
                    <td style="text-align:right;">৳{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align:right;font-weight:800;color:#0f172a;">৳{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Breakdown -->
        <div style="display:flex;justify-content:flex-end;margin-bottom:24px;">
            <div style="width:280px;background:#f8fafc;border-radius:10px;padding:16px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.9rem;">
                    <span style="color:#64748b;">Total Amount:</span>
                    <strong style="color:#0f172a;">৳{{ number_format($purchase->total_amount, 2) }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.9rem;">
                    <span style="color:#64748b;">Paid Amount:</span>
                    <strong style="color:#16a34a;">৳{{ number_format($purchase->paid_amount, 2) }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;border-top:1px solid #e2e8f0;padding-top:8px;font-size:1.05rem;">
                    <span style="font-weight:800;color:#0f172a;">Due Amount:</span>
                    <strong style="color:{{ $purchase->due_amount > 0 ? '#dc2626' : '#16a34a' }};">৳{{ number_format($purchase->due_amount, 2) }}</strong>
                </div>
            </div>
        </div>

        @if($purchase->note)
        <div style="background:#f8fafc;border-left:3px solid #cbd5e1;padding:12px 16px;border-radius:4px;font-size:0.82rem;color:#475569;">
            <strong>Note / Remarks:</strong> {{ $purchase->note }}
        </div>
        @endif
    </div>
</div>
@endsection

