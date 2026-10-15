@extends('layouts.admin')

@section('title', 'Manage Orders | Italian Pizza Admin')
@section('page-title', 'Orders Management')

@section('admin-content')
<div class="card-panel">
    <!-- Header with Filters & Create Button -->
    <div class="card-panel-header" style="flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.orders.index') }}" class="btn-view-store {{ !request('type') ? 'active' : '' }}" style="{{ !request('type') ? 'background: #0284c7; color: #fff;' : '' }}">
                All Orders
            </a>
            <a href="{{ route('admin.orders.index') }}?type=delivery" class="btn-view-store" style="{{ request('type') == 'delivery' ? 'background: #0284c7; color: #fff;' : '' }}">
                <i class="fa-solid fa-motorcycle"></i> Delivery
            </a>
            <a href="{{ route('admin.orders.index') }}?type=takeaway" class="btn-view-store" style="{{ request('type') == 'takeaway' ? 'background: #0284c7; color: #fff;' : '' }}">
                <i class="fa-solid fa-bag-shopping"></i> Takeaway
            </a>
            <a href="{{ route('admin.orders.index') }}?type=dine_in" class="btn-view-store" style="{{ request('type') == 'dine_in' ? 'background: #0284c7; color: #fff;' : '' }}">
                <i class="fa-solid fa-utensils"></i> Dine-in (Tables)
            </a>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: 6px; align-items: center;">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <select name="status" onchange="this.form.submit()" class="form-control" style="padding: 6px 10px; font-size: 0.85rem; border: 1px solid var(--border-light); border-radius: 6px; width: auto;">
                    <option value="">All Statuses</option>
                    @foreach($orderStatuses as $st)
                        <option value="{{ $st->slug }}" {{ request('status') == $st->slug ? 'selected' : '' }}>
                            {{ $st->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, name, phone..." class="form-control" style="padding: 6px 12px; font-size: 0.85rem; border: 1px solid var(--border-light); border-radius: 6px;">
                <button type="submit" class="btn-view-store">Search</button>
            </form>

            <a href="{{ route('admin.order-statuses.index') }}" class="btn-view-store" title="Manage dynamic order statuses">
                <i class="fa-solid fa-tags"></i> Statuses
            </a>

            <a href="{{ route('admin.orders.create') }}" class="btn-quick-pos">
                <i class="fa-solid fa-plus"></i> Walk-in Order
            </a>
        </div>
    </div>

    <!-- Orders Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date / Time</th>
                    <th>Type</th>
                    <th>Customer / Table</th>
                    <th>Items</th>
                    <th>Total (৳)</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $ord)
                    <tr>
                        <td>
                            <strong>#{{ $ord->order_number }}</strong>
                        </td>
                        <td>
                            <div style="font-size: 0.8rem; color: #475569;">{{ $ord->created_at->format('d M Y, h:i A') }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 700; font-size: 0.78rem; text-transform: uppercase;">
                                @if($ord->order_type === 'delivery')
                                    <i class="fa-solid fa-motorcycle"></i> Delivery
                                @elseif($ord->order_type === 'takeaway')
                                    <i class="fa-solid fa-bag-shopping"></i> Takeaway
                                @else
                                    <i class="fa-solid fa-utensils"></i> Dine-in
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($ord->order_type === 'dine_in' && $ord->table)
                                <strong>{{ $ord->table->table_number }}</strong>
                            @else
                                <strong>{{ $ord->customer_name ?: 'Guest' }}</strong>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $ord->customer_phone }}</div>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.82rem;">{{ $ord->items->count() }} item(s)</div>
                        </td>
                        <td>
                            <strong style="color: var(--admin-primary); font-size: 0.95rem;">
                                ৳ {{ number_format($ord->total_amount) }}
                            </strong>
                        </td>
                        <td>
                            <span class="badge {{ $ord->payment_status === 'paid' ? 'badge-completed' : 'badge-pending' }}">
                                {{ ucfirst($ord->payment_status) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $curStatusObj = $orderStatuses->firstWhere('slug', $ord->order_status)
                                    ?? \App\Models\OrderStatus::findBySlugOrName($ord->order_status);
                                $stBgColor = $curStatusObj->bg_color ?? '#f1f5f9';
                                $stColor = $curStatusObj->color ?? '#334155';
                            @endphp
                            <form action="{{ route('admin.orders.updateStatus', $ord->id) }}" method="POST" style="display:inline-block; margin:0;">
                                @csrf
                                <div style="position:relative; display:inline-block;">
                                    <select name="order_status" onchange="this.form.submit()" style="padding: 5px 24px 5px 12px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; border: 1.5px solid {{ $stColor }}40; background-color: {{ $stBgColor }}; color: {{ $stColor }}; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; box-shadow: 0 1px 3px rgba(0,0,0,0.06); transition: all 0.2s;">
                                        @foreach($orderStatuses as $statusItem)
                                            <option value="{{ $statusItem->slug }}" {{ $ord->order_status === $statusItem->slug ? 'selected' : '' }} style="background:#fff; color:#0f172a; font-weight:600;">
                                                ● {{ $statusItem->name }}
                                            </option>
                                        @endforeach
                                        @if(!$orderStatuses->contains('slug', $ord->order_status) && $ord->order_status)
                                            <option value="{{ $ord->order_status }}" selected style="background:#fff; color:#0f172a; font-weight:600;">
                                                ● {{ ucfirst(str_replace('_', ' ', $ord->order_status)) }}
                                            </option>
                                        @endif
                                    </select>
                                    <i class="fa-solid fa-chevron-down" style="position:absolute; right:9px; top:50%; transform:translateY(-50%); font-size:0.65rem; color:{{ $stColor }}; pointer-events:none;"></i>
                                </div>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn-view-store" style="padding: 4px 10px; font-size: 0.75rem;">
                                View Details <i class="fa-solid fa-angle-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px; color: #94a3b8;">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
