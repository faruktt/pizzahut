@extends('layouts.admin')

@section('title', 'Order Invoice #' . $order->order_number . ' | Italian Pizza')
@section('page-title', 'Order #' . $order->order_number)

@push('admin-styles')
<style>
    .invoice-page-wrapper {
        max-width: 950px;
        margin: 0 auto 40px auto;
    }

    .invoice-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .invoice-screen-header {
        padding: 16px 24px;
        background: #ffffff;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .invoice-status-bar {
        background: #f8fafc;
        border-bottom: 1px solid var(--border-light);
        padding: 14px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }

    /* A4 Sheet Presentation */
    .a4-sheet {
        padding: 36px 40px;
        background: #ffffff;
        color: #0f172a;
        font-family: 'Poppins', sans-serif;
    }

    .invoice-top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
    }

    .invoice-brand-col {
        flex: 1;
        max-width: 55%;
    }

    .invoice-logo-img {
        max-height: 52px;
        width: auto;
        max-width: 220px;
        object-fit: contain;
        display: block;
        margin-bottom: 10px;
    }

    .invoice-logo-fallback {
        margin-bottom: 8px;
    }

    .invoice-logo-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.6rem;
        font-weight: 900;
        color: var(--admin-primary);
        letter-spacing: -0.5px;
        display: block;
        line-height: 1.2;
    }

    .invoice-logo-subtitle {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        font-weight: 600;
    }

    .invoice-company-details {
        font-size: 0.8rem;
        color: #475569;
        line-height: 1.45;
    }

    .invoice-meta-col {
        text-align: right;
    }

    .invoice-badge-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 2.1rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .invoice-number-tag {
        display: inline-block;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.95rem;
        color: var(--admin-primary);
        margin-bottom: 6px;
    }

    .invoice-meta-list {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.5;
    }

    .invoice-divider-line {
        height: 2px;
        background: linear-gradient(90deg, var(--admin-primary) 0%, #cbd5e1 50%, #f1f5f9 100%);
        margin-bottom: 22px;
    }

    .invoice-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 24px;
    }

    .invoice-info-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 16px;
    }

    .invoice-info-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #475569;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        padding-bottom: 6px;
        border-bottom: 1px solid #e2e8f0;
    }

    .invoice-info-card p {
        font-size: 0.82rem;
        color: #1e293b;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .invoice-info-card p strong {
        color: #0f172a;
        min-width: 80px;
        display: inline-block;
    }

    .table-callout {
        background: #eff6ff;
        border: 1.5px solid #bfdbfe;
        color: #1e40af;
        padding: 3px 8px;
        border-radius: 5px;
        font-weight: 800;
        display: inline-block;
    }

    /* Items Table */
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 0.84rem;
    }

    .invoice-table thead th {
        background: #0f172a;
        color: #ffffff;
        padding: 9px 12px;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .invoice-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
    }

    .invoice-table tbody tr:nth-child(even) {
        background: #fbfcfe;
    }

    .invoice-table tbody td {
        padding: 10px 12px;
        color: #1e293b;
        vertical-align: top;
    }

    .item-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.86rem;
    }

    .item-var-line {
        font-size: 0.76rem;
        color: #64748b;
        margin-top: 2px;
    }

    .item-addons-line {
        font-size: 0.74rem;
        color: #0284c7;
        margin-top: 2px;
    }

    /* Calculation & Signatures */
    .invoice-bottom-grid {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 20px;
        margin-top: 8px;
        align-items: start;
    }

    .invoice-terms-card {
        border-left: 3px solid var(--admin-primary);
        padding-left: 12px;
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.5;
    }

    .invoice-calc-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 16px;
    }

    .calc-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.84rem;
        margin-bottom: 5px;
        color: #334155;
    }

    .calc-row.discount {
        color: #16a34a;
        font-weight: 600;
    }

    .calc-grand-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 2px dashed #cbd5e1;
        font-size: 1.2rem;
        font-weight: 900;
        color: var(--admin-primary);
    }

    .invoice-signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 16px;
    }

    .sig-block {
        width: 180px;
        text-align: center;
    }

    .sig-line {
        border-top: 1px dashed #94a3b8;
        margin-bottom: 6px;
    }

    .sig-text {
        font-size: 0.74rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* A4 Print Styles */
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm 14mm;
        }

        body, html {
            background: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .no-print,
        .no-print * {
            display: none !important;
        }

        .invoice-page-wrapper {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .invoice-card {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .a4-sheet {
            padding: 0 !important;
            width: 100% !important;
        }

        .invoice-table thead th {
            background: #0f172a !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .invoice-table tbody tr {
            page-break-inside: avoid !important;
        }

        .invoice-info-card,
        .invoice-calc-card {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .table-callout {
            background: #eff6ff !important;
            border: 1px solid #93c5fd !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .calc-grand-total {
            color: #000000 !important;
            border-top: 2px solid #000000 !important;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="invoice-page-wrapper">
    <div class="invoice-card">

        <!-- Top Screen Actions Header (Hidden on Print) -->
        <div class="invoice-screen-header no-print">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">
                    Order Invoice #{{ $order->order_number }}
                </h3>
                <div style="font-size:0.8rem; color:#64748b; margin-top:2px;">
                    Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="window.print()" class="btn-quick-pos" style="padding: 8px 16px; font-size: 0.85rem; cursor: pointer;">
                    <i class="fa-solid fa-print"></i> Print A4 Invoice
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn-view-store" style="padding: 8px 14px; font-size: 0.85rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>

        <!-- Quick Status Update Bar (Hidden on Print) -->
        @php
            $stObj = $orderStatuses->firstWhere('slug', $order->order_status)
                ?? \App\Models\OrderStatus::findBySlugOrName($order->order_status);
            $stBg = $stObj->bg_color ?? '#f1f5f9';
            $stCol = $stObj->color ?? '#334155';
            $stName = $stObj->name ?? ucfirst(str_replace('_', ' ', $order->order_status));
        @endphp
        <div class="invoice-status-bar no-print">
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <span style="font-size: 0.8rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Current Status:</span>
                <span style="display:inline-flex; align-items:center; gap:6px; padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 800; background: {{ $stBg }}; color: {{ $stCol }}; border: 1.5px solid {{ $stCol }}33;">
                    <span style="width:7px; height:7px; border-radius:50%; background:{{ $stCol }};"></span>
                    {{ $stName }}
                </span>
                <span class="badge {{ $order->payment_status === 'paid' ? 'badge-completed' : 'badge-pending' }}" style="font-size:0.8rem; padding:4px 10px;">
                    Payment: {{ ucfirst($order->payment_status) }}
                </span>
            </div>

            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin:0;">
                @csrf
                <label style="font-size: 0.82rem; font-weight:700;">Update Status:</label>
                <select name="order_status" class="form-control" style="padding: 6px 12px; font-size: 0.82rem; font-weight:600; min-width:140px;">
                    @foreach($orderStatuses as $statusItem)
                        <option value="{{ $statusItem->slug }}" {{ $order->order_status === $statusItem->slug ? 'selected' : '' }}>
                            {{ $statusItem->name }}
                        </option>
                    @endforeach
                    @if(!$orderStatuses->contains('slug', $order->order_status) && $order->order_status)
                        <option value="{{ $order->order_status }}" selected>{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</option>
                    @endif
                </select>

                <select name="payment_status" class="form-control" style="padding: 6px 12px; font-size: 0.82rem; font-weight:600;">
                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>

                <button type="submit" class="btn-quick-pos" style="padding: 6px 14px; font-size: 0.82rem;">
                    <i class="fa-solid fa-check"></i> Save
                </button>
            </form>
        </div>

        <!-- ============================================== -->
        <!-- BEAUTIFUL A4 PRINTABLE INVOICE SHEET           -->
        <!-- ============================================== -->
        <div class="a4-sheet">
            @php
                $siteLogo = \App\Models\Setting::getValue('site_logo');
                $siteName = \App\Models\Setting::getValue('site_name', 'Italian Pizza');
                $siteAddress = \App\Models\Setting::getValue('site_address', 'House 14, Road 11, Block D, Gulshan-2, Dhaka');
                $sitePhone = \App\Models\Setting::getValue('site_phone', '+880 1711-223344');
                $siteEmail = \App\Models\Setting::getValue('site_email', 'contact@italianpizza.com');
            @endphp

            <!-- 1. HEADER: BRAND & INVOICE META -->
            <div class="invoice-top-row">
                <div class="invoice-brand-col">
                    @if(!empty($siteLogo) && file_exists(public_path('uploads/' . $siteLogo)))
                        <img src="{{ asset('uploads/' . $siteLogo) }}" alt="{{ $siteName }}" class="invoice-logo-img">
                    @else
                        <div class="invoice-logo-fallback">
                            <span class="invoice-logo-title">{{ $siteName }}</span>
                            <span class="invoice-logo-subtitle">Authentic Italian Oven & Dine</span>
                        </div>
                    @endif
                    <div class="invoice-company-details">
                        <div>{{ $siteAddress }}</div>
                        <div>Hotline: {{ $sitePhone }} | Email: {{ $siteEmail }}</div>
                    </div>
                </div>

                <div class="invoice-meta-col">
                    <div class="invoice-badge-title">INVOICE</div>
                    <div>
                        <span class="invoice-number-tag">#{{ $order->order_number }}</span>
                    </div>
                    <div class="invoice-meta-list">
                        <div><strong>Date:</strong> {{ $order->created_at->format('d M, Y - h:i A') }}</div>
                        <div><strong>Order Type:</strong> {{ strtoupper(str_replace('_', ' ', $order->order_type)) }}</div>
                        <div><strong>Payment:</strong> {{ strtoupper($order->payment_method) }} (<span style="color: {{ $order->payment_status === 'paid' ? '#15803d' : '#dc2626' }}; font-weight:700;">{{ strtoupper($order->payment_status) }}</span>)</div>
                    </div>
                </div>
            </div>

            <!-- Divider Line -->
            <div class="invoice-divider-line"></div>

            <!-- 2. DETAILS GRID: CUSTOMER & ORDER -->
            <div class="invoice-details-grid">
                <div class="invoice-info-card">
                    <div class="invoice-info-title">
                        <i class="fa-solid fa-user" style="margin-right:4px;"></i> Customer / Dining Details
                    </div>
                    <p><strong>Name:</strong> {{ $order->customer_name ?: ($order->user->name ?? 'Guest Customer') }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_phone ?: ($order->user->phone ?? 'N/A') }}</p>
                    @if($order->order_type === 'dine_in')
                        <p><strong>Dining:</strong> <span class="table-callout"><i class="fa-solid fa-utensils"></i> Table: {{ $order->table->table_number ?? 'Dine-In Table' }}</span></p>
                    @else
                        <p><strong>Address:</strong> {{ $order->customer_address ?: 'N/A' }}</p>
                        @if($order->delivery_location)
                            <p><strong>Area:</strong> {{ $order->delivery_location }}</p>
                        @endif
                    @endif
                </div>

                <div class="invoice-info-card">
                    <div class="invoice-info-title">
                        <i class="fa-solid fa-file-lines" style="margin-right:4px;"></i> Order Summary
                    </div>
                    <p><strong>Invoice No:</strong> #{{ $order->order_number }}</p>
                    <p><strong>Order Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</p>
                    <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                    @if($order->special_instructions)
                        <p><strong>Instructions:</strong> {{ $order->special_instructions }}</p>
                    @endif
                </div>
            </div>

            <!-- 3. ITEMS TABLE -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">#</th>
                        <th style="width: 38%;">Item Description</th>
                        <th style="width: 20%;">Size & Crust</th>
                        <th style="width: 17%;">Add-ons</th>
                        <th style="width: 10%; text-align: right;">Price</th>
                        <th style="width: 10%; text-align: center;">Qty</th>
                        <th style="width: 10%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td style="text-align: center; color:#64748b;">{{ $index + 1 }}</td>
                            <td>
                                <div class="item-title">{{ $item->product_name }}</div>
                                @if($item->combo_details)
                                    <div style="font-size:0.75rem; color:var(--admin-primary); font-weight:600; margin-top:2px;">
                                        Combo: {{ $item->combo_details }}
                                    </div>
                                @endif
                                @if($item->special_instructions)
                                    <div style="font-size:0.72rem; color:#64748b; font-style:italic;">
                                        Note: {{ $item->special_instructions }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $variations = [];
                                    if ($item->size_name && strtolower($item->size_name) !== 'standard') {
                                        $variations[] = $item->size_name;
                                    }
                                    if ($item->crust_name && strtolower($item->crust_name) !== 'standard') {
                                        $variations[] = $item->crust_name;
                                    }
                                @endphp
                                @if(count($variations) > 0)
                                    <div class="item-var-line">{{ implode(' • ', $variations) }}</div>
                                @else
                                    <span style="color:#94a3b8; font-size:0.75rem;">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->addons && $item->addons->isNotEmpty())
                                    <div class="item-addons-line">
                                        @foreach($item->addons as $addon)
                                            <div>+ {{ $addon->add_on_name }} (৳{{ number_format($addon->price) }})</div>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="color:#94a3b8; font-size:0.75rem;">-</span>
                                @endif
                            </td>
                            <td style="text-align: right;">৳ {{ number_format($item->unit_price) }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ $item->quantity }}</td>
                            <td style="text-align: right; font-weight: 700;">৳ {{ number_format($item->total_price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 4. TOTALS & FINANCIAL SUMMARY -->
            <div class="invoice-bottom-grid">
                <div class="invoice-terms-card">
                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px; text-transform: uppercase;">Terms & Notes</div>
                    <div>• All prices are inclusive of applicable VAT / Tax (5%).</div>
                    <div>• Please preserve this invoice for any verification or customer queries.</div>
                    <div>• Hotline support: {{ $sitePhone }}</div>
                    <div style="margin-top: 10px; font-weight: 700; color: var(--admin-primary); font-size: 0.85rem;">
                        Thank you for dining with Italian Pizza!
                    </div>
                </div>

                <div class="invoice-calc-card">
                    <div class="calc-row">
                        <span>Subtotal:</span>
                        <strong>৳ {{ number_format($order->subtotal) }}</strong>
                    </div>
                    @if($order->coupon_discount > 0)
                        <div class="calc-row discount">
                            <span>Coupon Discount ({{ $order->coupon_code }}):</span>
                            <strong>-৳ {{ number_format($order->coupon_discount) }}</strong>
                        </div>
                    @endif
                    @if($order->points_discount > 0)
                        <div class="calc-row discount">
                            <span>Points Redeemed ({{ $order->points_used }} pts):</span>
                            <strong>-৳ {{ number_format($order->points_discount) }}</strong>
                        </div>
                    @endif
                    @if($order->delivery_charge > 0)
                        <div class="calc-row">
                            <span>Delivery Charge:</span>
                            <strong>৳ {{ number_format($order->delivery_charge) }}</strong>
                        </div>
                    @endif
                    <div class="calc-row">
                        <span>VAT / Tax (5%):</span>
                        <strong>৳ {{ number_format($order->vat_tax) }}</strong>
                    </div>
                    <div class="calc-grand-total">
                        <span>Grand Total:</span>
                        <span>৳ {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>

            <!-- 5. SIGNATURES ROW -->
            <div class="invoice-signatures">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-text">Customer's Signature</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-text">Authorized Signature & Seal</div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
