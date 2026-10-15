@extends('layouts.app')

@section('title', 'Pizza Hut Bangladesh | Order Best Pizzas & Deals Online')

@push('styles')
<style>
    /* Hero Banner matching Image 1 */
    .hero-banner-section {
        padding: 24px 0 32px 0;
    }

    .promo-banner {
        background: linear-gradient(135deg, #d31027 0%, #ea384d 100%);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(211, 16, 39, 0.25);
    }

    .promo-banner-inner {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        align-items: center;
        padding: 40px 48px;
        position: relative;
        z-index: 2;
    }

    .banner-text-box {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        padding: 24px 30px;
        display: inline-block;
        max-width: 440px;
    }

    .banner-title-super {
        font-size: 2.4rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -1px;
        text-transform: uppercase;
        color: #fff;
    }

    .banner-price-hero {
        font-size: 4.5rem;
        font-weight: 900;
        line-height: 0.95;
        color: #ffffff;
        display: flex;
        align-items: baseline;
        margin: 8px 0;
        text-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .banner-taka-sym {
        font-size: 2.2rem;
        font-weight: 700;
        margin-right: 4px;
    }

    .banner-subtitle {
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-top: 2px solid rgba(255, 255, 255, 0.4);
        padding-top: 8px;
        color: #ffe6e8;
    }

    .banner-pizzas-showcase {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .hero-pizza-quad {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        max-width: 480px;
    }

    .hero-pan-item {
        width: 190px;
        height: 190px;
        border-radius: 50%;
        background: #111;
        padding: 8px;
        box-shadow: 0 12px 28px rgba(0,0,0,0.4);
        overflow: hidden;
        border: 3px solid #222;
        transition: transform 0.3s ease;
    }

    .hero-pan-item:hover {
        transform: scale(1.05);
    }

    .hero-pan-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .banner-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 14px;
    }

    .banner-dot {
        width: 28px;
        height: 6px;
        border-radius: 4px;
        background: #ddd;
    }

    .banner-dot.active {
        background: var(--brand-red);
    }

    /* Dynamic Banner Slider (Admin-managed banners, full image - no cropping) */
    .banner-slider {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(211, 16, 39, 0.25);
    }

    .banner-slide {
        display: none;
    }

    .banner-slide.active {
        display: block;
        animation: bannerSlideFade 0.5s ease;
    }

    @keyframes bannerSlideFade {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Full banner image - natural aspect ratio, never cropped */
    .banner-full-img {
        display: block;
        width: 100%;
        height: auto;
    }

    .banner-slider > .banner-dots {
        position: absolute;
        bottom: 14px;
        left: 0;
        right: 0;
        z-index: 3;
    }

    .banner-slider .banner-dot {
        border: none;
        cursor: pointer;
        padding: 0;
        opacity: 0.85;
    }

    .banner-slider .banner-dot:hover {
        opacity: 1;
    }

    /* Explore Categories Section matching Image 1 */
    .section-wrap {
        padding: 24px 0 36px 0;
    }

    .section-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-title-line {
        font-size: 1.35rem;
        font-weight: 800;
        color: #222;
        position: relative;
        display: inline-block;
        padding-bottom: 6px;
    }

    .section-title-line::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--brand-red);
        border-radius: 2px;
    }

    .view-all-link {
        color: var(--brand-red);
        font-size: 0.88rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .view-all-link:hover {
        text-decoration: underline;
    }

    /* Categories Carousel - single line with side arrows */
    .categories-carousel-wrap {
        position: relative;
        padding: 4px 44px;
    }

    .categories-carousel {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .categories-carousel::-webkit-scrollbar {
        display: none;
    }

    .cat-nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e8e8e8;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        color: var(--brand-red);
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
        transition: all 0.2s ease;
    }

    .cat-nav-arrow:hover:not(:disabled) {
        background: var(--brand-red);
        border-color: var(--brand-red);
        color: #fff;
    }

    .cat-nav-arrow:disabled {
        opacity: 0.35;
        cursor: default;
    }

    .cat-prev { left: 0; }
    .cat-next { right: 0; }

    .category-box-card {
        flex: 0 0 190px;
        background: #ffffff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        padding: 20px 12px;
        text-align: center;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .category-box-card:hover {
        border-color: var(--brand-red);
        box-shadow: 0 6px 18px rgba(200, 16, 46, 0.12);
        transform: translateY(-3px);
    }

    .category-thumb-wrapper {
        width: 110px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .category-thumb-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .category-box-card:hover .category-thumb-wrapper img {
        transform: scale(1.08);
    }

    .category-card-name {
        font-weight: 800;
        font-size: 0.88rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #222;
    }

    /* Deals Grid matching Image 1 */
    .deals-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .deal-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .deal-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .deal-banner-header {
        position: relative;
        height: 130px;
        background: linear-gradient(135deg, #c8102e 0%, #f02d4a 100%);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .deal-banner-header img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .deal-badge-overlay {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #fff;
        color: var(--brand-red);
        font-size: 0.72rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .deal-card-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .deal-card-title {
        font-size: 0.98rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .deal-card-desc {
        font-size: 0.75rem;
        color: #666;
        line-height: 1.4;
        margin-bottom: 16px;
        flex: 1;
    }

    .deal-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
    }

    .deal-price-val {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--brand-red);
    }

    .deal-price-val span {
        font-size: 0.85rem;
    }

    .btn-red-add {
        background: var(--brand-red);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 8px 20px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-red-add:hover {
        background: var(--brand-red-dark);
    }

    /* Best Seller Grid matching Image 1 */
    .bestseller-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .pizza-item-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        transition: all 0.25s ease;
        position: relative;
    }

    .pizza-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .pizza-img-holder {
        height: 180px;
        position: relative;
        overflow: hidden;
        background: #f7f7f7;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .pizza-img-holder img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.4s ease;
    }

    .pizza-item-card:hover .pizza-img-holder img {
        transform: scale(1.05);
    }

    .badge-new-arrival {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #0ea943;
        color: #fff;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pizza-card-info {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .pizza-card-title {
        font-size: 0.98rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 12px;
        min-height: 40px;
        line-height: 1.3;
    }

    /* Size & Crust dropdowns */
    .selector-group {
        margin-bottom: 10px;
    }

    .selector-label {
        font-size: 0.72rem;
        color: #777;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
    }

    .custom-select {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #dcdcdc;
        border-radius: 6px;
        font-size: 0.82rem;
        color: #333;
        background: #fff;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .custom-select:focus {
        border-color: var(--brand-red);
    }

    .pizza-card-footer {
        margin-top: 14px;
    }

    .btn-pizza-add {
        width: 100%;
        background: var(--brand-red);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 14px;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-pizza-add:hover {
        background: var(--brand-red-dark);
    }

    /* Centered View All Menu Button */
    .view-all-center-box {
        text-align: center;
        padding: 40px 0;
    }

    .btn-all-menu {
        background: var(--brand-red);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 12px 36px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: 0 4px 14px rgba(200, 16, 46, 0.25);
    }

    .btn-all-menu:hover {
        background: var(--brand-red-dark);
    }

    /* CUSTOMIZE YOUR PIZZA MODAL (Exact match to Image 2) */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
        padding: 16px;
    }

    .modal-backdrop.open {
        opacity: 1;
        pointer-events: auto;
    }

    .modal-card {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        position: relative;
        display: flex;
        flex-direction: column;
        animation: modalScaleUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes modalScaleUp {
        from { transform: scale(0.92); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-header-top {
        text-align: center;
        padding: 24px 24px 16px 24px;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
    }

    .modal-close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        font-size: 1.4rem;
        color: #999;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .modal-close-btn:hover {
        background: #f0f0f0;
        color: #222;
    }

    .modal-brand-logo {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        color: var(--brand-red);
        font-size: 1.3rem;
        display: inline-block;
        margin-bottom: 4px;
    }

    .modal-heading {
        font-size: 1.15rem;
        font-weight: 800;
        color: #222;
    }

    .modal-body-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        padding: 24px;
        gap: 24px;
    }

    /* Modal Left */
    .modal-left-col {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .modal-preview-box {
        text-align: center;
        background: #fafafa;
        border: 1px solid #eeeeee;
        border-radius: 12px;
        padding: 16px;
    }

    .modal-preview-img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 12px;
        margin: 0 auto 12px auto;
        display: block;
    }

    .modal-item-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 4px;
    }

    .modal-item-meta {
        font-size: 0.78rem;
        color: #666;
        line-height: 1.4;
    }

    .instruction-box {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .instruction-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #333;
    }

    .instruction-textarea {
        width: 100%;
        height: 80px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.82rem;
        resize: none;
        font-family: inherit;
        outline: none;
    }

    .instruction-textarea:focus {
        border-color: var(--brand-red);
    }

    .char-counter {
        font-size: 0.7rem;
        color: #999;
        text-align: right;
    }

    /* Modal Left Bottom Action Row */
    .modal-action-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: auto;
    }

    .qty-stepper {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
    }

    .qty-btn {
        width: 32px;
        height: 36px;
        background: #f8f8f8;
        border: none;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #444;
        transition: background 0.2s;
    }

    .qty-btn:hover {
        background: #e9ecef;
    }

    .qty-val-display {
        width: 36px;
        text-align: center;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .btn-modal-add {
        flex: 1;
        background: var(--brand-red);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 16px;
        font-weight: 800;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-modal-add:hover {
        background: var(--brand-red-dark);
    }

    /* Modal Right (Add Ons) */
    .modal-right-col {
        border-left: 1px solid #f0f0f0;
        padding-left: 20px;
        display: flex;
        flex-direction: column;
    }

    .addons-heading {
        font-size: 0.95rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .addons-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 340px;
        overflow-y: auto;
        padding-right: 6px;
    }

    .addon-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.2s;
    }

    .addon-row:hover {
        background: #fbfbfb;
        border-color: #ddd;
    }

    .addon-row.checked {
        border-color: var(--brand-red);
        background: var(--brand-red-light);
    }

    .addon-label-side {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
    }

    .addon-price-tag {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--brand-red);
    }

    @media (max-width: 992px) {
        .promo-banner-inner {
            grid-template-columns: 1fr;
            padding: 30px 24px;
            gap: 24px;
        }
        .deals-grid, .bestseller-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .modal-body-split {
            grid-template-columns: 1fr;
        }
        .modal-right-col {
            border-left: none;
            padding-left: 0;
            border-top: 1px solid #f0f0f0;
            padding-top: 16px;
        }
    }

    @media (max-width: 576px) {
        .deals-grid, .bestseller-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container">

    <!-- 1. Hero Banner Slider (Managed from Admin Panel) -->
    @if($banners->isNotEmpty())
    <section class="hero-banner-section">
        <div class="banner-slider" id="heroBannerSlider">
            @foreach($banners as $banner)
            <div class="banner-slide {{ $loop->first ? 'active' : '' }}">
                <img src="{{ asset('uploads/' . $banner->image) }}" alt="Promotional Banner" class="banner-full-img">
            </div>
            @endforeach

            @if($banners->count() > 1)
            <div class="banner-dots">
                @foreach($banners as $banner)
                <button type="button" class="banner-dot {{ $loop->first ? 'active' : '' }}" onclick="showBanner({{ $loop->index }})" aria-label="Banner {{ $loop->iteration }}"></button>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- 2. Explore Categories (Matching Image 1) -->
    <section class="section-wrap">
        <div class="section-header-bar">
            <h2 class="section-title-line">Explore Categories</h2>
            <a href="#bestsellers" class="view-all-link">View All »</a>
        </div>

        @if($categories->isNotEmpty())
        <div class="categories-carousel-wrap">
            <button type="button" class="cat-nav-arrow cat-prev" id="catPrevBtn" onclick="scrollCategories(-1)" aria-label="Previous categories" disabled>
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="categories-carousel" id="categoriesCarousel">
                @foreach($categories as $cat)
                    <a href="#cat-{{ $cat->slug }}" class="category-box-card">
                        <div class="category-thumb-wrapper">
                            @if($cat->image && file_exists(public_path('uploads/' . $cat->image)))
                                <img src="{{ asset('uploads/' . $cat->image) }}" alt="{{ $cat->name }}" style="max-height:80px;object-fit:contain;">
                            @elseif($cat->image && file_exists(public_path('images/' . $cat->image)))
                                <img src="{{ asset('images/' . $cat->image) }}" alt="{{ $cat->name }}" style="max-height:80px;object-fit:contain;">
                            @else
                                <img src="{{ asset('images/pizza-hero.jpg') }}" alt="{{ $cat->name }}" style="max-height:80px;object-fit:contain;">
                            @endif
                        </div>
                        <span class="category-card-name">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>

            <button type="button" class="cat-nav-arrow cat-next" id="catNextBtn" onclick="scrollCategories(1)" aria-label="More categories">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
        @endif
    </section>

    <!-- Combo Meals Section -->
    @if(isset($combos) && $combos->isNotEmpty())
    <section class="section-wrap" id="combos">
        <div class="section-header-bar">
            <h2 class="section-title-line"><i class="fa-solid fa-layer-group" style="color:var(--brand-red);margin-right:8px;"></i>Combo Meals</h2>
        </div>

        <div class="deals-grid">
            @foreach($combos as $combo)
                <a href="{{ route('combo.show', $combo->id) }}" class="deal-card" style="text-decoration:none; color:inherit;">
                    <div class="deal-banner-header">
                        @if($combo->image && file_exists(public_path('uploads/' . $combo->image)))
                            <img src="{{ asset('uploads/' . $combo->image) }}" alt="{{ $combo->name }}">
                        @else
                            <img src="{{ asset('images/pizza-hero.jpg') }}" alt="{{ $combo->name }}">
                        @endif
                        <span class="deal-badge-overlay"><i class="fa-solid fa-layer-group"></i> Choose {{ $combo->max_select }} Items</span>
                    </div>

                    <div class="deal-card-body">
                        <h3 class="deal-card-title">{{ $combo->name }}</h3>
                        @if($combo->description)
                            <p class="deal-card-desc">{{ Str::limit($combo->description, 80) }}</p>
                        @endif

                        <div class="deal-card-footer">
                            <div class="deal-price-val">
                                <span>৳</span> {{ number_format($combo->price, 0) }}
                            </div>
                            <span class="btn-red-add">
                                <i class="fa-solid fa-arrow-right"></i> Order Now
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- 4. Best Seller (Matching Image 1) -->
    <section class="section-wrap" id="bestsellers">
        <div class="section-header-bar">
            <h2 class="section-title-line">Best Seller</h2>
        </div>

        <div class="bestseller-grid">
            @foreach($bestSellers as $pizza)
                @php
                    $hasVariations = $pizza->variations->isNotEmpty();
                    $groupedVariations = $hasVariations ? $pizza->variations->groupBy('size_id') : null;
                    $defaultImage = $pizza->image ? asset('uploads/' . $pizza->image) : asset('images/pizza-hero.jpg');
                @endphp
                <div class="pizza-item-card" id="card-prod-{{ $pizza->id }}">
                    <div class="pizza-img-holder">
                        @if($pizza->is_new)
                            <span class="badge-new-arrival">NEW ARRIVAL</span>
                        @endif
                        <img src="{{ $defaultImage }}" alt="{{ $pizza->name }}" id="pizzaCardImg-{{ $pizza->id }}" style="transition:opacity 0.25s ease, transform 0.3s ease;">
                    </div>

                    <div class="pizza-card-info">
                        <h3 class="pizza-card-title">{{ $pizza->name }}</h3>

                        <!-- Select Size dropdown -->
                        <div class="selector-group">
                            <label class="selector-label">Select Size</label>
                            <select class="custom-select size-select" onchange="onCardSizeChange({{ $pizza->id }})" id="size-{{ $pizza->id }}">
                                @if($hasVariations)
                                    @foreach($groupedVariations as $sizeId => $vars)
                                        @php $firstVar = $vars->first(); @endphp
                                        <option value="{{ $sizeId }}" data-name="{{ $firstVar->size_name }}" {{ $loop->first ? 'selected' : '' }}>
                                            {{ $firstVar->size_name }}
                                        </option>
                                    @endforeach
                                @else
                                    @foreach($pizza->sizes as $size)
                                        <option value="{{ $size->id }}" data-price="{{ $size->price }}" data-name="{{ $size->name }}" {{ $loop->first ? 'selected' : '' }}>
                                            {{ $size->name }} (৳{{ number_format($size->price) }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Select Crust dropdown (dynamically populated for variations) -->
                        <div class="selector-group">
                            <label class="selector-label">Select Your Crust</label>
                            <select class="custom-select crust-select" onchange="onCardCrustChange({{ $pizza->id }})" id="crust-{{ $pizza->id }}">
                                @if(!$hasVariations)
                                    @foreach($pizza->crusts as $crust)
                                        <option value="{{ $crust->id }}" data-extra="{{ $crust->extra_price }}" data-name="{{ $crust->name }}" {{ $loop->first ? 'selected' : '' }}>
                                            {{ $crust->name }} {{ $crust->extra_price > 0 ? '(+৳' . number_format($crust->extra_price) . ')' : '' }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="pizza-card-footer">
                            <button type="button" class="btn-pizza-add" id="btnAdd-{{ $pizza->id }}" onclick="openCustomizeModal({{ $pizza->id }})">
                                <span>Add</span>
                                <span class="pizza-calc-price" id="priceDisplay-{{ $pizza->id }}">
                                    ৳ {{ number_format($pizza->base_price) }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="view-all-center-box">
            <button type="button" class="btn-all-menu" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                View All Menu
            </button>
        </div>
    </section>

</div>

<!-- 5. CUSTOMIZE YOUR PIZZA MODAL (Exact match to Image 2) -->
<div class="modal-backdrop" id="customizeModal" onclick="closeModalOnBackdrop(event)">
    <div class="modal-card">
        <!-- Modal Header with Brand Logo & Close Button -->
        <div class="modal-header-top">
            <button type="button" class="modal-close-btn" onclick="closeCustomizeModal()">✕</button>
            <div class="modal-brand-logo">Pizza Hut</div>
            <h3 class="modal-heading">Customize Your Pizza</h3>
        </div>

        <div class="modal-body-split">
            <!-- Left Side: Preview, Instructions, Quantity, Add Button -->
            <div class="modal-left-col">
                <div class="modal-preview-box">
                    <img src="{{ asset('images/pizza-hero.jpg') }}" alt="Pizza preview" class="modal-preview-img" id="modalPizzaImg">
                    <h4 class="modal-item-name" id="modalPizzaName">Classic Margherita</h4>
                    <div class="modal-item-meta" id="modalPizzaMeta">
                        Pizza Mozzarella Cheese<br>
                        Size: <strong id="modalSelectedSize">Medium</strong> • Crust: <strong id="modalSelectedCrust">Sausage Crust</strong>
                    </div>
                </div>

                <div class="instruction-box">
                    <label class="instruction-label">Special Instructions</label>
                    <textarea 
                        class="instruction-textarea" 
                        id="modalInstructions" 
                        placeholder="e.g. No Jalapeno" 
                        maxlength="500" 
                        oninput="document.getElementById('charCount').innerText = this.value.length + '/500'"></textarea>
                    <div class="char-counter" id="charCount">0/500</div>
                </div>

                <div class="modal-action-row">
                    <div class="qty-stepper">
                        <button type="button" class="qty-btn" onclick="adjustModalQty(-1)">-</button>
                        <span class="qty-val-display" id="modalQtyDisplay">1</span>
                        <button type="button" class="qty-btn" onclick="adjustModalQty(1)">+</button>
                    </div>

                    <button type="button" class="btn-modal-add" id="btnModalSubmit" onclick="submitModalToCart()">
                        <span>Add</span>
                        <span id="modalTotalBtnPrice">৳ 1,249</span>
                    </button>
                </div>
            </div>

            <!-- Right Side: Add Ons List (Exact match to Image 2) -->
            <div class="modal-right-col">
                <div class="addons-heading">
                    <span>✨</span>
                    <span>Add On</span>
                </div>

                <div class="addons-list" id="modalAddonsList">
                    @foreach($addons as $addon)
                        <label class="addon-row" id="addon-label-{{ $addon->id }}">
                            <div class="addon-label-side">
                                <input type="checkbox" name="addons[]" value="{{ $addon->id }}" id="chk-addon-{{ $addon->id }}" onchange="toggleAddon({{ $addon->id }})" style="accent-color: var(--brand-red); width:18px; height:18px; cursor:pointer;">
                                <span><i class="fa-solid fa-cheese" style="color:var(--brand-red);"></i></span>
                                <span>{{ $addon->name }}</span>
                            </div>
                            <span class="addon-price-tag">+৳{{ number_format($addon->price) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const isCustomerLoggedIn = {{ Auth::guard('web')->check() ? 'true' : 'false' }};
    const customerLoginUrl = "{{ route('login') }}";

    // Hero banner slider (admin-managed banners)
    let bannerIndex = 0;
    const bannerSlides = document.querySelectorAll('#heroBannerSlider .banner-slide');
    const bannerDots = document.querySelectorAll('#heroBannerSlider .banner-dot');

    function showBanner(index) {
        bannerSlides.forEach((slide, i) => slide.classList.toggle('active', i === index));
        bannerDots.forEach((dot, i) => dot.classList.toggle('active', i === index));
        bannerIndex = index;
    }

    if (bannerSlides.length > 1) {
        setInterval(() => {
            showBanner((bannerIndex + 1) % bannerSlides.length);
        }, 5000);
    }

    // Categories carousel (horizontal scroll with side arrows)
    const catCarousel = document.getElementById('categoriesCarousel');
    const catPrevBtn = document.getElementById('catPrevBtn');
    const catNextBtn = document.getElementById('catNextBtn');

    function scrollCategories(direction) {
        if (!catCarousel) return;
        const card = catCarousel.querySelector('.category-box-card');
        const step = card ? (card.offsetWidth + 16) * 2 : 412; // scroll 2 cards per click
        catCarousel.scrollBy({ left: direction * step, behavior: 'smooth' });
    }

    function updateCategoryArrows() {
        if (!catCarousel || !catPrevBtn || !catNextBtn) return;
        catPrevBtn.disabled = catCarousel.scrollLeft <= 2;
        catNextBtn.disabled = catCarousel.scrollLeft >= catCarousel.scrollWidth - catCarousel.clientWidth - 2;
    }

    if (catCarousel) {
        catCarousel.addEventListener('scroll', updateCategoryArrows);
        window.addEventListener('resize', updateCategoryArrows);
        updateCategoryArrows();
    }

    // State for modal customization
    // Pizza data mapping with variations for dynamic switching
    const pizzaDataMap = {};
    @foreach($bestSellers as $pizza)
        pizzaDataMap[{{ $pizza->id }}] = {
            id: {{ $pizza->id }},
            name: @json($pizza->name),
            basePrice: {{ (float) $pizza->base_price }},
            baseImage: @json($pizza->image ? asset('uploads/' . $pizza->image) : asset('images/pizza-hero.jpg')),
            hasVariations: {{ $pizza->variations->isNotEmpty() ? 'true' : 'false' }},
            variations: @json($pizza->variations)
        };
    @endforeach

    // State for modal customization
    let currentPizza = null;
    let modalQuantity = 1;
    let modalBasePrice = 0;
    let modalCrustExtra = 0;
    let selectedAddonIds = new Set();
    let addonPriceMap = {};

    @foreach($addons as $ad)
        addonPriceMap[{{ $ad->id }}] = {{ (float)$ad->price }};
    @endforeach

    // When size is changed on a card, populate crusts for that size
    function onCardSizeChange(productId) {
        const pData = pizzaDataMap[productId];
        if (!pData) return;

        const sizeSelect = document.getElementById('size-' + productId);
        const crustSelect = document.getElementById('crust-' + productId);
        if (!sizeSelect || !crustSelect) return;

        const selectedSizeId = sizeSelect.value;

        if (pData.hasVariations) {
            // Find variations belonging to this size
            const sizeVars = pData.variations.filter(v => String(v.size_id) === String(selectedSizeId));
            crustSelect.innerHTML = '';

            sizeVars.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.crust_id;
                opt.dataset.name = v.crust_name;
                opt.dataset.price = v.price;
                opt.dataset.image = v.image || '';
                opt.innerText = v.crust_name;
                crustSelect.appendChild(opt);
            });

            if (sizeVars.length > 0) {
                crustSelect.selectedIndex = 0;
            }
        }

        onCardCrustChange(productId);
    }

    // When crust is changed on a card, update price and product image!
    function onCardCrustChange(productId) {
        const pData = pizzaDataMap[productId];
        if (!pData) return;

        const sizeSelect = document.getElementById('size-' + productId);
        const crustSelect = document.getElementById('crust-' + productId);
        const priceDisplay = document.getElementById('priceDisplay-' + productId);
        const imgEl = document.getElementById('pizzaCardImg-' + productId);

        if (!crustSelect) return;

        if (pData.hasVariations) {
            const crustOption = crustSelect.options[crustSelect.selectedIndex];
            if (crustOption) {
                const price = parseFloat(crustOption.dataset.price || pData.basePrice);
                if (priceDisplay) {
                    priceDisplay.innerText = '৳ ' + Math.round(price).toLocaleString();
                }

                // Dynamic variation image change!
                const varImage = crustOption.dataset.image;
                if (imgEl) {
                    if (varImage) {
                        imgEl.src = '/uploads/' + varImage;
                    } else {
                        imgEl.src = pData.baseImage;
                    }
                }
            }
        } else {
            // Legacy calculation
            const sizeOption = sizeSelect ? sizeSelect.options[sizeSelect.selectedIndex] : null;
            const crustOption = crustSelect ? crustSelect.options[crustSelect.selectedIndex] : null;

            const sizePrice = parseFloat(sizeOption ? sizeOption.dataset.price || 0 : 0);
            const crustExtra = parseFloat(crustOption ? crustOption.dataset.extra || 0 : 0);

            const total = sizePrice + crustExtra;
            if (priceDisplay) {
                priceDisplay.innerText = '৳ ' + Math.round(total).toLocaleString();
            }
        }
    }

    // Initialize all card dropdowns and prices on page load
    document.addEventListener('DOMContentLoaded', () => {
        Object.keys(pizzaDataMap).forEach(productId => {
            onCardSizeChange(productId);
        });
    });

    // Open Customize Modal (Image 2)
    function openCustomizeModal(productId) {
        if (!isCustomerLoggedIn) {
            window.location.href = customerLoginUrl;
            return;
        }

        const pData = pizzaDataMap[productId];
        currentPizza = pData;

        const sizeSelect = document.getElementById('size-' + productId);
        const crustSelect = document.getElementById('crust-' + productId);

        const sizeOption = sizeSelect ? sizeSelect.options[sizeSelect.selectedIndex] : null;
        const crustOption = crustSelect ? crustSelect.options[crustSelect.selectedIndex] : null;

        const currentSizeName = sizeOption ? (sizeOption.dataset.name || sizeOption.text) : 'Standard';
        const currentCrustName = crustOption ? (crustOption.dataset.name || crustOption.text) : 'Standard';

        if (pData && pData.hasVariations) {
            modalBasePrice = parseFloat(crustOption ? crustOption.dataset.price || pData.basePrice : pData.basePrice);
            modalCrustExtra = 0;
            const varImg = crustOption ? crustOption.dataset.image : '';
            document.getElementById('modalPizzaImg').src = varImg ? ('/uploads/' + varImg) : pData.baseImage;
        } else {
            modalBasePrice = parseFloat(sizeOption ? sizeOption.dataset.price || pData.basePrice : (pData ? pData.basePrice : 0));
            modalCrustExtra = parseFloat(crustOption ? crustOption.dataset.extra || 0 : 0);
            document.getElementById('modalPizzaImg').src = pData ? pData.baseImage : '{{ asset('images/pizza-hero.jpg') }}';
        }

        document.getElementById('modalPizzaName').innerText = pData ? pData.name : '';
        document.getElementById('modalSelectedSize').innerText = currentSizeName;
        document.getElementById('modalSelectedCrust').innerText = currentCrustName;

        // Reset modal state
        modalQuantity = 1;
        document.getElementById('modalQtyDisplay').innerText = '1';
        document.getElementById('modalInstructions').value = '';
        document.getElementById('charCount').innerText = '0/500';

        // Uncheck all addons
        selectedAddonIds.clear();
        document.querySelectorAll('#modalAddonsList input[type="checkbox"]').forEach(chk => {
            chk.checked = false;
            chk.closest('.addon-row').classList.remove('checked');
        });

        recalcModalTotal();
        document.getElementById('customizeModal').classList.add('open');
    }

    function closeCustomizeModal() {
        document.getElementById('customizeModal').classList.remove('open');
    }

    function closeModalOnBackdrop(e) {
        if (e.target.id === 'customizeModal') {
            closeCustomizeModal();
        }
    }

    // Toggle add-on checkbox
    function toggleAddon(addonId) {
        const chk = document.getElementById('chk-addon-' + addonId);
        const row = document.getElementById('addon-label-' + addonId);
        if (!chk || !row) return;

        if (chk.checked) {
            selectedAddonIds.add(addonId);
            row.classList.add('checked');
        } else {
            selectedAddonIds.delete(addonId);
            row.classList.remove('checked');
        }
        recalcModalTotal();
    }

    // Recalculate modal total price
    function recalcModalTotal() {
        let addonsTotal = 0;
        selectedAddonIds.forEach(id => {
            addonsTotal += (addonPriceMap[id] || 0);
        });

        const singleItem = modalBasePrice + modalCrustExtra + addonsTotal;
        const total = singleItem * modalQuantity;

        const priceEl = document.getElementById('modalTotalBtnPrice');
        if (priceEl) {
            priceEl.innerText = '৳ ' + Math.round(total).toLocaleString();
        }
    }

    // Adjust modal quantity
    function adjustModalQty(delta) {
        modalQuantity += delta;
        if (modalQuantity < 1) modalQuantity = 1;
        document.getElementById('modalQtyDisplay').innerText = modalQuantity;
        recalcModalTotal();
    }

    // Submit modal pizza to cart via AJAX (real-time)
    function submitModalToCart() {
        if (!isCustomerLoggedIn) {
            window.location.href = customerLoginUrl;
            return;
        }

        if (!currentPizza) return;

        const sizeSelect = document.getElementById('size-' + currentPizza.id);
        const crustSelect = document.getElementById('crust-' + currentPizza.id);

        const btn = document.getElementById('btnModalSubmit');
        const priceEl = document.getElementById('modalTotalBtnPrice');
        const currentPriceText = priceEl ? priceEl.innerText : '';

        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> <span>Adding...</span>`;

        const sizeOption = sizeSelect ? sizeSelect.options[sizeSelect.selectedIndex] : null;
        const crustOption = crustSelect ? crustSelect.options[crustSelect.selectedIndex] : null;

        const payload = {
            product_id: currentPizza.id,
            size_id: sizeSelect ? sizeSelect.value : null,
            size_name: sizeOption ? (sizeOption.dataset.name || sizeOption.text) : '',
            crust_id: crustSelect ? crustSelect.value : null,
            crust_name: crustOption ? (crustOption.dataset.name || crustOption.text) : '',
            quantity: modalQuantity,
            addons: Array.from(selectedAddonIds),
            special_instructions: document.getElementById('modalInstructions').value,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': payload._token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => {
            if (res.status === 401) {
                showToast('কার্টে পণ্য যোগ করতে অনুগ্রহ করে প্রথমে লগইন করুন।', false);
                setTimeout(() => {
                    window.location.href = customerLoginUrl;
                }, 800);
                throw new Error('Unauthenticated');
            }
            return res.json();
        })
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = `<span>Add</span> <span id="modalTotalBtnPrice">${currentPriceText}</span>`;
            
            if (data.status === 'auth_required') {
                showToast(data.message || 'কার্টে পণ্য যোগ করতে অনুগ্রহ করে প্রথমে লগইন করুন।', false);
                setTimeout(() => {
                    window.location.href = data.redirect || customerLoginUrl;
                }, 800);
                return;
            }

            if (data.success) {
                // Real-time update of floating cart badge
                const cartCountEl = document.getElementById('headerCartCount');
                if (cartCountEl) {
                    cartCountEl.innerText = data.cartCount;
                }

                // Bounce animation on floating cart button
                const floatCart = document.getElementById('floatingCartBtn');
                if (floatCart) {
                    floatCart.style.transition = 'all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    floatCart.style.transform = 'translateY(-50%) scale(1.3)';
                    setTimeout(() => {
                        floatCart.style.transform = 'translateY(-50%) scale(1)';
                    }, 300);
                }

                closeCustomizeModal();
                showToast('✓ ' + (currentPizza ? currentPizza.name : 'Pizza') + ' added to cart!');
            } else {
                showToast(data.message || 'Could not add to cart.', false);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = `<span>Add</span> <span id="modalTotalBtnPrice">${currentPriceText}</span>`;
            console.error(err);
            if (err.message !== 'Unauthenticated') {
                showToast('Failed to add item to cart. Please try again.', false);
            }
        });
    }

    // Quick add for deals
    function quickAddDeal(dealId, dealName, price) {
        if (!isCustomerLoggedIn) {
            window.location.href = customerLoginUrl;
            return;
        }

        const payload = {
            product_id: dealId,
            quantity: 1,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': payload._token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => {
            if (res.status === 401) {
                showToast('কার্টে পণ্য যোগ করতে অনুগ্রহ করে প্রথমে লগইন করুন।', false);
                setTimeout(() => {
                    window.location.href = customerLoginUrl;
                }, 800);
                throw new Error('Unauthenticated');
            }
            return res.json();
        })
        .then(data => {
            if (data.status === 'auth_required') {
                showToast(data.message || 'কার্টে পণ্য যোগ করতে অনুগ্রহ করে প্রথমে লগইন করুন।', false);
                setTimeout(() => {
                    window.location.href = data.redirect || customerLoginUrl;
                }, 800);
                return;
            }
            if (data.success) {
                document.getElementById('headerCartCount').innerText = data.cartCount;
                showToast('✓ ' + dealName + ' added to cart!');
            }
        })
        .catch(err => {
            console.error(err);
        });
    }
</script>
@endpush
