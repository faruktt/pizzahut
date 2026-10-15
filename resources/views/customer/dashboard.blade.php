@extends('layouts.app')

@section('title', 'My Dashboard | Italian Pizza')

@push('styles')
<style>
    .customer-dashboard {
        padding: 32px 0 60px;
    }

    .dashboard-header {
        margin-bottom: 28px;
    }

    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .dashboard-subtitle {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s;
    }

    .stat-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #fff;
    }

    .stat-icon.orders { background: linear-gradient(135deg, #c8102e, #e62843); }
    .stat-icon.pending { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .stat-icon.completed { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-icon.spent { background: linear-gradient(135deg, #3b82f6, #60a5fa); }

    .stat-info {
        flex: 1;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Dashboard Sections */
    .dashboard-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .section-link {
        color: var(--brand-red);
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .section-link:hover {
        text-decoration: underline;
    }

    .section-body {
        padding: 0;
    }

    /* Orders Table */
    .order-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border-color);
        align-items: center;
        transition: background 0.15s;
    }

    .order-row:last-child {
        border-bottom: none;
    }

    .order-row:hover {
        background: #fafafa;
    }

    .order-id {
        font-weight: 700;
        color: var(--brand-red);
        font-size: 0.9rem;
    }

    .order-id small {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 400;
        margin-top: 2px;
    }

    .order-items {
        color: var(--text-dark);
        font-size: 0.85rem;
    }

    .order-items-count {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .order-total {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .order-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-pending { background: #fef3c7; color: #b45309; }
    .status-preparing { background: #e0f2fe; color: #0369a1; }
    .status-ready { background: #ede9fe; color: #6d28d9; }
    .status-served { background: #f3e8ff; color: #7e22ce; }
    .status-completed { background: #dcfce7; color: #15803d; }
    .status-cancelled { background: #fee2e2; color: #b91c1c; }
    .status-delivered { background: #dcfce7; color: #15803d; }

    .order-actions {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-dark);
    }

    .btn-outline:hover {
        background: var(--brand-red-light);
        border-color: var(--brand-red);
        color: var(--brand-red);
    }

    .empty-state {
        text-align: center;
        padding: 60px 24px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 16px;
        color: var(--border-color);
    }

    .empty-state h3 {
        font-size: 1.1rem;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        margin-bottom: 20px;
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .action-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 24px;
        text-align: center;
        transition: all 0.2s;
        text-decoration: none;
    }

    .action-card:hover {
        border-color: var(--brand-red);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .action-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: var(--brand-red-light);
        color: var(--brand-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 14px;
    }

    .action-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .action-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .order-row {
            grid-template-columns: 1fr 1fr;
            grid-template-areas:
                "id items"
                "total status"
                "actions actions";
        }
        .order-id { grid-area: id; }
        .order-items { grid-area: items; }
        .order-total { grid-area: total; }
        .order-status { grid-area: status; }
        .order-actions { grid-area: actions; justify-content: flex-start; }
    }

    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Profile & Security Section Styles */
    .profile-tabs-nav {
        display: flex;
        gap: 8px;
        padding: 14px 24px;
        background: #fdfdfd;
        border-bottom: 1px solid var(--border-color);
        overflow-x: auto;
    }

    .profile-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        font-family: inherit;
    }

    .profile-tab-btn i {
        font-size: 1rem;
        color: #888;
        transition: color 0.2s ease;
    }

    .profile-tab-btn:hover {
        background: #f3f4f6;
        color: var(--text-dark);
    }

    .profile-tab-btn:hover i {
        color: var(--brand-red);
    }

    .profile-tab-btn.active {
        background: #fff;
        color: var(--brand-red);
        border-color: #f0e1e3;
        box-shadow: 0 2px 8px rgba(200, 16, 46, 0.08);
    }

    .profile-tab-btn.active i {
        color: var(--brand-red);
    }

    .profile-tab-content {
        padding: 28px;
        background: #ffffff;
    }

    .profile-tab-pane {
        display: none;
    }

    .profile-tab-pane.active {
        display: block;
        animation: profileFadeIn 0.25s ease-out;
    }

    @keyframes profileFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Flash Alerts */
    .profile-alert {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 0.9rem;
        line-height: 1.45;
        position: relative;
    }

    .profile-alert.success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    .profile-alert.success .alert-icon {
        color: #10b981;
    }

    .profile-alert.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .profile-alert.error .alert-icon {
        color: #ef4444;
    }

    .profile-alert .alert-icon {
        font-size: 1.25rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .profile-alert .alert-content {
        flex: 1;
    }

    .profile-alert .alert-title {
        font-weight: 700;
        margin-bottom: 2px;
    }

    .profile-alert .alert-close {
        background: transparent;
        border: none;
        color: inherit;
        opacity: 0.6;
        font-size: 1.35rem;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
    }

    .profile-alert .alert-close:hover {
        opacity: 1;
    }

    /* Profile Header Card */
    .profile-header-card {
        display: flex;
        align-items: center;
        gap: 22px;
        padding: 24px;
        background: linear-gradient(135deg, #fffafa 0%, #ffffff 100%);
        border: 1px solid #fae8eb;
        border-radius: 14px;
        margin-bottom: 26px;
    }

    .profile-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .avatar-circle {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c8102e, #a50b24);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.85rem;
        font-weight: 800;
        box-shadow: 0 6px 18px rgba(200, 16, 46, 0.25);
        border: 3px solid #fff;
    }

    .avatar-badge-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 24px;
        height: 24px;
        background: #10b981;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    .profile-basic-info h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .profile-basic-info .profile-email {
        font-size: 0.9rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
    }

    .profile-meta-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #f3f4f6;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #4b5563;
    }

    .meta-pill.verified {
        background: #ecfdf5;
        color: #059669;
    }

    /* Security Header Card */
    .security-header-card {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 24px;
        background: linear-gradient(135deg, #fff7f7 0%, #ffffff 100%);
        border: 1px solid #fee2e2;
        border-radius: 14px;
        margin-bottom: 26px;
    }

    .security-header-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        background: linear-gradient(135deg, #c8102e, #8f0b1f);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(200, 16, 46, 0.22);
    }

    .security-header-text h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .security-header-text p {
        font-size: 0.88rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* Form Layout & Elements */
    .form-group-custom {
        margin-bottom: 22px;
    }

    .form-label-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.88rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .form-label-custom .req {
        color: var(--brand-red);
        margin-left: 2px;
    }

    .input-wrapper-custom {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-left {
        position: absolute;
        left: 14px;
        color: #9ca3af;
        font-size: 0.95rem;
        pointer-events: none;
        transition: color 0.2s ease;
        z-index: 2;
    }

    .input-field-custom {
        width: 100%;
        height: 48px;
        padding: 10px 42px 10px 42px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.95rem;
        color: var(--text-dark);
        background: #fafafa;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .input-field-custom.no-right-icon {
        padding-right: 14px;
    }

    .input-field-custom:focus {
        outline: none;
        background: #ffffff;
        border-color: var(--brand-red);
        box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.12);
    }

    .input-wrapper-custom:focus-within .input-icon-left {
        color: var(--brand-red);
    }

    .input-field-custom.is-invalid {
        border-color: #ef4444;
        background: #fffafa;
    }

    .input-field-custom.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }

    .toggle-pass-btn {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        border-radius: 6px;
        transition: color 0.15s, background 0.15s;
        z-index: 2;
    }

    .toggle-pass-btn:hover {
        color: var(--text-dark);
        background: #f1f2f4;
    }

    .form-hint {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-error-custom {
        font-size: 0.82rem;
        color: #dc2626;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Password Strength & Real-time Validation */
    .pass-strength-card {
        background: #fbfbfb;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 24px;
    }

    .strength-meter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #4b5563;
    }

    .strength-label-text {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.74rem;
        letter-spacing: 0.4px;
    }

    .strength-label-text.weak { color: #ef4444; }
    .strength-label-text.medium { color: #f59e0b; }
    .strength-label-text.strong { color: #10b981; }

    .strength-track {
        height: 6px;
        background: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .strength-bar-progress {
        height: 100%;
        width: 0%;
        border-radius: 3px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .strength-bar-progress.weak {
        width: 33%;
        background: #ef4444;
    }

    .strength-bar-progress.medium {
        width: 66%;
        background: #f59e0b;
    }

    .strength-bar-progress.strong {
        width: 100%;
        background: #10b981;
    }

    .pass-rules-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 8px;
        margin-top: 8px;
    }

    .rule-item {
        font-size: 0.78rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
    }

    .rule-item i {
        font-size: 0.8rem;
        color: #cbd5e1;
        transition: all 0.2s;
    }

    .rule-item.valid {
        color: #059669;
        font-weight: 600;
    }

    .rule-item.valid i {
        color: #10b981;
    }

    /* Action Buttons */
    .form-actions-bar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 18px;
        border-top: 1px solid #f1f2f4;
        margin-top: 10px;
    }

    .btn-profile-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, var(--brand-red), var(--brand-red-dark));
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(200, 16, 46, 0.25);
        transition: all 0.2s ease;
    }

    .btn-profile-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(200, 16, 46, 0.35);
        color: #ffffff;
    }

    .btn-profile-submit:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(200, 16, 46, 0.2);
    }

    @media (max-width: 768px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .profile-header-card,
        .security-header-card {
            flex-direction: column;
            text-align: center;
            padding: 20px 16px;
        }

        .profile-basic-info .profile-email {
            justify-content: center;
        }

        .profile-meta-pills {
            justify-content: center;
        }

        .profile-tab-content {
            padding: 20px 16px;
        }

        .profile-tabs-nav {
            padding: 10px 14px;
        }

        .profile-tab-btn {
            flex: 1;
            justify-content: center;
            padding: 10px 12px;
            font-size: 0.85rem;
        }

        .form-actions-bar {
            flex-direction: column-reverse;
        }

        .btn-profile-submit {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="customer-dashboard">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">My Dashboard</h1>
            <p class="dashboard-subtitle">Welcome back, <strong>{{ $user->name }}</strong>! Here's your overview.</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon orders"><i class="fa-solid fa-receipt"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['total_orders'] }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['pending_orders'] }}</div>
                    <div class="stat-label">Active Orders</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['completed_orders'] }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon spent"><i class="fa-solid fa-taka-sign"></i></div>
                <div class="stat-info">
                    <div class="stat-value">৳{{ number_format($stats['total_spent']) }}</div>
                    <div class="stat-label">Total Spent</div>
                </div>
            </div>
            <div class="stat-card" style="border-left: 3px solid #f59e0b;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);"><i class="fa-solid fa-coins"></i></div>
                <div class="stat-info">
                    <div class="stat-value" style="color: #b45309;">{{ number_format($user->reward_points ?? 0) }}</div>
                    <div class="stat-label">Reward Points (Worth ৳{{ number_format(\App\Models\Setting::getPointDiscount($user->reward_points ?? 0)) }})</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Quick Actions</h2>
            </div>
            <div class="section-body">
                <div class="quick-actions" style="padding: 24px;">
                    <a href="{{ route('home') }}" class="action-card">
                        <div class="action-icon"><i class="fa-solid fa-pizza-slice"></i></div>
                        <div class="action-title">Order Food</div>
                        <div class="action-desc">Browse menu and place new order</div>
                    </a>
                    <a href="{{ route('customer.orders') }}" class="action-card">
                        <div class="action-icon"><i class="fa-solid fa-list-check"></i></div>
                        <div class="action-title">View All Orders</div>
                        <div class="action-desc">Track and manage your orders</div>
                    </a>
                    <a href="#profile" class="action-card" onclick="document.getElementById('profile').scrollIntoView({behavior:'smooth'})">
                        <div class="action-icon"><i class="fa-solid fa-user-cog"></i></div>
                        <div class="action-title">Profile Settings</div>
                        <div class="action-desc">Update name, email, password</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Recent Orders</h2>
                <a href="{{ route('customer.orders') }}" class="section-link">View All <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="section-body">
                @if($recentOrders->isNotEmpty())
                    @foreach($recentOrders as $order)
                    <div class="order-row">
                        <div class="order-id">
                            #{{ $order->order_number }}
                            <small>{{ $order->created_at->format('M d, Y H:i') }}</small>
                        </div>
                        <div class="order-items">
                            @foreach($order->items->take(2) as $item)
                                <div>{{ $item->product_name }} (x{{ $item->quantity }})</div>
                            @endforeach
                            @if($order->items->count() > 2)
                                <div class="order-items-count">+{{ $order->items->count() - 2 }} more items</div>
                            @endif
                        </div>
                        <div class="order-total">৳{{ number_format($order->total_amount) }}</div>
                        <div class="d-flex align-items-center gap-8">
                            <span class="order-status status-{{ strtolower($order->order_status) }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-sm btn-outline">
                                <i class="fa-solid fa-eye"></i> Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fa-solid fa-receipt"></i>
                        <h3>No orders yet</h3>
                        <p>Start ordering delicious pizzas and track them here!</p>
                        <a href="{{ route('home') }}" class="btn-signin"><i class="fa-solid fa-pizza-slice"></i> Order Now</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Profile & Security Section -->
        <div class="dashboard-section" id="profile">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Account Settings</h2>
                </div>
            </div>
            <div class="section-body" style="padding: 0;">
                <div class="profile-tabs-nav">
                    <button type="button" class="profile-tab-btn active" data-tab="profile" id="tabBtnProfile">
                        <i class="fa-solid fa-user-pen"></i> Personal Profile
                    </button>
                    <button type="button" class="profile-tab-btn" data-tab="security" id="tabBtnSecurity">
                        <i class="fa-solid fa-shield-halved"></i> Password & Security
                    </button>
                </div>

                <div class="profile-tab-content">
                    {{-- Flash Success Message --}}
                    @if(session('success'))
                    <div class="profile-alert success" id="profileSuccessAlert">
                        <div class="alert-icon"><i class="fa-solid fa-circle-check"></i></div>
                        <div class="alert-content">
                            <div class="alert-title">Success</div>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                    @endif

                    {{-- General Validation Errors Alert --}}
                    @if($errors->any())
                    <div class="profile-alert error" id="profileErrorAlert">
                        <div class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                        <div class="alert-content">
                            <div class="alert-title">Update Failed</div>
                            <div>Please correct the errors in the form below.</div>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                    @endif

                    <!-- Profile Tab -->
                    <div class="profile-tab-pane active" id="tab-profile">
                        <div class="profile-header-card">
                            <div class="profile-avatar-wrap">
                                <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <div class="avatar-badge-icon" title="Active Customer"><i class="fa-solid fa-check"></i></div>
                            </div>
                            <div class="profile-basic-info">
                                <h3>{{ $user->name }}</h3>
                                <div class="profile-email">
                                    <i class="fa-regular fa-envelope"></i> {{ $user->email }}
                                </div>
                                <div class="profile-meta-pills">
                                    <span class="meta-pill verified"><i class="fa-solid fa-circle-check"></i> Verified Account</span>
                                    <span class="meta-pill" style="background:#fef3c7; color:#b45309; border-color:#fde68a;"><i class="fa-solid fa-coins" style="color:#d97706;"></i> {{ number_format($user->reward_points ?? 0) }} Reward Points</span>
                                    <span class="meta-pill"><i class="fa-regular fa-calendar"></i> Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Recent' }}</span>
                                    <span class="meta-pill"><i class="fa-solid fa-hashtag"></i> ID #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('customer.profile.update') }}" method="POST" class="profile-form">
                            @csrf
                            @method('PUT')
                            <div class="form-grid-2">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">
                                        <span>Full Name <span class="req">*</span></span>
                                    </label>
                                    <div class="input-wrapper-custom">
                                        <span class="input-icon-left"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" name="name" class="input-field-custom no-right-icon @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required placeholder="Enter your full name">
                                    </div>
                                    @error('name')
                                        <div class="form-error-custom"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group-custom">
                                    <label class="form-label-custom">
                                        <span>Email Address <span class="req">*</span></span>
                                    </label>
                                    <div class="input-wrapper-custom">
                                        <span class="input-icon-left"><i class="fa-regular fa-envelope"></i></span>
                                        <input type="email" name="email" class="input-field-custom no-right-icon @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required placeholder="your.email@example.com">
                                    </div>
                                    @error('email')
                                        <div class="form-error-custom"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">
                                    <span>Phone Number</span>
                                    <small style="color: #6b7280; font-weight: normal;">Optional</small>
                                </label>
                                <div class="input-wrapper-custom">
                                    <span class="input-icon-left"><i class="fa-solid fa-phone"></i></span>
                                    <input type="tel" name="phone" class="input-field-custom no-right-icon @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 01700000000">
                                </div>
                                @error('phone')
                                    <div class="form-error-custom"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-actions-bar">
                                <button type="submit" class="btn-profile-submit">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Profile Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Tab -->
                    <div class="profile-tab-pane" id="tab-security">
                        <div class="security-header-card">
                            <div class="security-header-icon">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div class="security-header-text">
                                <h3>Change Password</h3>
                                <p>Keep your account secure by using a strong password with letters, numbers, and symbols.</p>
                            </div>
                        </div>

                        <form action="{{ route('customer.password.update') }}" method="POST" class="profile-form" id="passwordForm">
                            @csrf
                            @method('PUT')
                            <div class="form-group-custom">
                                <label class="form-label-custom">
                                    <span>Current Password <span class="req">*</span></span>
                                </label>
                                <div class="input-wrapper-custom">
                                    <span class="input-icon-left"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="current_password" id="current_password" class="input-field-custom @error('current_password') is-invalid @enderror" required autocomplete="current-password" placeholder="Enter your current password">
                                    <button type="button" class="toggle-pass-btn" data-target="current_password" title="Toggle visibility">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="form-error-custom"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-grid-2">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">
                                        <span>New Password <span class="req">*</span></span>
                                    </label>
                                    <div class="input-wrapper-custom">
                                        <span class="input-icon-left"><i class="fa-solid fa-key"></i></span>
                                        <input type="password" name="password" id="new_password" class="input-field-custom @error('password') is-invalid @enderror" required minlength="6" autocomplete="new-password" placeholder="At least 6 characters">
                                        <button type="button" class="toggle-pass-btn" data-target="new_password" title="Toggle visibility">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="form-error-custom"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group-custom">
                                    <label class="form-label-custom">
                                        <span>Confirm New Password <span class="req">*</span></span>
                                    </label>
                                    <div class="input-wrapper-custom">
                                        <span class="input-icon-left"><i class="fa-solid fa-check-double"></i></span>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="input-field-custom @error('password_confirmation') is-invalid @enderror" required autocomplete="new-password" placeholder="Repeat new password">
                                        <button type="button" class="toggle-pass-btn" data-target="password_confirmation" title="Toggle visibility">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="form-error-custom"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="pass-strength-card" id="passStrengthBox" style="display: none;">
                                <div class="strength-meter-header">
                                    <span>Password Strength:</span>
                                    <span class="strength-label-text weak" id="strengthText">Weak</span>
                                </div>
                                <div class="strength-track">
                                    <div class="strength-bar-progress weak" id="strengthProgress"></div>
                                </div>
                                <div class="pass-rules-list">
                                    <div class="rule-item" id="ruleLength">
                                        <i class="fa-regular fa-circle-dot"></i> At least 6 characters
                                    </div>
                                    <div class="rule-item" id="ruleComplex">
                                        <i class="fa-regular fa-circle-dot"></i> Number or symbol
                                    </div>
                                    <div class="rule-item" id="ruleMatch">
                                        <i class="fa-regular fa-circle-dot"></i> Passwords match
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions-bar">
                                <button type="submit" class="btn-profile-submit">
                                    <i class="fa-solid fa-shield-check"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching function
        function switchTab(tabName) {
            document.querySelectorAll('.profile-tab-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.tab === tabName);
            });
            document.querySelectorAll('.profile-tab-pane').forEach(p => {
                p.classList.toggle('active', p.id === 'tab-' + tabName);
            });
        }

        document.querySelectorAll('.profile-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                switchTab(this.dataset.tab);
            });
        });

        // Auto-switch to security tab if there are password-related errors
        @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
            switchTab('security');
        @elseif(request()->get('tab') === 'security' || request()->is('#security'))
            switchTab('security');
        @endif

        // Toggle password show/hide
        document.querySelectorAll('.toggle-pass-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        });

        // Password strength & live validation
        const newPassInput = document.getElementById('new_password');
        const confirmPassInput = document.getElementById('password_confirmation');
        const strengthBox = document.getElementById('passStrengthBox');
        const strengthProgress = document.getElementById('strengthProgress');
        const strengthText = document.getElementById('strengthText');
        const ruleLength = document.getElementById('ruleLength');
        const ruleComplex = document.getElementById('ruleComplex');
        const ruleMatch = document.getElementById('ruleMatch');

        function updatePasswordStrength() {
            const val = newPassInput ? newPassInput.value : '';
            const confirmVal = confirmPassInput ? confirmPassInput.value : '';

            if (!val && !confirmVal) {
                if (strengthBox) strengthBox.style.display = 'none';
                return;
            }

            if (strengthBox) strengthBox.style.display = 'block';

            const hasMinLength = val.length >= 6;
            const hasComplexity = /[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val);
            const isMatch = val.length > 0 && val === confirmVal;

            // Update rule checklist
            setRuleStatus(ruleLength, hasMinLength);
            setRuleStatus(ruleComplex, hasComplexity);
            setRuleStatus(ruleMatch, isMatch);

            // Calculate strength score
            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            if (strengthProgress && strengthText) {
                strengthProgress.className = 'strength-bar-progress';
                strengthText.className = 'strength-label-text';

                if (score <= 2) {
                    strengthProgress.classList.add('weak');
                    strengthText.classList.add('weak');
                    strengthText.textContent = 'Weak';
                } else if (score <= 4) {
                    strengthProgress.classList.add('medium');
                    strengthText.classList.add('medium');
                    strengthText.textContent = 'Medium';
                } else {
                    strengthProgress.classList.add('strong');
                    strengthText.classList.add('strong');
                    strengthText.textContent = 'Strong';
                }
            }
        }

        function setRuleStatus(elem, isValid) {
            if (!elem) return;
            const icon = elem.querySelector('i');
            if (isValid) {
                elem.classList.add('valid');
                if (icon) {
                    icon.className = 'fa-solid fa-circle-check';
                }
            } else {
                elem.classList.remove('valid');
                if (icon) {
                    icon.className = 'fa-regular fa-circle-dot';
                }
            }
        }

        if (newPassInput) newPassInput.addEventListener('input', updatePasswordStrength);
        if (confirmPassInput) confirmPassInput.addEventListener('input', updatePasswordStrength);
    });
</script>
@endpush