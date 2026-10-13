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

        /* Top Header (Exact Match to Image 1 & 3) */
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
            padding: 12px 0;
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

        /* Toggle: Delivery / Takeaway */
        .order-type-switch {
            display: inline-flex;
            background: #f1f2f4;
            border-radius: 30px;
            padding: 4px;
            border: 1px solid #e2e4e8;
        }

        .type-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 24px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
        }

        .type-btn.active {
            background: #ffffff;
            color: var(--brand-red);
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        /* Location & Store Pickers */
        .header-meta-group {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .info-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #333;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 6px 12px;
            border-radius: 8px;
        }

        .info-pill strong {
            display: block;
            font-size: 0.72rem;
            color: #777;
            text-transform: uppercase;
        }

        /* Search Bar & Sign In */
        .header-action-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-box {
            position: relative;
            width: 240px;
        }

        .search-input {
            width: 100%;
            padding: 8px 36px 8px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: var(--brand-red);
        }

        .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
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

        .user-dropdown {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--brand-red);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Floating Cart Icon on right edge (Exact match to image 1) */
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

    <!-- Header matching Image 1 & 3 -->
    <header class="site-header">
        <div class="container">
            <div class="header-top-row">
                <!-- Left: Logo & Order Type switcher -->
                <div class="header-left-group">
                    <a href="{{ route('home') }}" class="brand-logo-wrap">
                        @if(!empty($siteLogo))
                            {{-- Custom logo uploaded from admin panel --}}
                            <img src="{{ asset('uploads/' . $siteLogo) }}" alt="Website Logo" style="height:44px;width:auto;max-width:220px;object-fit:contain;">
                        @else
                            {{-- Default logo --}}
                            <span class="brand-logo-icon"><i class="fa-solid fa-pizza-slice"></i></span>
                            <div class="brand-logo-text">
                                <strong>Pizza Hut</strong>
                                <span>BANGLADESH</span>
                            </div>
                        @endif
                    </a>

                    <div class="order-type-switch">
                        <button type="button" class="type-btn active" id="btnDelivery" onclick="switchOrderType('delivery')">
                            <i class="fa-solid fa-motorcycle"></i> Delivery
                        </button>
                        <button type="button" class="type-btn" id="btnTakeaway" onclick="switchOrderType('takeaway')">
                            <i class="fa-solid fa-bag-shopping"></i> Takeaway
                        </button>
                    </div>
                </div>

                <!-- Middle: Store & Delivery Location -->
                <div class="header-meta-group">
                    <div class="info-pill">
                        <i class="fa-solid fa-store" style="color:#c8102e;"></i>
                        <div>
                            <strong>Store</strong>
                            <span>PH Adabor</span>
                        </div>
                    </div>

                    <div class="info-pill" style="cursor: pointer;" title="Click to change location">
                        <i class="fa-solid fa-location-dot" style="color:#c8102e;"></i>
                        <div>
                            <strong>Delivery Location</strong>
                            <span>Q8CX+3X6, Dhaka, Bangladesh <i class="fa-solid fa-pen" style="font-size:0.7rem;"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Right: Search & Customer Sign In -->
                <div class="header-action-group">
                    <div class="search-box">
                        <input type="text" class="search-input" id="globalSearch" placeholder="Search for products, deals...">
                        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                    </div>

                    @if(Auth::guard('web')->check())
                        <div class="user-dropdown">
                            <span><i class="fa-solid fa-circle-user"></i> {{ Auth::guard('web')->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" style="background:none; border:none; color:#c8102e; font-size:0.75rem; cursor:pointer; text-decoration:underline;">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-signin">
                            <i class="fa-solid fa-right-to-bracket"></i> Sign in
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

    <!-- Footer matching Image 1 & 3 -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>About Us</h4>
                    <ul>
                        <li><a href="#">About Pizza Hut Bangladesh</a></li>
                        <li><a href="#">Store Locator</a></li>
                        <li><a href="{{ route('admin.login') }}" target="_blank" style="color: #ffaa00; font-weight:600;"><i class="fa-solid fa-lock"></i> Staff / Admin Portal</a></li>
                    </ul>
                    <div class="social-icons">
                        <a href="#" class="social-link-icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-link-icon"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-link-icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Policies</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact Pizza Hut</a></li>
                        <li><a href="#">Customer Feedback</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Download Our App</h4>
                    <div class="app-badges">
                        <a href="#" class="app-badge-btn">
                            <i class="fa-brands fa-google-play"></i> <span>GET IT ON</span><br>
                            <strong style="font-size:0.95rem;">Google Play</strong>
                        </a>
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
