@extends('layouts.admin')

@section('title', 'Order Invoice #' . $order->order_number . ' | Italian Pizza Hut')
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

    /* Hidden on normal screen */
    .pos-receipt-print-only,
    .kot-receipt-print-only {
        display: none;
    }

    /* Print Styles (Adaptive for POS 58mm, 80mm & A4) */
    @media print {
        body, html {
            background: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .no-print,
        .no-print * {
            display: none !important;
        }

        /* Hide non-active print blocks */
        body.mode-pos-58 .kot-receipt-print-only,
        body.mode-pos-80 .kot-receipt-print-only,
        body.mode-pos .kot-receipt-print-only,
        body.mode-a4 .kot-receipt-print-only {
            display: none !important;
        }

        /* 1. POS 58mm Thermal Receipt Print Mode (Default for standard mini thermal printers) */
        body.mode-pos-58 @page,
        body.mode-pos @page {
            size: 58mm auto;
            margin: 0mm !important;
        }
        body.mode-pos-58 .a4-sheet,
        body.mode-pos-58 .invoice-card,
        body.mode-pos-58 .kot-receipt-print-only,
        body.mode-pos .a4-sheet,
        body.mode-pos .invoice-card,
        body.mode-pos .kot-receipt-print-only {
            display: none !important;
        }
        body.mode-pos-58 .pos-receipt-print-only,
        body.mode-pos .pos-receipt-print-only {
            display: block !important;
            width: 48mm !important;
            max-width: 48mm !important;
            margin: 0 auto !important;
            padding: 1mm 1mm !important;
            box-sizing: border-box !important;
        }
        body.mode-pos-58 .pos-receipt-print-only .thermal-receipt-wrap,
        body.mode-pos .pos-receipt-print-only .thermal-receipt-wrap {
            width: 48mm !important;
            max-width: 48mm !important;
            font-size: 9.5px !important;
            line-height: 1.28 !important;
            padding: 1mm !important;
            box-sizing: border-box !important;
        }

        /* 2. POS 80mm Thermal Receipt Print Mode */
        body.mode-pos-80 @page {
            size: 80mm auto;
            margin: 0mm !important;
        }
        body.mode-pos-80 .a4-sheet,
        body.mode-pos-80 .invoice-card,
        body.mode-pos-80 .kot-receipt-print-only {
            display: none !important;
        }
        body.mode-pos-80 .pos-receipt-print-only {
            display: block !important;
            width: 72mm !important;
            max-width: 72mm !important;
            margin: 0 auto !important;
            padding: 2mm 2mm !important;
            box-sizing: border-box !important;
        }
        body.mode-pos-80 .pos-receipt-print-only .thermal-receipt-wrap {
            width: 72mm !important;
            max-width: 72mm !important;
            font-size: 11px !important;
            line-height: 1.35 !important;
            padding: 2mm !important;
            box-sizing: border-box !important;
        }

        /* 3. Web A4 Sheet Print Mode */
        body.mode-a4 @page {
            size: A4 portrait;
            margin: 10mm 14mm;
        }
        body.mode-a4 .pos-receipt-print-only,
        body.mode-a4 .kot-receipt-print-only {
            display: none !important;
        }
        body.mode-a4 .invoice-page-wrapper {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        body.mode-a4 .invoice-card {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            display: block !important;
        }
        body.mode-a4 .a4-sheet {
            display: block !important;
            padding: 0 !important;
            width: 100% !important;
        }
        body.mode-a4 .invoice-table thead th {
            background: #0f172a !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body.mode-a4 .invoice-table tbody tr {
            page-break-inside: avoid !important;
        }
        body.mode-a4 .invoice-info-card,
        body.mode-a4 .invoice-calc-card {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body.mode-a4 .table-callout {
            background: #eff6ff !important;
            border: 1px solid #93c5fd !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body.mode-a4 .calc-grand-total {
            color: #000000 !important;
            border-top: 2px solid #000000 !important;
        }

        /* 4. KOT 58mm Kitchen Ticket Print Mode */
        body.mode-kot-58 @page {
            size: 58mm auto;
            margin: 0mm !important;
        }
        body.mode-kot-58 .a4-sheet,
        body.mode-kot-58 .invoice-card,
        body.mode-kot-58 .pos-receipt-print-only {
            display: none !important;
        }
        body.mode-kot-58 .kot-receipt-print-only {
            display: block !important;
            width: 48mm !important;
            max-width: 48mm !important;
            margin: 0 auto !important;
            padding: 1mm 1mm !important;
            box-sizing: border-box !important;
        }
        body.mode-kot-58 .kot-receipt-print-only .kot-wrap {
            width: 48mm !important;
            max-width: 48mm !important;
            font-size: 9.5px !important;
            line-height: 1.28 !important;
            padding: 1mm !important;
            box-sizing: border-box !important;
        }

        /* 5. KOT 80mm Kitchen Ticket Print Mode */
        body.mode-kot-80 @page {
            size: 80mm auto;
            margin: 0mm !important;
        }
        body.mode-kot-80 .a4-sheet,
        body.mode-kot-80 .invoice-card,
        body.mode-kot-80 .pos-receipt-print-only {
            display: none !important;
        }
        body.mode-kot-80 .kot-receipt-print-only {
            display: block !important;
            width: 72mm !important;
            max-width: 72mm !important;
            margin: 0 auto !important;
            padding: 2mm 2mm !important;
            box-sizing: border-box !important;
        }
        body.mode-kot-80 .kot-receipt-print-only .kot-wrap {
            width: 72mm !important;
            max-width: 72mm !important;
            font-size: 11px !important;
            line-height: 1.35 !important;
            padding: 2mm !important;
            box-sizing: border-box !important;
        }
    }
</style>
@endpush

@section('admin-content')
@php
    $isPosOrTakeaway = in_array($order->order_type, ['dine_in', 'takeaway'])
        || !empty($order->restaurant_table_id)
        || \Illuminate\Support\Str::startsWith($order->order_number, 'POS-');
@endphp
<div class="invoice-page-wrapper">
    <div class="invoice-card">

        <!-- Top Screen Actions Header (Hidden on Print) -->
        <div class="invoice-screen-header no-print">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0; display:flex; align-items:center; gap:8px;">
                    Order Invoice #{{ $order->order_number }}
                    @if($isPosOrTakeaway)
                        <span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:0.72rem; padding:3px 8px; border-radius:6px; font-weight:700;">
                            <i class="fa-solid fa-cash-register"></i> POS / Dine-in
                        </span>
                    @else
                        <span class="badge" style="background:#fef3c7; color:#92400e; font-size:0.72rem; padding:3px 8px; border-radius:6px; font-weight:700;">
                            <i class="fa-solid fa-globe"></i> Web Delivery
                        </span>
                    @endif
                </h3>
                <div style="font-size:0.8rem; color:#64748b; margin-top:2px;">
                    Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items:center; flex-wrap:wrap;">
                @if($isPosOrTakeaway)
                    <!-- Primary: POS 58mm Thermal Receipt (Fits narrow paper rolls without cutting) -->
                    <button type="button" onclick="printPosInvoice('58mm')" class="btn-quick-pos" style="padding: 8px 14px; font-size: 0.85rem; cursor: pointer;" title="Print 58mm thermal receipt (২ ইঞ্চি / কোনো সাইড কাটবে না)">
                        <i class="fa-solid fa-receipt"></i> Print POS (58mm)
                    </button>
                    <button type="button" onclick="printPosInvoice('80mm')" class="btn-view-store" style="padding: 8px 12px; font-size: 0.85rem; background:#fff; border:1.5px solid #cbd5e1; color:#334155; cursor:pointer;" title="Print 80mm thermal receipt (৩ ইঞ্চি)">
                        <i class="fa-solid fa-receipt"></i> POS (80mm)
                    </button>
                    <button type="button" onclick="printA4Invoice()" class="btn-view-store" style="padding: 8px 12px; font-size: 0.85rem; background:#fff; border:1.5px solid #cbd5e1; color:#334155; cursor:pointer;" title="Print standard A4 sheet">
                        <i class="fa-solid fa-file-invoice"></i> A4 Sheet
                    </button>
                @else
                    <!-- Primary: A4 for Web Customer Orders -->
                    <button type="button" onclick="printA4Invoice()" class="btn-quick-pos" style="padding: 8px 16px; font-size: 0.85rem; cursor: pointer;" title="Print standard A4 sheet">
                        <i class="fa-solid fa-file-invoice"></i> Print A4 Invoice
                    </button>
                    <button type="button" onclick="printPosInvoice('58mm')" class="btn-view-store" style="padding: 8px 12px; font-size: 0.85rem; background:#fff; border:1.5px solid #cbd5e1; color:#334155; cursor:pointer;" title="Print 58mm thermal receipt (২ ইঞ্চি)">
                        <i class="fa-solid fa-receipt"></i> POS (58mm)
                    </button>
                    <button type="button" onclick="printPosInvoice('80mm')" class="btn-view-store" style="padding: 8px 12px; font-size: 0.85rem; background:#fff; border:1.5px solid #cbd5e1; color:#334155; cursor:pointer;" title="Print 80mm thermal receipt (৩ ইঞ্চি)">
                        <i class="fa-solid fa-receipt"></i> POS (80mm)
                    </button>
                @endif

                <!-- Kitchen View & KOT Print Options -->
                <button type="button" onclick="printKotInvoice('58mm')" class="btn-view-store" style="padding: 8px 12px; font-size: 0.85rem; background:#fff7ed; border:1.5px solid #f97316; color:#c2410c; font-weight:700; cursor:pointer;" title="Print Kitchen Order Ticket (58mm KOT)">
                    <i class="fa-solid fa-utensils"></i> Print KOT (58mm)
                </button>
                <button type="button" onclick="printKotInvoice('80mm')" class="btn-view-store" style="padding: 8px 12px; font-size: 0.85rem; background:#fff7ed; border:1.5px solid #fdba74; color:#c2410c; font-weight:700; cursor:pointer;" title="Print Kitchen Order Ticket (80mm KOT)">
                    <i class="fa-solid fa-fire-burner"></i> KOT (80mm)
                </button>
                <button type="button" onclick="openKotModal()" class="btn-view-store" style="padding: 8px 12px; font-size: 0.85rem; background:#f0fdf4; border:1.5px solid #86efac; color:#166534; font-weight:700; cursor:pointer;" title="View Kitchen Ticket (KOT) on screen">
                    <i class="fa-solid fa-eye"></i> Kitchen View (KOT)
                </button>

                <a href="{{ route('admin.orders.index') }}" class="btn-view-store" style="padding: 8px 14px; font-size: 0.85rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>

                <!-- Delete Order Button -->
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline; margin:0;" onsubmit="return confirm('⚠️ Are you sure you want to permanently delete order #{{ $order->order_number }}?\nThis cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 8px 12px; font-size: 0.85rem; background:#fee2e2; border:1.5px solid #fca5a5; color:#b91c1c; font-weight:700; border-radius:6px; cursor:pointer; display:inline-flex; align-items:center; gap:5px;" title="Delete this order">
                        <i class="fa-solid fa-trash-can"></i> Delete Order
                    </button>
                </form>
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
                    Payment: {{ ucfirst($order->payment_status) }} ({{ strtoupper($order->payment_method ?? 'Cash') }})
                </span>
            </div>

            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin:0;">
                @csrf
                <label style="font-size: 0.82rem; font-weight:700;">Update Status:</label>
                <select name="order_status" class="form-control" style="padding: 6px 12px; font-size: 0.82rem; font-weight:600; min-width:140px;" title="Order Status">
                    @foreach($orderStatuses as $statusItem)
                        <option value="{{ $statusItem->slug }}" {{ $order->order_status === $statusItem->slug ? 'selected' : '' }}>
                            {{ $statusItem->name }}
                        </option>
                    @endforeach
                    @if(!$orderStatuses->contains('slug', $order->order_status) && $order->order_status)
                        <option value="{{ $order->order_status }}" selected>{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</option>
                    @endif
                </select>

                @php
                    $curMethod = strtolower($order->payment_method ?? 'cash');
                @endphp
                <select name="payment_method" class="form-control" style="padding: 6px 12px; font-size: 0.82rem; font-weight:600; min-width:130px;" title="Payment Method">
                    <option value="cash" {{ $curMethod === 'cash' ? 'selected' : '' }}>💵 Cash</option>
                    <option value="bank" {{ $curMethod === 'bank' ? 'selected' : '' }}>🏦 Bank</option>
                    <option value="card" {{ $curMethod === 'card' ? 'selected' : '' }}>💳 Card</option>
                    <option value="bkash" {{ $curMethod === 'bkash' ? 'selected' : '' }}>📱 bKash</option>
                    <option value="nagad" {{ $curMethod === 'nagad' ? 'selected' : '' }}>📱 Nagad</option>
                    <option value="rocket" {{ $curMethod === 'rocket' ? 'selected' : '' }}>📱 Rocket</option>
                    @if(!in_array($curMethod, ['cash', 'bank', 'card', 'bkash', 'nagad', 'rocket']) && !empty($order->payment_method))
                        <option value="{{ $curMethod }}" selected>{{ ucfirst($order->payment_method) }}</option>
                    @endif
                </select>

                <select name="payment_status" id="orderPaymentStatusSelect" onchange="onPaymentStatusChange(this.value)" class="form-control" style="padding: 6px 12px; font-size: 0.82rem; font-weight:600;" title="Payment Status">
                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ $order->payment_status === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="due" {{ $order->payment_status === 'due' ? 'selected' : '' }}>Due (Unpaid)</option>
                </select>

                <input type="number" step="0.01" min="0" id="orderPaidAmountInput" name="paid_amount" value="{{ (float)$order->paid_amount }}" class="form-control" placeholder="Paid (৳)" style="width:110px; padding:6px 10px; font-size:0.82rem;" title="Paid Amount (৳)">

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
                $siteName = \App\Models\Setting::getValue('site_name', 'Italian Pizza Hut');
                $siteAddress = \App\Models\Setting::getValue('site_address', 'House 14, Road 11, Block D, Gulshan-2, Dhaka');
                $sitePhone = \App\Models\Setting::getValue('site_phone', '+880 1711-223344');
                $siteEmail = \App\Models\Setting::getValue('site_email', 'contact@italianpizzahut.com');
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
                    <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method ?? 'Cash') }}</p>
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
                    @if($order->vat_tax > 0)
                        <div>• Prices include applicable VAT / Tax.</div>
                    @endif
                    <div>• Please preserve this invoice for any verification or customer queries.</div>
                    <div>• Hotline support: {{ $sitePhone }}</div>
                    <div style="margin-top: 10px; font-weight: 700; color: var(--admin-primary); font-size: 0.85rem;">
                        Thank you for dining with Italian Pizza Hut!
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
                    @if($order->discount > 0)
                        <div class="calc-row discount">
                            <span>Discount (ছাড়):</span>
                            <strong>-৳ {{ number_format($order->discount) }}</strong>
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
                    @if($order->vat_tax > 0)
                    <div class="calc-row">
                        <span>VAT / Tax:</span>
                        <strong>৳ {{ number_format($order->vat_tax) }}</strong>
                    </div>
                    @endif
                    <div class="calc-grand-total">
                        <span>Grand Total:</span>
                        <span>৳ {{ number_format($order->total_amount) }}</span>
                    </div>
                    <div class="calc-row" style="margin-top:6px; font-size:0.9rem;">
                        <span>Paid ({{ strtoupper($order->payment_method ?? 'Cash') }}):</span>
                        <strong style="color:#16a34a;">৳ {{ number_format($order->paid_amount, 2) }}</strong>
                    </div>
                    @if($order->due_amount > 0)
                        <div class="calc-row" style="color:#dc2626; font-weight:800; font-size:0.95rem; background:#fee2e2; padding:4px 8px; border-radius:4px; margin-top:4px;">
                            <span>Due Balance:</span>
                            <strong style="color:#dc2626;">৳ {{ number_format($order->due_amount, 2) }}</strong>
                        </div>
                    @endif
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

<!-- ============================================================== -->
<!-- POS THERMAL RECEIPT CONTAINER (58mm / 80mm Adaptive)          -->
<!-- ============================================================== -->
<div id="posThermalReceiptContainer" class="pos-receipt-print-only">
    @php
        $siteName = \App\Models\Setting::getValue('site_name', 'Italian Pizza Hut');
        $siteAddress = \App\Models\Setting::getValue('site_address', 'House 14, Road 11, Block D, Gulshan-2, Dhaka');
        $sitePhone = \App\Models\Setting::getValue('site_phone', '+880 1711-223344');
        $isPaid = ($order->payment_status === 'paid');
    @endphp
    <div class="thermal-receipt-wrap" style="width: 48mm; max-width: 48mm; margin: 0 auto; padding: 1mm 1mm; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #000; font-size: 10px; line-height: 1.28; background: #fff; box-sizing: border-box;">
        <div style="text-align: center; margin-bottom: 5px;">
            <h2 style="margin: 0; font-size: 14px; font-weight: 900; letter-spacing: 0.5px; text-transform: uppercase; color: #000;">{{ $siteName }}</h2>
            <div style="font-size: 8.5px; margin-top: 1px; text-transform: uppercase; font-weight: 700; color: #000;">Authentic Italian Oven & Dine</div>
            <div style="font-size: 8.5px; margin-top: 1px; color: #000; line-height: 1.2;">{{ $siteAddress }}</div>
            <div style="font-size: 8.5px; font-weight: 700; color: #000;">Hotline: {{ $sitePhone }}</div>
        </div>

        <div style="border-top: 1.5px dashed #000; margin: 4px 0;"></div>

        <div style="margin: 3px 0; font-size: 9.5px; color: #000;">
            @if($order->restaurant_table_id && $order->table)
            <div style="border: 1.5px solid #000; padding: 3px; text-align: center; margin-bottom: 4px; border-radius: 4px; color: #000;">
                <span style="font-weight: 900; font-size: 12.5px; letter-spacing: 0.5px;">TABLE: {{ $order->table->table_number }}</span>
            </div>
            @endif
            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                <span>Invoice:</span>
                <strong style="color: #000; white-space: nowrap;">#{{ $order->order_number }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                <span>Date & Time:</span>
                <span style="color: #000; white-space: nowrap;">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Type:</span>
                <span style="text-transform: capitalize; color: #000;">{{ str_replace('_', ' ', $order->order_type) }}</span>
            </div>
            @if($order->customer_name && !in_array(strtolower($order->customer_name), ['walk-in', 'dine-in']) && !Str::startsWith($order->customer_name, 'Dine-in ('))
            <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                <span>Customer:</span>
                <strong style="color: #000; text-align: right; word-break: break-word;">{{ $order->customer_name }}</strong>
            </div>
            @endif
            @if($order->customer_phone && $order->customer_phone !== 'Walk-in')
            <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                <span>Phone:</span>
                <span style="color: #000; white-space: nowrap;">{{ $order->customer_phone }}</span>
            </div>
            @endif
            @if($order->customer_address && $order->order_type === 'delivery')
            <div style="margin-top: 1px;">
                <span>Address:</span>
                <span style="color: #000; word-break: break-word;">{{ $order->customer_address }}</span>
            </div>
            @endif
        </div>

        <div style="border-top: 1px dashed #000; margin: 4px 0;"></div>

        <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: 9.5px; margin-bottom: 2px; text-transform: uppercase; color: #000;">
            <span>Qty & Item</span>
            <span>Total</span>
        </div>
        <div style="border-top: 1px solid #000; margin-bottom: 4px;"></div>

        <div style="margin-bottom: 4px;">
            @foreach($order->items as $item)
                @php
                    $vars = [];
                    if ($item->size_name && strtolower($item->size_name) !== 'standard') $vars[] = $item->size_name;
                    if ($item->crust_name && strtolower($item->crust_name) !== 'standard') $vars[] = $item->crust_name;
                @endphp
                <div style="margin-bottom: 4px; padding-bottom: 3px; border-bottom: 1px dotted #000;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; font-weight: 800; font-size: 10px; color: #000;">
                        <span style="flex: 1; padding-right: 4px; word-break: break-word;">{{ $item->quantity }}x {{ $item->product_name }}</span>
                        <span style="white-space: nowrap; margin-left: auto;">৳{{ number_format($item->total_price) }}</span>
                    </div>
                    @if(count($vars) > 0)
                        <div style="font-size: 8.5px; font-weight: 700; color: #000; padding-left: 6px;">{{ implode(' | ', $vars) }}</div>
                    @endif
                    @if($item->addons && $item->addons->isNotEmpty())
                        @foreach($item->addons as $addon)
                            <div style="font-size: 8.5px; font-weight: 700; color: #000; padding-left: 6px;">+ {{ $addon->add_on_name }} (৳{{ number_format($addon->price) }})</div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>

        <div style="border-top: 1px dashed #000; margin: 4px 0;"></div>

        <div style="font-size: 9.5px; color: #000;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                <span>Subtotal:</span>
                <strong style="color: #000; white-space: nowrap;">৳ {{ number_format($order->subtotal) }}</strong>
            </div>
            @if($order->coupon_discount > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                <span>Coupon ({{ $order->coupon_code }}):</span>
                <span style="white-space: nowrap;">-৳ {{ number_format($order->coupon_discount) }}</span>
            </div>
            @endif
            @if($order->discount > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                <span>Discount:</span>
                <strong style="color: #000; white-space: nowrap;">-৳ {{ number_format($order->discount) }}</strong>
            </div>
            @endif
            @if($order->points_discount > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                <span>Points Discount:</span>
                <span style="white-space: nowrap;">-৳ {{ number_format($order->points_discount) }}</span>
            </div>
            @endif
            @if($order->delivery_charge > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                <span>Delivery:</span>
                <span style="white-space: nowrap;">৳ {{ number_format($order->delivery_charge) }}</span>
            </div>
            @endif
            @if($order->vat_tax > 0)
            <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                <span>VAT / Tax:</span>
                <span style="white-space: nowrap;">৳ {{ number_format($order->vat_tax) }}</span>
            </div>
            @endif

            <div style="border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; padding: 3px 0; margin: 4px 0; display: flex; justify-content: space-between; align-items: center; font-weight: 900; color: #000;">
                <span style="font-size: 11px;">TOTAL:</span>
                <span style="font-size: 13px; white-space: nowrap;">৳ {{ number_format($order->total_amount) }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                <span>Method:</span>
                <span style="text-transform: uppercase; font-weight: 700; white-space: nowrap;">{{ $order->payment_method ?? 'Cash' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                <span>Paid Amount:</span>
                <strong style="color: #000; white-space: nowrap;">৳ {{ number_format($order->paid_amount) }}</strong>
            </div>
            @if($order->due_amount > 0)
            <div style="display: flex; justify-content: space-between; margin-top: 2px; border: 1.5px solid #000; padding: 2px 3px; border-radius: 3px; color: #000;">
                <span style="font-weight: 900;">DUE BALANCE:</span>
                <strong style="font-weight: 900; white-space: nowrap;">৳ {{ number_format($order->due_amount) }}</strong>
            </div>
            @endif
            <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                <span>Status:</span>
                <strong style="text-transform: uppercase; color: #000; white-space: nowrap;">{{ $isPaid ? 'PAID' : ($order->due_amount > 0 && $order->paid_amount > 0 ? 'PARTIAL' : 'DUE') }}</strong>
            </div>
        </div>

        <div style="border-top: 1.5px dashed #000; margin: 6px 0 3px 0;"></div>
        <div style="text-align: center; font-size: 8.5px; color: #000; margin-top: 3px; line-height: 1.25;">
            <div style="font-weight: 800;">Thank you for dining with Italian Pizza Hut!</div>
            <div>Please visit again!</div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- KITCHEN ORDER TICKET (KOT) THERMAL PRINT BLOCK -->
<!-- ============================================== -->
<div class="kot-receipt-print-only">
    @include('admin.orders.partials.kot_content', ['order' => $order])
</div>

<!-- ============================================== -->
<!-- KITCHEN VIEW (KOT) ON-SCREEN MODAL PREVIEW     -->
<!-- ============================================== -->
<div id="kotModal" class="no-print" style="display:none; position:fixed; z-index:99999; left:0; top:0; width:100%; height:100%; background:rgba(15,23,42,0.65); backdrop-filter:blur(3px); justify-content:center; align-items:center; padding:16px;">
    <div style="background:#ffffff; width:100%; max-width:440px; border-radius:12px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.3); overflow:hidden; max-height:92vh; display:flex; flex-direction:column;">
        <!-- Modal Header -->
        <div style="padding:14px 18px; background:#1e293b; color:#ffffff; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-utensils" style="color:#f97316;"></i>
                <strong style="font-size:0.95rem;">Kitchen Order Ticket (KOT)</strong>
            </div>
            <button type="button" onclick="closeKotModal()" style="background:transparent; border:none; color:#cbd5e1; font-size:1.4rem; cursor:pointer; padding:0 4px; line-height:1;">&times;</button>
        </div>

        <!-- Modal Toolbar -->
        <div style="padding:10px 16px; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; gap:8px; flex-wrap:wrap;">
            <span style="font-size:0.8rem; color:#64748b; font-weight:600;">Print to Kitchen:</span>
            <div style="display:flex; gap:6px;">
                <button type="button" onclick="printKotInvoice('58mm')" style="background:#f97316; color:#ffffff; border:none; padding:6px 12px; border-radius:6px; font-size:0.8rem; font-weight:700; cursor:pointer;">
                    <i class="fa-solid fa-print"></i> 58mm KOT
                </button>
                <button type="button" onclick="printKotInvoice('80mm')" style="background:#ea580c; color:#ffffff; border:none; padding:6px 12px; border-radius:6px; font-size:0.8rem; font-weight:700; cursor:pointer;">
                    <i class="fa-solid fa-print"></i> 80mm KOT
                </button>
            </div>
        </div>

        <!-- Modal Body (The KOT Ticket Preview) -->
        <div style="padding:16px; overflow-y:auto; flex:1; background:#f1f5f9; display:flex; justify-content:center;">
            <div style="background:#ffffff; width:100%; max-width:340px; padding:16px 14px; border-radius:8px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); border:1px solid #cbd5e1;">
                @include('admin.orders.partials.kot_content', ['order' => $order])
            </div>
        </div>

        <!-- Modal Footer -->
        <div style="padding:10px 16px; background:#f8fafc; border-top:1px solid #e2e8f0; text-align:right;">
            <button type="button" onclick="closeKotModal()" style="background:#64748b; color:#fff; border:none; padding:6px 16px; border-radius:6px; font-size:0.82rem; cursor:pointer;">Close</button>
        </div>
    </div>
</div>
@endsection

@push('admin-scripts')
<script>
    const defaultPrintMode = '{{ $isPosOrTakeaway ? "pos-58" : "a4" }}';
    document.body.classList.add('mode-' + defaultPrintMode);

    function onPaymentStatusChange(status) {
        const paidInput = document.getElementById('orderPaidAmountInput');
        if (!paidInput) return;
        const totalAmount = {{ (float)$order->total_amount }};
        if (status === 'paid') {
            paidInput.value = totalAmount;
        } else if (status === 'due' || status === 'pending') {
            paidInput.value = 0;
        }
    }

    function printPosInvoice(size = '58mm') {
        document.body.classList.remove('mode-a4', 'mode-pos', 'mode-pos-58', 'mode-pos-80', 'mode-kot-58', 'mode-kot-80');
        const modeClass = (size === '80mm') ? 'mode-pos-80' : 'mode-pos-58';
        document.body.classList.add(modeClass);
        window.print();
    }

    function printA4Invoice() {
        document.body.classList.remove('mode-pos', 'mode-pos-58', 'mode-pos-80', 'mode-kot-58', 'mode-kot-80');
        document.body.classList.add('mode-a4');
        window.print();
    }

    function printKotInvoice(size = '58mm') {
        document.body.classList.remove('mode-a4', 'mode-pos', 'mode-pos-58', 'mode-pos-80', 'mode-kot-58', 'mode-kot-80');
        const modeClass = (size === '80mm') ? 'mode-kot-80' : 'mode-kot-58';
        document.body.classList.add(modeClass);
        window.print();
    }

    function openKotModal() {
        const modal = document.getElementById('kotModal');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function closeKotModal() {
        const modal = document.getElementById('kotModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('kotModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    window.onafterprint = function() {
        document.body.classList.remove('mode-pos', 'mode-pos-58', 'mode-pos-80', 'mode-kot-58', 'mode-kot-80', 'mode-a4');
        document.body.classList.add('mode-' + defaultPrintMode);
    };
</script>
@endpush
