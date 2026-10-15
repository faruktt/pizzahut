<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Portal | Italian Pizza & POS')</title>

    <!-- Google Fonts: Playfair Display (Headings) & Poppins (Body & UI) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

            <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php $adminFavicon = \App\Models\Setting::getValue('site_favicon'); @endphp
    @if($adminFavicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('uploads/' . $adminFavicon) }}">
    @endif

    <style>
        :root {
            --admin-primary: #e31837;
            --admin-primary-soft: rgba(227, 24, 55, 0.12);
            --sidebar-bg: #ffffff;
            --sidebar-border: #e2e8f0;
            --header-bg: #ffffff;
            --page-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-sub: #64748b;
            --border-light: #e2e8f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body, button, input, select, textarea, p, span, a, label, li, td, th {
            font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--page-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            line-height: 1.5;
        }

        h1, h2, h3, h4, h5, h6, .page-header-title, .card-title, .brand-logo-text strong {
            font-family: 'Playfair Display', Georgia, serif;
            letter-spacing: -0.2px;
        }

        a { text-decoration: none; color: inherit; }

        /* SIDEBAR - Clean Modern Light Theme */
        aside.admin-sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid var(--border-light, #e2e8f0);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 100;
            box-shadow: 2px 0 16px rgba(15, 23, 42, 0.04);
        }

        .sidebar-brand-box {
            padding: 16px 18px 0px 18px; /* Removed bottom padding and margin */
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: none;
            background: #ffffff;
        }

        .sidebar-logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #e31837 0%, #990011 100%);
            color: #fff;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
            box-shadow: 0 4px 12px rgba(227, 24, 55, 0.25);
            flex-shrink: 0;
        }

        .sidebar-brand-title {
            font-size: 1.02rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sidebar-brand-badge {
            font-size: 0.6rem;
            font-weight: 800;
            background: #fee2e2;
            color: #dc2626;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #fca5a5;
            letter-spacing: 0.5px;
        }

        .sidebar-brand-sub {
            font-size: 0.68rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-top: 1px;
        }

        .sidebar-nav {
            padding: 8px 12px 16px 12px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }

        .nav-section-title {
            font-size: 0.64rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.1px;
            padding: 10px 10px 3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.84rem;
            font-weight: 600;
            color: #475569;
            transition: all 0.18s ease;
            position: relative;
        }

        .nav-item-link i {
            width: 18px;
            text-align: center;
            font-size: 0.92rem;
            color: #64748b;
            transition: color 0.18s, transform 0.18s;
        }

        .nav-item-link:hover {
            background: #f8fafc;
            color: #0f172a;
            transform: translateX(2px);
        }

        .nav-item-link:hover i {
            color: #0f172a;
        }

        .nav-item-link.active {
            background: #fef2f2;
            color: #dc2626;
            font-weight: 700;
            border-left: 3px solid #dc2626;
            padding-left: 9px;
        }

        .nav-item-link.active i {
            color: #dc2626;
        }

        .nav-item-link.pos-highlight {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-weight: 700;
        }

        .nav-item-link.pos-highlight i {
            color: #16a34a;
        }

        .nav-item-link.pos-highlight:hover {
            background: #dcfce7;
            color: #166534;
            border-color: #86efac;
        }

        .nav-item-link.pos-highlight.active {
            background: #16a34a;
            color: #ffffff;
            border-color: #16a34a;
            border-left: 3px solid #14532d;
            padding-left: 9px;
        }
        .nav-item-link.pos-highlight.active i {
            color: #ffffff;
        }

        /* Sub-menu style */
        .nav-sub-menu { display: none; padding-left: 24px; flex-direction: column; gap: 2px; }
        .nav-sub-menu.open { display: flex; }
        .nav-item-link.has-sub::after { content: '\f107'; font-family: 'Font Awesome 6 Free'; font-weight: 900; margin-left: auto; font-size: 0.75rem; transition: transform 0.2s; }
        .nav-item-link.has-sub.open::after { transform: rotate(180deg); }

        .sidebar-footer {
            padding: 12px 14px;
            border-top: 1px solid #f1f5f9;
            background: #ffffff;
        }

        .admin-profile-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 10px;
        }

        /* MAIN WRAPPER */
        .admin-main-wrap {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* HEADER */
        header.admin-header {
            background: var(--header-bg);
            border-bottom: 1px solid var(--border-light);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky; top: 0; z-index: 90;
        }

        .header-title-left h1 { font-size: 1.15rem; font-weight: 800; color: var(--text-main); }

        .header-actions-right { display: flex; align-items: center; gap: 12px; }

        .btn-view-store {
            display: inline-flex; align-items: center; gap: 6px;
            background: #f1f5f9; color: #334155;
            font-size: 0.8rem; font-weight: 700;
            padding: 7px 12px; border-radius: 6px;
            border: 1px solid var(--border-light); transition: background 0.2s;
        }
        .btn-view-store:hover { background: #e2e8f0; }

        .btn-quick-pos {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--admin-primary); color: #fff;
            font-size: 0.8rem; font-weight: 700;
            padding: 7px 14px; border-radius: 6px;
            box-shadow: 0 2px 8px rgba(200,16,46,0.25); transition: background 0.2s;
        }
        .btn-quick-pos:hover { background: #a50b24; }

        /* Admin Header User Dropdown */
        .admin-user-dropdown-wrap {
            position: relative;
        }

        .admin-user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid var(--border-light, #e2e8f0);
            padding: 4px 12px 4px 6px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            font-family: inherit;
        }
        .admin-user-btn:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .admin-avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.85rem;
            flex-shrink: 0;
            border: 1.5px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .admin-avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-user-info-text {
            text-align: left;
            line-height: 1.2;
        }
        .admin-user-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1e293b;
        }
        .admin-user-role {
            font-size: 0.68rem;
            color: #64748b;
            font-weight: 600;
        }
        .admin-chevron {
            font-size: 0.72rem;
            color: #94a3b8;
            transition: transform 0.2s;
            margin-left: 2px;
        }
        .admin-user-dropdown-wrap.open .admin-chevron {
            transform: rotate(180deg);
        }

        .admin-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 250px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            padding: 8px;
            z-index: 1000;
            display: none;
            animation: dropdownFade 0.2s ease;
        }
        .admin-user-dropdown-wrap.open .admin-dropdown-menu {
            display: block;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-user-header {
            padding: 12px;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dropdown-header-name {
            font-size: 0.86rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .dropdown-header-email {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 2px;
            word-break: break-all;
        }

        .admin-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            transition: all 0.15s;
            text-decoration: none;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }
        .admin-dropdown-item:hover {
            background: #f1f5f9;
            color: var(--admin-primary, #c8102e);
        }
        .admin-dropdown-item i {
            font-size: 0.9rem;
            color: #64748b;
            width: 18px;
            text-align: center;
        }
        .admin-dropdown-item:hover i {
            color: var(--admin-primary, #c8102e);
        }

        .admin-dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 6px 0;
        }

        .admin-dropdown-item.text-danger:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        .admin-dropdown-item.text-danger:hover i {
            color: #dc2626;
        }

        /* PAGE BODY */
        .admin-page-content { padding: 28px; flex: 1; }

        /* Alerts */
        .alert-box {
            padding: 11px 16px; border-radius: 8px;
            font-size: 0.85rem; font-weight: 600;
            margin-bottom: 20px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

        /* Card Panel */
        .card-panel {
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex; justify-content: space-between; align-items: center;
        }

        .card-panel-title { font-size: 1rem; font-weight: 800; color: var(--text-main); }
        .card-panel-body { padding: 20px; }

        /* Table */
        .admin-table { width: 100%; border-collapse: collapse; font-size: 0.86rem; }
        .admin-table th {
            text-align: left; padding: 11px 14px;
            background: #f8fafc; color: var(--text-sub);
            font-weight: 700; font-size: 0.75rem;
            text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-light);
        }
        .admin-table td {
            padding: 12px 14px; border-bottom: 1px solid #f1f5f9;
            color: var(--text-main); vertical-align: middle;
        }
        .admin-table tr:hover td { background: #f8fafc; }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-preparing { background: #e0f2fe; color: #0369a1; }
        .badge-ready { background: #ede9fe; color: #6d28d9; }
        .badge-served { background: #f3e8ff; color: #7e22ce; }
        .badge-completed { background: #dcfce7; color: #15803d; }
        .badge-cancelled { background: #fee2e2; color: #b91c1c; }
        .badge-available { background: #dcfce7; color: #15803d; }
        .badge-occupied { background: #fee2e2; color: #b91c1c; }
        .badge-reserved { background: #fef3c7; color: #b45309; }
        .badge-billed { background: #e0f2fe; color: #0284c7; }
        .badge-active { background: #dcfce7; color: #15803d; }
        .badge-inactive { background: #f1f5f9; color: #64748b; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 7px; font-size: 0.82rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .btn-primary { background: var(--admin-primary); color: #fff; }
        .btn-primary:hover { background: #a50b24; }
        .btn-secondary { background: #f1f5f9; color: #334155; border: 1px solid var(--border-light); }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 4px 10px; font-size: 0.75rem; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-warning:hover { background: #d97706; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.82rem; font-weight: 700; color: #374151; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 9px 12px;
            border: 1px solid var(--border-light); border-radius: 7px;
            font-size: 0.88rem; color: var(--text-main);
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.2s;
            background: #fff;
        }
        .form-control:focus { outline: none; border-color: var(--admin-primary); box-shadow: 0 0 0 3px rgba(200,16,46,0.1); }
        .form-hint { font-size: 0.75rem; color: var(--text-sub); margin-top: 4px; }
        .form-error { font-size: 0.75rem; color: var(--danger); margin-top: 4px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
    </style>
    @stack('admin-styles')
</head>
<body>
    @php
        $adminUser = Auth::guard('admin')->user();
        $siteLogo = \App\Models\Setting::getValue('site_logo');
    @endphp

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand-box" style="padding: 14px 14px 0 14px; margin: 0; justify-content: center;">
            <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;justify-content:center;width:100%;text-decoration:none;margin:0;padding:0;">
                @if(!empty($siteLogo) && file_exists(public_path('uploads/' . $siteLogo)))
                    <img src="{{ asset('uploads/' . $siteLogo) }}" alt="Website Logo" style="max-height: 40px; width: auto; max-width: 185px; object-fit: contain; margin: 0; padding: 0; display: block;">
                @else
                    <div style="display:flex;align-items:center;gap:10px;margin:0;padding:0;">
                        <div class="sidebar-logo-icon"><i class="fa-solid fa-pizza-slice"></i></div>
                        <div>
                            <div class="sidebar-brand-title">
                                Italian Pizza <span class="sidebar-brand-badge">PRO</span>
                            </div>
                            <div class="sidebar-brand-sub">Admin & POS Manager</div>
                        </div>
                    </div>
                @endif
            </a>
        </div>

        <nav class="sidebar-nav">
            @php $currentAdmin = auth('admin')->user(); @endphp

            @if($currentAdmin->hasPermission('dashboard.view'))
                <div class="nav-section-title">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i><span>Dashboard</span>
                </a>
            @endif

            @if($currentAdmin->hasPermission('pos.access'))
                <a href="{{ route('admin.pos.index') }}" class="nav-item-link pos-highlight {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-cash-register"></i><span>POS & Tables</span>
                </a>
            @endif

            @if($currentAdmin->hasAnyPermission(['orders.view', 'order_statuses.manage', 'settings.manage', 'users.view']))
                <div class="nav-section-title">Orders & Users</div>
                @if($currentAdmin->hasPermission('orders.view'))
                    <a href="{{ route('admin.orders.index') }}" class="nav-item-link {{ request()->routeIs('admin.orders.index','admin.orders.show') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt"></i><span>Orders</span>
                    </a>
                    @if($currentAdmin->hasPermission('orders.create'))
                        <a href="{{ route('admin.orders.create') }}" class="nav-item-link {{ request()->routeIs('admin.orders.create') ? 'active' : '' }}">
                            <i class="fa-solid fa-plus-circle"></i><span>Walk-in</span>
                        </a>
                    @endif
                    @if($currentAdmin->hasPermission('order_statuses.manage'))
                        <a href="{{ route('admin.order-statuses.index') }}" class="nav-item-link {{ request()->routeIs('admin.order-statuses.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-tags"></i><span>Statuses</span>
                        </a>
                    @endif
                    @if($currentAdmin->hasPermission('settings.manage'))
                        <a href="{{ route('admin.settings.shipping') }}" class="nav-item-link {{ request()->routeIs('admin.settings.shipping*') ? 'active' : '' }}">
                            <i class="fa-solid fa-truck-fast"></i><span>Shipping</span>
                        </a>
                    @endif
                @endif

                @if($currentAdmin->hasPermission('users.view'))
                    <a href="{{ route('admin.users.index') }}" class="nav-item-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i><span>Customers</span>
                    </a>
                @endif
            @endif

            @if($currentAdmin->hasAnyPermission(['products.view', 'categories.manage', 'sizes.manage', 'crusts.manage', 'addons.manage', 'combos.manage', 'tables.manage']))
                <div class="nav-section-title">Products</div>
                @if($currentAdmin->hasPermission('products.view'))
                    <a href="{{ route('admin.products.index') }}" class="nav-item-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-burger"></i><span>Products</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('categories.manage'))
                    <a href="{{ route('admin.categories.index') }}" class="nav-item-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-folder-tree"></i><span>Categories</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('sizes.manage'))
                    <a href="{{ route('admin.sizes.index') }}" class="nav-item-link {{ request()->routeIs('admin.sizes.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-ruler-horizontal"></i><span>Sizes</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('crusts.manage'))
                    <a href="{{ route('admin.crusts.index') }}" class="nav-item-link {{ request()->routeIs('admin.crusts.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-half-stroke"></i><span>Crusts</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('addons.manage'))
                    <a href="{{ route('admin.addons.index') }}" class="nav-item-link {{ request()->routeIs('admin.addons.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-puzzle-piece"></i><span>Add-ons</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('combos.manage'))
                    <a href="{{ route('admin.combos.index') }}" class="nav-item-link {{ request()->routeIs('admin.combos.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group"></i><span>Combos</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('tables.manage'))
                    <a href="{{ route('admin.tables.index') }}" class="nav-item-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-table-cells"></i><span>Tables</span>
                    </a>
                @endif
            @endif

            @if($currentAdmin->hasAnyPermission(['coupons.manage', 'points.manage', 'pages.manage', 'banners.manage']))
                <div class="nav-section-title">Marketing</div>
                @if($currentAdmin->hasPermission('coupons.manage'))
                    <a href="{{ route('admin.coupons.index') }}" class="nav-item-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-ticket"></i><span>Coupons</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('points.manage'))
                    <a href="{{ route('admin.settings.points') }}" class="nav-item-link {{ request()->routeIs('admin.settings.points*') ? 'active' : '' }}">
                        <i class="fa-solid fa-coins"></i><span>Points</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('pages.manage'))
                    <a href="{{ route('admin.pages.index') }}" class="nav-item-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-lines"></i><span>Pages</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('banners.manage'))
                    <a href="{{ route('admin.banners.index') }}" class="nav-item-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-images"></i><span>Banners</span>
                    </a>
                @endif
            @endif

            @if($currentAdmin->hasAnyPermission(['roles.manage', 'admins.manage']))
                <div class="nav-section-title">Staff & Roles</div>
                @if($currentAdmin->hasPermission('roles.manage'))
                    <a href="{{ route('admin.roles.index') }}" class="nav-item-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-shield-halved"></i><span>Roles</span>
                    </a>
                @endif
                @if($currentAdmin->hasPermission('admins.manage'))
                    <a href="{{ route('admin.staff.index') }}" class="nav-item-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-shield"></i><span>Staff</span>
                    </a>
                @endif
            @endif

            <div class="nav-section-title">Settings</div>
            @if($currentAdmin->hasPermission('settings.manage'))
                <a href="{{ route('admin.settings.index') }}" class="nav-item-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders"></i><span>Settings</span>
                </a>
            @endif
            <a href="{{ route('admin.profile') }}" class="nav-item-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i><span>Profile</span>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="nav-item-link">
                <i class="fa-solid fa-globe"></i><span>View Site</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-profile-pill">
                <a href="{{ route('admin.profile') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;min-width:0;flex:1;">
                    <div style="position:relative;width:34px;height:34px;border-radius:10px;background:#f1f5f9;border:1px solid #cbd5e1;display:flex;align-items:center;justify-content:center;color:#475569;font-size:0.95rem;overflow:hidden;flex-shrink:0;">
                        @if(!empty($adminUser->image) && file_exists(public_path('uploads/' . $adminUser->image)))
                            <img src="{{ asset('uploads/' . $adminUser->image) }}" alt="{{ $adminUser->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="fa-solid fa-user-shield"></i>
                        @endif
                        <span style="position:absolute;bottom:0px;right:0px;width:8px;height:8px;background:#10b981;border-radius:50%;border:2px solid #ffffff;box-shadow:0 0 4px #10b981;"></span>
                    </div>
                    <div style="min-width:0;">
                        <div style="font-size:0.82rem;font-weight:700;color:#0f172a;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $adminUser->name ?? 'Administrator' }}</div>
                        <div style="font-size:0.68rem;color:#64748b;font-weight:600;">Super Admin</div>
                    </div>
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout from Admin Panel" style="background:#fee2e2;border:1px solid #fca5a5;color:#dc2626;width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#dc2626';this.style.color='#fff';" onmouseout="this.style.background='#fee2e2';this.style.color='#dc2626';">
                        <i class="fa-solid fa-power-off" style="font-size:0.82rem;"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="admin-main-wrap">
        <header class="admin-header">
            <div class="header-title-left">
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="header-actions-right">
                <a href="{{ route('home') }}" target="_blank" class="btn-view-store">
                    <i class="fa-solid fa-store"></i> View Store
                </a>
                <a href="{{ route('admin.pos.index') }}" class="btn-quick-pos">
                    <i class="fa-solid fa-cash-register"></i> Open POS
                </a>

                <!-- Admin Profile Dropdown -->
                <div class="admin-user-dropdown-wrap" id="adminUserDropdownWrap">
                    <button type="button" class="admin-user-btn" id="adminUserDropdownBtn" onclick="toggleAdminDropdown(event)">
                        <div class="admin-avatar-circle">
                            @if(!empty($adminUser->image) && file_exists(public_path('uploads/' . $adminUser->image)))
                                <img src="{{ asset('uploads/' . $adminUser->image) }}" alt="{{ $adminUser->name }}">
                            @else
                                <i class="fa-solid fa-user"></i>
                            @endif
                        </div>
                        <div class="admin-user-info-text">
                            <div class="admin-user-name">{{ Str::limit($adminUser->name ?? 'Admin', 14) }}</div>
                            <div class="admin-user-role">Administrator</div>
                        </div>
                        <i class="fa-solid fa-chevron-down admin-chevron"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="admin-dropdown-menu" id="adminDropdownMenu">
                        <div class="dropdown-user-header">
                            <div class="admin-avatar-circle" style="width:38px;height:38px;font-size:1rem;">
                                @if(!empty($adminUser->image) && file_exists(public_path('uploads/' . $adminUser->image)))
                                    <img src="{{ asset('uploads/' . $adminUser->image) }}" alt="{{ $adminUser->name }}">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div class="dropdown-header-name">{{ $adminUser->name ?? 'Administrator' }}</div>
                                <div class="dropdown-header-email">{{ $adminUser->email ?? 'admin@italianpizza.com' }}</div>
                            </div>
                        </div>

                        <a href="{{ route('admin.profile') }}" class="admin-dropdown-item">
                            <i class="fa-solid fa-user-pen"></i>
                            <span>Edit Profile</span>
                        </a>

                        <a href="{{ route('admin.profile') }}#passwordSection" class="admin-dropdown-item">
                            <i class="fa-solid fa-key"></i>
                            <span>Change Password</span>
                        </a>

                        <a href="{{ route('admin.settings.index') }}" class="admin-dropdown-item">
                            <i class="fa-solid fa-sliders"></i>
                            <span>Store Settings</span>
                        </a>

                        <div class="admin-dropdown-divider"></div>

                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="admin-dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-page-content">
            @if(session('success'))
                <div class="alert-box alert-success">
                    <span><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#15803d;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-box alert-error">
                    <span><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#b91c1c;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @yield('admin-content')
            @yield('content')
        </main>
    </div>

    <script>
        function toggleAdminDropdown(e) {
            e.stopPropagation();
            const wrap = document.getElementById('adminUserDropdownWrap');
            if (wrap) wrap.classList.toggle('open');
        }

        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('adminUserDropdownWrap');
            if (wrap && !wrap.contains(e.target)) {
                wrap.classList.remove('open');
            }
        });
    </script>

    @stack('admin-scripts')
</body>
</html>
