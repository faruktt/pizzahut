<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Portal | Italian Pizza & POS')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
            --sidebar-bg: #090d16;
            --sidebar-border: #172033;
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

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--page-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            line-height: 1.5;
        }

        a { text-decoration: none; color: inherit; }

        /* SIDEBAR - Ultra-Premium Dark Luxury */
        aside.admin-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #090d16 0%, #0d1322 100%);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 100;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.18);
        }

        .sidebar-brand-box {
            padding: 22px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--sidebar-border);
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
        }

        .sidebar-logo-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #e31837 0%, #990011 100%);
            color: #fff;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 6px 16px rgba(227, 24, 55, 0.35);
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-brand-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sidebar-brand-badge {
            font-size: 0.6rem;
            font-weight: 800;
            background: rgba(227, 24, 55, 0.25);
            color: #ff6b81;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid rgba(227, 24, 55, 0.4);
            letter-spacing: 0.5px;
        }

        .sidebar-brand-sub {
            font-size: 0.68rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 2px;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 14px 10px 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 9px;
            font-size: 0.86rem;
            font-weight: 600;
            color: #94a3b8;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .nav-item-link i {
            width: 18px;
            text-align: center;
            font-size: 0.95rem;
            color: #64748b;
            transition: color 0.2s, transform 0.2s;
        }

        .nav-item-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #f8fafc;
            transform: translateX(2px);
        }

        .nav-item-link:hover i {
            color: #ffffff;
        }

        .nav-item-link.active {
            background: linear-gradient(90deg, rgba(227, 24, 55, 0.2) 0%, rgba(227, 24, 55, 0.04) 100%);
            color: #ffffff;
            font-weight: 700;
            border-left: 3px solid #e31837;
            padding-left: 11px;
            box-shadow: inset 0 0 16px rgba(227, 24, 55, 0.08);
        }

        .nav-item-link.active i {
            color: #ff4d6d;
            filter: drop-shadow(0 0 8px rgba(227, 24, 55, 0.6));
        }

        .nav-item-link.pos-highlight {
            background: linear-gradient(90deg, rgba(14, 165, 233, 0.12) 0%, rgba(14, 165, 233, 0.02) 100%);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.25);
            font-weight: 700;
        }

        .nav-item-link.pos-highlight i {
            color: #38bdf8;
        }

        .nav-item-link.pos-highlight:hover {
            background: linear-gradient(90deg, rgba(14, 165, 233, 0.2) 0%, rgba(14, 165, 233, 0.08) 100%);
            color: #7dd3fc;
            border-color: rgba(56, 189, 248, 0.45);
        }

        .nav-item-link.pos-highlight.active {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
            border-left: 3px solid #38bdf8;
            padding-left: 11px;
        }

        /* Sub-menu style */
        .nav-sub-menu { display: none; padding-left: 24px; flex-direction: column; gap: 2px; }
        .nav-sub-menu.open { display: flex; }
        .nav-item-link.has-sub::after { content: '\f107'; font-family: 'Font Awesome 6 Free'; font-weight: 900; margin-left: auto; font-size: 0.75rem; transition: transform 0.2s; }
        .nav-item-link.has-sub.open::after { transform: rotate(180deg); }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--sidebar-border);
            background: rgba(11, 16, 27, 0.95);
        }

        .admin-profile-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
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
            font-family: 'Plus Jakarta Sans', sans-serif;
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

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand-box">
            <div class="sidebar-logo-icon"><i class="fa-solid fa-pizza-slice"></i></div>
            <div>
                <div class="sidebar-brand-title">
                    Italian Pizza <span class="sidebar-brand-badge">PRO</span>
                </div>
                <div class="sidebar-brand-sub">Admin & POS Manager</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.pos.index') }}" class="nav-item-link pos-highlight {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cash-register"></i><span>POS & Tables</span>
            </a>

            <div class="nav-section-title">Orders</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-item-link {{ request()->routeIs('admin.orders.index','admin.orders.show') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i><span>All Orders</span>
            </a>
            <a href="{{ route('admin.orders.create') }}" class="nav-item-link {{ request()->routeIs('admin.orders.create') ? 'active' : '' }}">
                <i class="fa-solid fa-plus-circle"></i><span>Walk-in Order</span>
            </a>

            <div class="nav-section-title">Menu Management</div>
            <a href="{{ route('admin.products.index') }}" class="nav-item-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-burger"></i><span>Products</span>
            </a>
            <a href="{{ route('admin.combos.index') }}" class="nav-item-link {{ request()->routeIs('admin.combos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i><span>Combos</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-item-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i><span>Categories</span>
            </a>
            <a href="{{ route('admin.sizes.index') }}" class="nav-item-link {{ request()->routeIs('admin.sizes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-ruler-horizontal"></i><span>Sizes</span>
            </a>
            <a href="{{ route('admin.crusts.index') }}" class="nav-item-link {{ request()->routeIs('admin.crusts.*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-half-stroke"></i><span>Crusts</span>
            </a>
            <a href="{{ route('admin.addons.index') }}" class="nav-item-link {{ request()->routeIs('admin.addons.*') ? 'active' : '' }}">
                <i class="fa-solid fa-plus-circle"></i><span>Add Ons</span>
            </a>
            <a href="{{ route('admin.tables.index') }}" class="nav-item-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                <i class="fa-solid fa-table-cells"></i><span>Dining Tables</span>
            </a>

            <div class="nav-section-title">Marketing & Website</div>
            <a href="{{ route('admin.coupons.index') }}" class="nav-item-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket"></i><span>Coupons</span>
            </a>
            <a href="{{ route('admin.settings.points') }}" class="nav-item-link {{ request()->routeIs('admin.settings.points*') ? 'active' : '' }}">
                <i class="fa-solid fa-coins"></i><span>Reward Points</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="nav-item-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-lines"></i><span>Pages (CMS)</span>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="nav-item-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="fa-solid fa-images"></i><span>Banners</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i><span>Store & Map Settings</span>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="nav-item-link">
                <i class="fa-solid fa-globe"></i><span>View Frontend</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-profile-pill">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="position:relative;width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg, #1e293b, #0f172a);border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:#e2e8f0;font-size:0.95rem;">
                        <i class="fa-solid fa-user-shield"></i>
                        <span style="position:absolute;bottom:-1px;right:-1px;width:8px;height:8px;background:#10b981;border-radius:50%;border:2px solid #090d16;box-shadow:0 0 6px #10b981;"></span>
                    </div>
                    <div>
                        <div style="font-size:0.82rem;font-weight:700;color:#f8fafc;line-height:1.2;">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</div>
                        <div style="font-size:0.68rem;color:#64748b;font-weight:600;">Super Admin</div>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout from Admin Panel" style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.25);color:#ef4444;width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#ef4444';this.style.color='#fff';" onmouseout="this.style.background='rgba(239,68,68,0.12)';this.style.color='#ef4444';">
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
        </main>
    </div>

    @stack('admin-scripts')
</body>
</html>
