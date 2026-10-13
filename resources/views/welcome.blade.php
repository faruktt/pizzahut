<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Italian Pizza | Authentic Wood-Fired Pizzeria – Coming Soon</title>
    <meta name="description" content="Authentic Italian wood-fired pizza crafted with 48-hour fermented sourdough, San Marzano tomatoes, and fresh buffalo mozzarella. Coming soon to your city!">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍕</text></svg>">

    <style>
        :root {
            --bg-dark: #0a0807;
            --bg-card: rgba(22, 18, 16, 0.78);
            --border-card: rgba(255, 120, 60, 0.15);
            --primary: #ff5722;
            --primary-glow: rgba(255, 87, 34, 0.4);
            --amber: #f59e0b;
            --italian-green: #10b981;
            --italian-red: #ef4444;
            --text-main: #fbf7f5;
            --text-muted: #a69c96;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            line-height: 1.6;
        }

        /* Ambient Glow & Background Grid */
        .ambient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .glow-sphere-1 {
            position: absolute;
            width: 700px;
            height: 700px;
            top: -200px;
            right: -100px;
            background: radial-gradient(circle, rgba(255, 87, 34, 0.18) 0%, rgba(245, 158, 11, 0.05) 50%, transparent 70%);
            filter: blur(60px);
            animation: pulse-glow 8s ease-in-out infinite alternate;
        }

        .glow-sphere-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            bottom: -150px;
            left: -100px;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.15) 0%, rgba(16, 185, 129, 0.05) 50%, transparent 70%);
            filter: blur(70px);
            animation: pulse-glow 10s ease-in-out infinite alternate-reverse;
        }

        .bg-mesh {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(10, 8, 7, 0.8), rgba(10, 8, 7, 0.98));
            background-size: 32px 32px, 100% 100%;
            opacity: 0.9;
        }

        @keyframes pulse-glow {
            0% { transform: scale(1) translate(0, 0); opacity: 0.7; }
            100% { transform: scale(1.15) translate(20px, 30px); opacity: 1; }
        }

        /* Typography */
        .font-cinzel {
            font-family: 'Cinzel', serif;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 1.5rem;
            position: relative;
            z-index: 10;
        }

        /* Top Italian Ribbon */
        .tricolor-stripe {
            height: 4px;
            width: 100%;
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .tricolor-stripe > div:nth-child(1) { flex: 1; background-color: var(--italian-green); }
        .tricolor-stripe > div:nth-child(2) { flex: 1; background-color: #ffffff; }
        .tricolor-stripe > div:nth-child(3) { flex: 1; background-color: var(--italian-red); }

        /* Navigation / Header */
        header {
            padding: 2.2rem 0 1.5rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(255, 87, 34, 0.25), rgba(245, 158, 11, 0.1));
            border: 1px solid rgba(255, 87, 34, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(255, 87, 34, 0.2);
        }

        .brand-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: linear-gradient(135deg, #fff 40%, #ffc0a8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-sub {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            color: var(--amber);
            text-transform: uppercase;
            font-weight: 600;
            display: block;
        }

        .badge-live {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 87, 34, 0.12);
            border: 1px solid rgba(255, 87, 34, 0.28);
            padding: 0.4rem 0.95rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #ff916b;
            text-transform: uppercase;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--primary);
            box-shadow: 0 0 0 0 rgba(255, 87, 34, 0.7);
            animation: pulse-animation 1.8s infinite;
        }

        @keyframes pulse-animation {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 87, 34, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(255, 87, 34, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 87, 34, 0); }
        }

        /* Hero Section */
        .hero-section {
            padding: 3.5rem 0 4rem 0;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 3.5rem;
            align-items: center;
        }

        @media (max-width: 992px) {
            .hero-section {
                grid-template-columns: 1fr;
                gap: 2.5rem;
                padding: 2.5rem 0 3rem 0;
            }
        }

        .hero-content {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #ffaa80;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(2.3rem, 4.5vw, 3.8rem);
            line-height: 1.12;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .text-gradient-fire {
            background: linear-gradient(120deg, #ff8c42, #ff4500 50%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .hero-description {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 580px;
            font-weight: 400;
        }

        /* Countdown Box */
        .countdown-wrapper {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.5rem 1.8rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
        }

        .countdown-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .countdown-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #ffaa80;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .countdown-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.9rem;
        }

        .time-card {
            background: rgba(10, 8, 7, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1rem 0.5rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .time-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            opacity: 0.5;
        }

        .time-card:hover {
            transform: translateY(-3px);
            border-color: rgba(255, 87, 34, 0.4);
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.15);
        }

        .time-number {
            font-size: clamp(1.6rem, 2.8vw, 2.4rem);
            font-weight: 800;
            font-family: 'Cinzel', serif;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .time-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #9c928c;
            font-weight: 600;
        }

        /* Subscribe Box */
        .subscribe-form-wrapper {
            margin-top: 0.5rem;
        }

        .subscribe-form {
            display: flex;
            gap: 0.75rem;
            position: relative;
        }

        @media (max-width: 540px) {
            .subscribe-form {
                flex-direction: column;
            }
        }

        .email-input-wrapper {
            flex: 1;
            position: relative;
        }

        .email-input {
            width: 100%;
            padding: 1rem 1.25rem 1rem 2.85rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 12px;
            color: #ffffff;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .email-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.2);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #8c827c;
            pointer-events: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
            color: #ffffff;
            border: none;
            padding: 0.95rem 1.75rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 6px 20px rgba(255, 87, 34, 0.35);
            white-space: nowrap;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 87, 34, 0.5);
            background: linear-gradient(135deg, #ff6b35 0%, #f4511e 100%);
        }

        .form-hint {
            margin-top: 0.5rem;
            font-size: 0.78rem;
            color: #9c928c;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .form-hint span.badge-perk {
            color: var(--amber);
            font-weight: 600;
        }

        /* Hero Image Showcase */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-frame-container {
            position: relative;
            width: 100%;
            max-width: 540px;
            border-radius: 26px;
            padding: 10px;
            background: linear-gradient(145deg, rgba(255, 120, 60, 0.25), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.65), 0 0 40px rgba(255, 87, 34, 0.15);
        }

        .image-frame-inner {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 16 / 10;
            background-color: #1a1614;
        }

        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .image-frame-container:hover .hero-img {
            transform: scale(1.04);
        }

        .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(10, 8, 7, 0.85) 0%, rgba(10, 8, 7, 0.1) 50%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.5rem;
        }

        .pizza-tagline {
            font-family: 'Cinzel', serif;
            font-size: 1.15rem;
            color: #ffffff;
            font-weight: 700;
        }

        .pizza-subline {
            font-size: 0.8rem;
            color: #e0d8d4;
        }

        /* Floating Badges on Hero Image */
        .float-badge {
            position: absolute;
            background: rgba(18, 14, 12, 0.88);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0.65rem 1rem;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            z-index: 2;
            animation: float-anim 5s ease-in-out infinite alternate;
        }

        .float-badge-1 {
            top: -15px;
            left: -15px;
            border-color: rgba(255, 87, 34, 0.4);
        }

        .float-badge-2 {
            bottom: -15px;
            right: -15px;
            border-color: rgba(16, 185, 129, 0.4);
            animation-delay: -2.5s;
        }

        @keyframes float-anim {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-8px); }
        }

        /* Features Section */
        .features-section {
            padding: 2.5rem 0 4rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .section-header {
            text-align: center;
            margin-bottom: 2.8rem;
        }

        .section-tag {
            color: var(--amber);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        .section-heading {
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            color: #ffffff;
            font-weight: 800;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 1.8rem;
            backdrop-filter: blur(14px);
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 87, 34, 0.35);
            box-shadow: 0 15px 35px rgba(255, 87, 34, 0.1);
        }

        .feature-icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(255, 87, 34, 0.2), rgba(245, 158, 11, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.25rem;
            border: 1px solid rgba(255, 87, 34, 0.25);
        }

        .feature-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.6rem;
            font-family: 'Cinzel', serif;
        }

        .feature-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Sneak Peek / Taste Teaser Card */
        .taste-banner {
            margin: 2rem 0 4rem 0;
            background: linear-gradient(135deg, rgba(255, 87, 34, 0.12) 0%, rgba(20, 16, 14, 0.8) 100%);
            border: 1px solid rgba(255, 87, 34, 0.3);
            border-radius: 22px;
            padding: 2.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .taste-content h3 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.4rem;
        }

        .taste-content p {
            font-size: 0.95rem;
            color: #bbb0aa;
        }

        .taste-badges {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .taste-pill {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #f5f0ec;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Footer */
        footer {
            padding: 2.5rem 0 3rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
            font-size: 0.85rem;
            color: #8c827c;
        }

        .footer-socials {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 87, 34, 0.35);
        }

        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #181412;
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #ffffff;
            padding: 1rem 1.4rem;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.7);
            z-index: 1000;
            transform: translateY(120px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: none;
        }

        .toast-notification.active {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.2);
            color: var(--italian-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .toast-title {
            font-weight: 700;
            font-size: 0.92rem;
            color: #ffffff;
        }

        .toast-desc {
            font-size: 0.8rem;
            color: #a89f99;
        }
    </style>
</head>
<body>

    <!-- Italian Tricolor Accent Bar -->
    <div class="tricolor-stripe">
        <div></div>
        <div></div>
        <div></div>
    </div>

    <!-- Ambient Glowing Background Elements -->
    <div class="ambient-bg">
        <div class="glow-sphere-1"></div>
        <div class="glow-sphere-2"></div>
        <div class="bg-mesh"></div>
    </div>

    <!-- Main Page Wrapper -->
    <div class="container">
        <!-- Header -->
        <header>
            <a href="{{ url('/') }}" class="brand-logo">
                <div class="brand-icon-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff783c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 11h.01"/>
                        <path d="M11 15h.01"/>
                        <path d="M16 16h.01"/>
                        <path d="m2 16 20 6-6-20A20 20 0 0 0 2 16Z"/>
                        <path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/>
                    </svg>
                </div>
                <div>
                    <span class="brand-title font-cinzel">Italian Pizza</span>
                    <span class="brand-sub">Antica Pizzeria Napoletana</span>
                </div>
            </a>

            <div class="badge-live">
                <span class="pulse-dot"></span>
                <span>Grand Opening Soon</span>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="hero-section">
            <!-- Left: Hero Text & Countdown & Subscription -->
            <div class="hero-content">
                <div class="hero-tag">
                    <span>🇮🇹 Artisanal Wood-Fired Craft</span>
                    <span>•</span>
                    <span>Est. 2026</span>
                </div>

                <h1 class="hero-title font-cinzel">
                    The Authentic Taste of Naples is <span class="text-gradient-fire">Coming Soon</span>
                </h1>

                <p class="hero-description">
                    We are putting the final touches on our artisanal wood-fired oven. Hand-stretched dough with 48-hour slow fermentation, San Marzano D.O.P. tomatoes, and buffalo mozzarella imported directly from Campania, Italy.
                </p>

                <!-- Countdown Timer -->
                <div class="countdown-wrapper">
                    <div class="countdown-header">
                        <span class="countdown-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            Oven Pre-Heating Countdown
                        </span>
                        <span style="font-size: 0.75rem; color: #10b981; font-weight: 600;">● Live Timer</span>
                    </div>

                    <div class="countdown-grid">
                        <div class="time-card">
                            <div class="time-number" id="days">14</div>
                            <div class="time-label">Days</div>
                        </div>
                        <div class="time-card">
                            <div class="time-number" id="hours">08</div>
                            <div class="time-label">Hours</div>
                        </div>
                        <div class="time-card">
                            <div class="time-number" id="minutes">45</div>
                            <div class="time-label">Minutes</div>
                        </div>
                        <div class="time-card">
                            <div class="time-number" id="seconds">30</div>
                            <div class="time-label">Seconds</div>
                        </div>
                    </div>
                </div>

                <!-- VIP Access / Notification Form -->
                <div class="subscribe-form-wrapper">
                    <form id="notifyForm" class="subscribe-form" onsubmit="handleSubscribe(event)">
                        <div class="email-input-wrapper">
                            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                            <input 
                                type="email" 
                                id="emailInput" 
                                class="email-input" 
                                placeholder="Enter your email address..." 
                                required 
                                autocomplete="email"
                            >
                        </div>
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <span>Get VIP Invite</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </button>
                    </form>

                    <div class="form-hint">
                        <span>✨</span>
                        <span>Join 480+ pizza lovers. <span class="badge-perk">Get 20% OFF on Opening Week</span> + VIP Tasting Ticket.</span>
                    </div>
                </div>
            </div>

            <!-- Right: Hero Visual Showcase with Floating Badges -->
            <div class="hero-visual">
                <div class="image-frame-container">
                    <!-- Top Float Badge -->
                    <div class="float-badge float-badge-1">
                        <span>🔥</span>
                        <span>450°C Wood-Fired Oven</span>
                    </div>

                    <!-- Inner Image Container -->
                    <div class="image-frame-inner">
                        <img 
                            src="{{ asset('images/pizza-hero.jpg') }}" 
                            alt="Authentic Neapolitan Wood-Fired Pizza with buffalo mozzarella and basil" 
                            class="hero-img"
                            loading="eager"
                        >
                        <div class="image-overlay">
                            <div class="pizza-tagline font-cinzel">Margherita Verace D.O.P.</div>
                            <div class="pizza-subline">San Marzano • Buffalo Mozzarella • Fresh Basil • Extra Virgin Olive Oil</div>
                        </div>
                    </div>

                    <!-- Bottom Float Badge -->
                    <div class="float-badge float-badge-2">
                        <span>🇮🇹</span>
                        <span>100% Italian Caputo Flour</span>
                    </div>
                </div>
            </div>
        </main>

        <!-- Taste Banner -->
        <div class="taste-banner">
            <div class="taste-content">
                <h3 class="font-cinzel">🍕 What Makes Our Pizza Extraordinary?</h3>
                <p>Pure Italian tradition passed through generations of master pizzaiolos in Naples.</p>
            </div>
            <div class="taste-badges">
                <div class="taste-pill">🌾 48h Cold Fermentation</div>
                <div class="taste-pill">🍅 San Marzano Tomatoes</div>
                <div class="taste-pill">🧀 Fresh Fior di Latte</div>
                <div class="taste-pill">🌿 Organic Genovese Basil</div>
            </div>
        </div>

        <!-- Four Key Pillars / Features Section -->
        <section class="features-section">
            <div class="section-header">
                <span class="section-tag">Craftsmanship & Ingredients</span>
                <h2 class="section-heading font-cinzel">The Four Secrets of Our Crust</h2>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">🌾</div>
                    <h3 class="feature-title">Caputo 00 Flour</h3>
                    <p class="feature-desc">Milled in Naples from select Italian grains for a dough that is light, easily digestible, and delicately charred.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">🔥</div>
                    <h3 class="feature-title">Volcanic Rock Oven</h3>
                    <p class="feature-desc">Fired with seasoned oak and beechwood at 450°C to flash-bake each pizza in under 90 seconds for that signature leopard crust.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">🍅</div>
                    <h3 class="feature-title">Vesuvius Tomatoes</h3>
                    <p class="feature-desc">Certified D.O.P. San Marzano tomatoes grown in volcanic soil under the southern Italian sun for sweet, balanced acidity.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrapper">🧀</div>
                    <h3 class="feature-title">Campania Mozzarella</h3>
                    <p class="feature-desc">Flown directly from Campania, delivering an exquisite creamy melt, velvety stretch, and rich buttery aroma.</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer>
            <div>
                <p>© {{ date('Y') }} <strong>Italian Pizza</strong> (Pizzeria Italiana). Fatto con amore e passione.</p>
                <p style="font-size: 0.78rem; margin-top: 0.25rem;">📍 Location: Food District Avenue • Opening for Dine-in & Takeaway Soon</p>
            </div>

            <div class="footer-socials">
                <a href="#" class="social-link" title="Instagram" aria-label="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                    </svg>
                </a>
                <a href="#" class="social-link" title="Facebook" aria-label="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                    </svg>
                </a>
                <a href="#" class="social-link" title="WhatsApp" aria-label="WhatsApp">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m4.93 4.93 4.24 4.24"></path>
                        <path d="m14.83 9.17 4.24-4.24"></path>
                        <path d="m14.83 14.83 4.24 4.24"></path>
                        <path d="m9.17 14.83-4.24 4.24"></path>
                    </svg>
                </a>
            </div>
        </footer>
    </div>

    <!-- Toast Feedback Notification -->
    <div id="toastNotification" class="toast-notification">
        <div class="toast-icon">✓</div>
        <div>
            <div class="toast-title">Grazie Mille! You're on the VIP List!</div>
            <div class="toast-desc">We'll send your 20% opening discount code & launch date soon.</div>
        </div>
    </div>

    <!-- Interactive Scripts -->
    <script>
        // Target launch date: 15 days from now
        const launchDate = new Date();
        launchDate.setDate(launchDate.getDate() + 15);
        launchDate.setHours(18, 0, 0, 0); // 6:00 PM opening

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = launchDate.getTime() - now;

            if (distance < 0) {
                document.getElementById('days').innerText = "00";
                document.getElementById('hours').innerText = "00";
                document.getElementById('minutes').innerText = "00";
                document.getElementById('seconds').innerText = "00";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').innerText = String(days).padStart(2, '0');
            document.getElementById('hours').innerText = String(hours).padStart(2, '0');
            document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
            document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
        }

        // Initialize countdown & tick every second
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Handle Email Subscription
        function handleSubscribe(event) {
            event.preventDefault();
            const emailInput = document.getElementById('emailInput');
            const submitBtn = document.getElementById('submitBtn');
            const toast = document.getElementById('toastNotification');
            
            const email = emailInput.value.trim();
            if (!email) return;

            // Button feedback animation
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span>Saving...</span>`;

            setTimeout(() => {
                // Save to localStorage for persistence
                try {
                    const existingList = JSON.parse(localStorage.getItem('vip_subscribers') || '[]');
                    existingList.push({ email: email, date: new Date().toISOString() });
                    localStorage.setItem('vip_subscribers', JSON.stringify(existingList));
                } catch (e) {
                    console.log(e);
                }

                emailInput.value = '';
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <span>Joined!</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                `;

                // Show toast notification
                toast.classList.add('active');
                setTimeout(() => {
                    toast.classList.remove('active');
                    submitBtn.innerHTML = `
                        <span>Get VIP Invite</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    `;
                }, 4000);
            }, 600);
        }
    </script>
</body>
</html>
