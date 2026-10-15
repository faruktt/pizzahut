@extends('layouts.admin')

@section('title', 'Order Details #' . $order->order_number . ' | Italian Pizza')
@section('page-title', 'Order #' . $order->order_number)

@section('admin-content')
<div style="max-width: 900px; margin: 0 auto;">
    <div class="card-panel">
        <div class="card-panel-header">
            <div>
                <h3 class="card-panel-title">Order #{{ $order->order_number }}</h3>
                <div style="font-size:0.8rem; color:#64748b;">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="window.print()" class="btn-view-store"><i class="fa-solid fa-print"></i> Print Invoice</button>
                <a href="{{ route('admin.orders.index') }}" class="btn-view-store"><i class="fa-solid fa-arrow-left"></i> Back</a>
            </div>
        </div>

        <div class="card-panel-body">
            <!-- Top Status Bar & Quick Update Form -->
            <div style="background: #f8fafc; border: 1px solid var(--border-light); border-radius: 8px; padding: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <span style="font-size: 0.8rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Current Status:</span>
                    <span class="badge badge-{{ $order->order_status }}" style="margin-left: 6px;">
                        {{ ucfirst($order->order_status) }}
                    </span>
                    <span class="badge {{ $order->payment_status === 'paid' ? 'badge-completed' : 'badge-pending' }}" style="margin-left: 6px;">
                        Pay: {{ ucfirst($order->payment_status) }}
                    </span>
                </div>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:flex; gap:8px; align-items:center;">
                    @csrf
                    <label style="font-size: 0.82rem; font-weight:700;">Update Status:</label>
                    <select name="order_status" class="form-control" style="padding: 6px 12px; font-size: 0.82rem;">
                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="preparing" {{ $order->order_status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ $order->order_status === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="served" {{ $order->order_status === 'served' ? 'selected' : '' }}>Served</option>
                        <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <select name="payment_status" class="form-control" style="padding: 6px 12px; font-size: 0.82rem;">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>

                    <button type="submit" class="btn-quick-pos" style="padding: 6px 14px; font-size: 0.82rem;">
                        Update
                    </button>
                </form>
            </div>

            <!-- Customer & Order Details Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: 8px; padding: 16px;">
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Customer / Dining Details</h4>
                    <p style="font-size: 0.85rem; margin-bottom: 4px;"><strong>Name:</strong> {{ $order->customer_name ?: 'Guest' }}</p>
                    <p style="font-size: 0.85rem; margin-bottom: 4px;"><strong>Phone:</strong> {{ $order->customer_phone ?: 'N/A' }}</p>
                    @if($order->order_type === 'dine_in')
                        <p style="font-size: 0.85rem; margin-bottom: 4px;"><strong>Table:</strong> {{ $order->table->table_number ?? 'N/A' }}</p>
                    @else
                        <p style="font-size: 0.85rem; margin-bottom: 4px;"><strong>Delivery Address:</strong> {{ $order->customer_address ?: 'N/A' }}</p>
                    @endif
                </div>

                <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: 8px; padding: 16px;">
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Order Details</h4>
                    <p style="font-size: 0.85rem; margin-bottom: 4px;"><strong>Type:</strong> {{ strtoupper($order->order_type) }}</p>
                    <p style="font-size: 0.85rem; margin-bottom: 4px;"><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                    @if($order->special_instructions)
                        <p style="font-size: 0.85rem; margin-bottom: 4px;"><strong>Instructions:</strong> {{ $order->special_instructions }}</p>
                    @endif
                </div>
            </div>

            <!-- Items Ordered -->
            <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 12px;">Items Ordered</h4>
            <div style="border: 1px solid var(--border-light); border-radius: 8px; overflow: hidden; margin-bottom: 20px;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Size / Crust</th>
                            <th>Add-ons</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product_name }}</strong>
                                    @if($item->combo_details)
                                        <div style="font-size:0.75rem; color:var(--admin-primary); font-weight:700; margin-top:2px;">
                                            <i class="fa-solid fa-pizza-slice" style="margin-right:4px;"></i> Combo: {{ $item->combo_details }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->size_name }} • {{ $item->crust_name }}</td>
                                <td>
                                    @if($item->addons->isNotEmpty())
                                        <span style="color:#0284c7; font-size:0.8rem;">
                                            {{ $item->addons->pluck('add_on_name')->join(', ') }}
                                        </span>
                                    @else
                                        <span style="color:#94a3b8;">None</span>
                                    @endif
                                </td>
                                <td>৳ {{ number_format($item->unit_price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong style="color:var(--admin-primary);">৳ {{ number_format($item->total_price) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Financial Summary -->
            <div style="max-width: 340px; margin-left: auto; background: #f8fafc; border: 1px solid var(--border-light); border-radius: 8px; padding: 16px;">
                <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:6px;">
                    <span>Subtotal:</span>
                    <strong>৳ {{ number_format($order->subtotal) }}</strong>
                </div>
                @if($order->coupon_discount > 0)
                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:6px; color:#16a34a;">
                        <span><i class="fa-solid fa-ticket" style="margin-right:4px;"></i> Coupon ({{ $order->coupon_code }}):</span>
                        <strong>-৳ {{ number_format($order->coupon_discount) }}</strong>
                    </div>
                @endif
                @if($order->points_discount > 0)
                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:6px; color:#16a34a;">
                        <span><i class="fa-solid fa-coins" style="margin-right:4px; color:#f59e0b;"></i> Points ({{ $order->points_used }} pts):</span>
                        <strong>-৳ {{ number_format($order->points_discount) }}</strong>
                    </div>
                @endif
                <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:6px;">
                    <span>Delivery Charge:</span>
                    <strong>৳ {{ number_format($order->delivery_charge) }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:6px;">
                    <span>VAT (5%):</span>
                    <strong>৳ {{ number_format($order->vat_tax) }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:1.2rem; font-weight:900; color:var(--admin-primary); border-top:2px dashed #cbd5e1; padding-top:8px; margin-top:8px;">
                    <span>Grand Total:</span>
                    <span>৳ {{ number_format($order->total_amount) }}</span>
                </div>
                @if($order->points_earned > 0)
                    <div style="margin-top:8px; font-size:0.78rem; color:#065f46; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:6px; padding:6px 8px; text-align:center;">
                        <i class="fa-solid fa-coins" style="color:#10b981;"></i> Customer earned +{{ $order->points_earned }} Reward Points
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
