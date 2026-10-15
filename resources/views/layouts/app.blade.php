<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pizza Hut Bangladesh | Italian Pizza Delivery')</title>
    <meta name="description" content="Order the best pan pizza, deals, pasta, and sides online. Fast delivery and takeaway across Bangladesh.">

    {{-- Favicon (manageable from admin panel) --}}
    @if(!empty($siteFavicon))
        <link rel="icon" type="image/x-icon" href="{{ asset('uploads/' . $siteFavicon) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Self-contained Styling matching Pizza Hut Bangladesh Screenshot -->
    <style>
        :root {
            --brand-red: #c8102e;
            --brand-red-dark: #a50b24;
            --brand-red-light: #fde8eb;
            --brand-green: #108e3e;
            --text-dark: #222222;
            --text-muted: #666666;
            --border-color: #e5e5e5;
            --bg-light: #f8f8f8;
            --bg-card: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--text-dark);
            background-color: #fbfbfb;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: 'Poppins', sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* Top Header - Simplified: Logo, Search, User */
        header.site-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }

        .header-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            gap: 16px;
        }

        .header-left-group {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Brand Logo */
        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            color: var(--brand-red);
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .brand-logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--brand-red);
            color: #fff;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            font-size: 1.25rem;
        }

        .brand-logo-text {
            line-height: 1.1;
        }

        .brand-logo-text span {
            display: block;
            font-size: 0.65rem;
            color: #666;
            letter-spacing: 2px;
            font-weight: 600;
        }

        /* Search Bar */
        .search-box {
            position: relative;
            width: 300px;
            max-width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 10px 40px 10px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-input:focus {
            border-color: var(--brand-red);
            box-shadow: 0 0 0 3px rgba(200,16,46,0.1);
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
        }

        /* User Area */
        .user-area {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-signin {
            background: var(--brand-red);
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 8px 18px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-signin:hover {
            background: var(--brand-red-dark);
        }

        .user-menu {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            background: #f8f9fa;
            border: 1px solid var(--border-color);
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            transition: all 0.2s;
        }

        .user-btn:hover {
            background: var(--brand-red-light);
            border-color: var(--brand-red);
            color: var(--brand-red);
        }

        .user-btn i {
            color: var(--brand-red);
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s;
            z-index: 100;
        }

        .user-menu:hover .user-dropdown-menu,
        .user-menu:focus-within .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-menu a,
        .user-dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 12px 16px;
            text-align: left;
            background: none;
            border: none;
            font-size: 0.85rem;
            color: var(--text-dark);
            cursor: pointer;
            transition: background 0.15s;
        }

        .user-dropdown-menu a:hover,
        .user-dropdown-menu button:hover {
            background: var(--brand-red-light);
            color: var(--brand-red);
        }

        .user-dropdown-menu .divider {
            height: 1px;
            background: var(--border-color);
            margin: 4px 0;
        }

        .user-dropdown-menu .logout-btn {
            color: var(--danger);
        }

        .user-dropdown-menu .logout-btn:hover {
            background: #fee2e2;
        }

        .user-name {
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
        }

        /* Floating Cart Icon */
        .floating-cart-btn {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: #ffffff;
            border: 2px solid var(--brand-red);
            border-right: none;
            border-radius: 30px 0 0 30px;
            padding: 12px 14px 12px 16px;
            box-shadow: -4px 4px 16px rgba(200, 16, 46, 0.2);
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .floating-cart-btn:hover {
            padding-right: 18px;
            box-shadow: -6px 6px 20px rgba(200, 16, 46, 0.35);
        }

        .cart-icon-svg {
            color: var(--brand-red);
            width: 26px;
            height: 26px;
        }

        .cart-badge-count {
            background: var(--brand-red);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 800;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Footer (Exact match to Image 1 & 3: Dark themed with columns) */
        footer.site-footer {
            background: #0d0d0d;
            color: #a3a3a3;
            margin-top: auto;
            padding: 50px 0 24px 0;
            border-top: 4px solid var(--brand-red);
            font-size: 0.85rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            padding-bottom: 40px;
            border-bottom: 1px solid #222;
        }

        .footer-col h4 {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            color: #a3a3a3;
            transition: color 0.2s;
        }

        .footer-col ul li a:hover {
            color: #ffffff;
        }

        .social-icons {
            display: flex;
            gap: 12px;
            margin-top: 14px;
        }

        .social-link-icon {
            width: 32px;
            height: 32px;
            background: #222;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .social-link-icon:hover {
            background: var(--brand-red);
        }

        .app-badges {
            margin-top: 12px;
        }

        .app-badge-btn {
            display: inline-block;
            background: #222;
            border: 1px solid #333;
            border-radius: 6px;
            padding: 8px 14px;
            color: #fff;
            font-size: 0.78rem;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            font-size: 0.78rem;
            color: #666;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Toast notifications */
        .toast-popup {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #222;
            color: #fff;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
            z-index: 2000;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast-popup.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        .toast-popup.success {
            border-left: 4px solid var(--brand-green);
        }

        /* Header Address Tag */
        .header-address-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 14px;
            background: #fff5f5;
            border: 1px solid rgba(227, 24, 55, 0.2);
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #4a5568;
            max-width: 320px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .header-address-tag i {
            color: var(--brand-red);
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* Interactive Restaurant Location & Google Map Section */
        .restaurant-map-section {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-top: 1px solid #edf2f7;
            padding: 56px 0 64px;
            position: relative;
        }

        .map-section-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .map-section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(227, 24, 55, 0.08);
            color: var(--brand-red);
            font-weight: 800;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 10px;
        }

        .map-section-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .map-section-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            max-width: 580px;
            margin: 0 auto;
        }

        .map-container-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
            align-items: stretch;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 36px rgba(0,0,0,0.06);
            overflow: hidden;
            padding: 14px;
        }

        .map-iframe-wrapper {
            border-radius: 14px;
            overflow: hidden;
            min-height: 380px;
            background: #e2e8f0;
            position: relative;
        }

        .map-iframe-wrapper iframe {
            width: 100% !important;
            height: 100% !important;
            min-height: 380px;
            border: none;
            display: block;
        }

        .map-info-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
            border-radius: 14px;
            padding: 28px 24px;
            border: 1px solid #f1f5f9;
        }

        .map-info-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .map-info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 0.88rem;
            color: #334155;
            line-height: 1.5;
        }

        .map-info-item i {
            font-size: 1rem;
            color: var(--brand-red);
            width: 20px;
            text-align: center;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .map-btn-directions {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 20px;
            background: var(--brand-red);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.92rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(227, 24, 55, 0.25);
            margin-top: 10px;
        }

        .map-btn-directions:hover {
            background: #c8102e;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(227, 24, 55, 0.35);
            color: #fff;
        }

        .footer-address-box {
            margin: 14px 0 18px;
            font-size: 0.82rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer-address-box .item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 8px;
        }

        .footer-address-box .item i {
            color: var(--brand-red);
            font-size: 0.85rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        @media (max-width: 900px) {
            .header-top-row {
                flex-wrap: wrap;
            }
            .header-meta-group {
                display: none;
            }
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .map-container-grid {
                grid-template-columns: 1fr;
            }
            .map-iframe-wrapper {
                min-height: 300px;
            }
            .header-address-tag {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }
            .search-box {
                width: 160px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header - Simplified: Logo, Search, User -->
    <header class="site-header">
        <div class="container">
            <div class="header-top-row">
                <!-- Left: Logo & Address -->
                <div class="header-left-group" style="display:flex;align-items:center;gap:16px;">
                    <a href="{{ route('home') }}" class="brand-logo-wrap">
                        @if(!empty($siteLogo))
                            <img src="{{ asset('uploads/' . $siteLogo) }}" alt="Website Logo" style="height:44px;width:auto;max-width:220px;object-fit:contain;">
                        @else
                            <span class="brand-logo-icon"><i class="fa-solid fa-pizza-slice"></i></span>
                            <div class="brand-logo-text">
                                <strong>Pizza Hut</strong>
                                <span>BANGLADESH</span>
                            </div>
                        @endif
                    </a>
                    @if(!empty($siteAddress))
                    <div class="header-address-tag" title="{{ $siteAddress }}">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>{{ Str::limit($siteAddress, 36) }}</span>
                    </div>
                    @endif
                </div>

                <!-- Center: Search -->
                <div class="search-box">
                    <input type="text" class="search-input" id="globalSearch" placeholder="Search for products, deals...">
                    <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                </div>

                <!-- Right: User Area -->
                <div class="user-area">
                    @if(Auth::guard('web')->check())
                        <div class="user-menu">
                            <div class="user-btn">
                                <i class="fa-solid fa-circle-user"></i>
                                <span>{{ Auth::guard('web')->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down" style="font-size:0.7rem;"></i>
                            </div>
                            <div class="user-dropdown-menu">
                                <div class="user-name">{{ Auth::guard('web')->user()->name }}</div>
                                <a href="{{ route('customer.dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
                                <a href="{{ route('customer.orders') }}"><i class="fa-solid fa-receipt"></i> My Orders</a>
                                <div class="divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-signin">
                            <i class="fa-solid fa-right-to-bracket"></i> Sign in
                        </a>
                        <a href="{{ route('register') }}" class="btn-signin" style="background:#fff; color:var(--brand-red); border:1px solid var(--brand-red);">
                            <i class="fa-solid fa-user-plus"></i> Register
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main style="flex: 1;">
        @yield('content')
    </main>

    <!-- Floating Cart Icon on right edge (Image 1) -->
    <a href="{{ route('cart.index') }}" class="floating-cart-btn" id="floatingCartBtn" title="View your cart">
        <i class="fa-solid fa-cart-shopping" style="color:var(--brand-red);font-size:1.3rem;"></i>
        <span class="cart-badge-count" id="headerCartCount">{{ $cartCount ?? 0 }}</span>
    </a>

    <!-- Restaurant Location & Interactive Google Map Section -->
    <section class="restaurant-map-section" id="location-map">
        <div class="container">
            <div class="map-section-header">
                <div class="map-section-badge">
                    <i class="fa-solid fa-location-dot"></i> Visit Our Restaurant
                </div>
                <h2 class="map-section-title">Find Us on Google Maps</h2>
                <p class="map-section-subtitle">Experience fresh, oven-baked authentic Italian pizza & pasta live at our restaurant dining table.</p>
            </div>

            <div class="map-container-grid">
                <div class="map-iframe-wrapper">
                    @if(!empty($googleMapEmbed) && Str::startsWith(trim($googleMapEmbed), '<iframe'))
                        {!! preg_replace('/width="\d+"/', 'width="100%"', preg_replace('/height="\d+"/', 'height="100%"', $googleMapEmbed)) !!}
                    @elseif(!empty($googleMapEmbed) && Str::startsWith(trim($googleMapEmbed), 'http'))
                        <iframe src="{{ trim($googleMapEmbed) }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    @else
                        <iframe src="https://maps.google.com/maps?q={{ urlencode(!empty($siteAddress) ? $siteAddress : 'Italian Pizza Dhaka Bangladesh') }}&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    @endif
                </div>

                <div class="map-info-card">
                    <div>
                        <div class="map-info-title">
                            <i class="fa-solid fa-store" style="color:var(--brand-red);"></i>
                            <span>Italian Pizza</span>
                        </div>

                        <div class="map-info-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <div>
                                <strong style="display:block;color:#0f172a;margin-bottom:2px;">Our Address</strong>
                                <span>{{ !empty($siteAddress) ? $siteAddress : 'House 14, Road 11, Block D, Gulshan-2, Dhaka' }}</span>
                            </div>
                        </div>

                        <div class="map-info-item">
                            <i class="fa-solid fa-phone"></i>
                            <div>
                                <strong style="display:block;color:#0f172a;margin-bottom:2px;">Phone & Hotline</strong>
                                <span>{{ !empty($sitePhone) ? $sitePhone : '+880 1711-223344' }}</span>
                            </div>
                        </div>

                        <div class="map-info-item">
                            <i class="fa-solid fa-clock"></i>
                            <div>
                                <strong style="display:block;color:#0f172a;margin-bottom:2px;">Opening Hours</strong>
                                <span>{{ !empty($openingHours) ? $openingHours : 'Daily: 11:00 AM – 11:30 PM' }}</span>
                            </div>
                        </div>

                        <div class="map-info-item">
                            <i class="fa-solid fa-envelope"></i>
                            <div>
                                <strong style="display:block;color:#0f172a;margin-bottom:2px;">Support Email</strong>
                                <span>{{ !empty($siteEmail) ? $siteEmail : 'contact@italianpizza.com' }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(!empty($siteAddress) ? $siteAddress : 'Italian Pizza Dhaka') }}" target="_blank" class="map-btn-directions">
                        <i class="fa-solid fa-diamond-turn-right"></i> Get Live Directions
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer matching Image 1 & 3 -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Col 1: Logo & Address -->
                <div class="footer-col">
                    <a href="{{ route('home') }}" class="brand-logo-wrap" style="color:#fff;margin-bottom:16px;display:inline-flex;align-items:center;">
                        @if(!empty($siteLogo))
                            <img src="{{ asset('uploads/' . $siteLogo) }}" alt="Website Logo" style="height:56px;width:auto;max-width:200px;object-fit:contain;display:block;">
                        @else
                            <span class="brand-logo-icon"><i class="fa-solid fa-pizza-slice"></i></span>
                            <div class="brand-logo-text" style="color:#fff;">
                                <strong>Italian Pizza</strong>
                                <span style="color:#aaa;">BANGLADESH</span>
                            </div>
                        @endif
                    </a>

                    <div class="footer-address-box">
                        <div class="item">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>{{ !empty($siteAddress) ? $siteAddress : 'House 14, Road 11, Block D, Gulshan-2, Dhaka' }}</span>
                        </div>
                        <div class="item">
                            <i class="fa-solid fa-phone"></i>
                            <span>{{ !empty($sitePhone) ? $sitePhone : '+880 1711-223344' }}</span>
                        </div>
                        <div class="item">
                            <i class="fa-solid fa-clock"></i>
                            <span>{{ !empty($openingHours) ? $openingHours : 'Daily: 11:00 AM – 11:30 PM' }}</span>
                        </div>
                    </div>

                    <div class="social-icons">
                        <a href="#" class="social-link-icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-link-icon"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-link-icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Col 2: Categories -->
                <div class="footer-col">
                    <h4>Categories</h4>
                    <ul>
                        @forelse($footerCategories ?? [] as $fCat)
                            <li><a href="{{ route('home') }}#category-{{ $fCat->id }}"><i class="fa-solid fa-angle-right" style="font-size:0.7rem;margin-right:4px;color:var(--brand-red);"></i> {{ $fCat->name }}</a></li>
                        @empty
                            <li><a href="{{ route('home') }}">Classic Pizzas</a></li>
                            <li><a href="{{ route('home') }}">Appetizers</a></li>
                            <li><a href="{{ route('home') }}">Pastas & Pastries</a></li>
                        @endforelse
                    </ul>
                </div>

                <!-- Col 3: Pages & Policies -->
                <div class="footer-col">
                    <h4>Pages & Policies</h4>
                    <ul>
                        @forelse($footerPages ?? [] as $fPage)
                            <li><a href="{{ route('page.show', $fPage->slug) }}"><i class="fa-solid fa-angle-right" style="font-size:0.7rem;margin-right:4px;color:var(--brand-red);"></i> {{ $fPage->title }}</a></li>
                        @empty
                            <li><a href="{{ route('page.show', 'about-us') }}">About Us</a></li>
                            <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('page.show', 'terms-and-conditions') }}">Terms & Conditions</a></li>
                        @endforelse
                        <li><a href="{{ route('admin.login') }}" target="_blank" style="color: #ffaa00; font-weight:600;"><i class="fa-solid fa-lock"></i> Staff / Admin Portal</a></li>
                    </ul>
                </div>

                <!-- Col 4: Customer Care & Support -->
                <div class="footer-col">
                    <h4>Customer Care</h4>
                    <p style="font-size:0.84rem;color:#a3a3a3;margin-bottom:14px;line-height:1.6;">
                        Have questions, feedback or wish to place a special catering order? Contact our support team directly.
                    </p>
                    <div style="display:flex;flex-direction:column;gap:10px;font-size:0.85rem;color:#e2e8f0;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <i class="fa-solid fa-headset" style="color:var(--brand-red);font-size:1rem;width:20px;text-align:center;"></i>
                            <span>{{ !empty($sitePhone) ? $sitePhone : '+880 1711-223344' }}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <i class="fa-solid fa-envelope" style="color:var(--brand-red);font-size:0.95rem;width:20px;text-align:center;"></i>
                            <span>{{ !empty($siteEmail) ? $siteEmail : 'contact@italianpizza.com' }}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <i class="fa-solid fa-clock" style="color:var(--brand-red);font-size:0.95rem;width:20px;text-align:center;"></i>
                            <span>{{ !empty($openingHours) ? $openingHours : 'Daily: 11:00 AM – 11:30 PM' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div>© {{ date('Y') }}. Pizza Hut Bangladesh. All rights reserved.</div>
                <div>Powered by <strong>Transcom / Italian Pizza Ltd.</strong></div>
            </div>
        </div>
    </footer>

    <!-- Global Toast Popup -->
    <div id="globalToast" class="toast-popup success">
        <i class="fa-solid fa-circle-check" id="toastIcon"></i>
        <span id="toastMessage">Action successful</span>
    </div>

    <script>
        function showToast(message, isSuccess = true) {
            const toast = document.getElementById('globalToast');
            const toastMsg = document.getElementById('toastMessage');
            toastMsg.innerText = message;
            toast.className = 'toast-popup show ' + (isSuccess ? 'success' : 'error');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function switchOrderType(type) {
            document.getElementById('btnDelivery').classList.toggle('active', type === 'delivery');
            document.getElementById('btnTakeaway').classList.toggle('active', type === 'takeaway');
            showToast('Order mode switched to: ' + (type === 'delivery' ? 'Delivery' : 'Takeaway'));
        }
    </script>
    @stack('scripts')
</body>
</html>
