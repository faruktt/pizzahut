@extends('layouts.app')

@php
    $pageTitle = $category ? ($category->name . ' | Italian Pizza Hut') : 'Our Menu | Italian Pizza Hut';
    $catName = $category ? $category->name : 'All Menu';
    $catSlug = $category ? $category->slug : 'all';
    $totalCount = $products->count() + ($combos ? $combos->count() : 0);
@endphp

@section('title', $pageTitle)

@push('styles')
<style>
    /* Category Page Hero & Breadcrumbs */
    .category-page-wrap {
        padding: 24px 0 60px 0;
    }

    .breadcrumb-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #777;
        margin-bottom: 20px;
    }

    .breadcrumb-bar a {
        color: #555;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-bar a:hover {
        color: var(--brand-red);
    }

    .breadcrumb-bar i {
        font-size: 0.65rem;
        color: #aaa;
    }

    .breadcrumb-bar span {
        color: var(--brand-red);
        font-weight: 600;
    }

    /* Category Banner Header */
    .category-hero-header {
        background: linear-gradient(135deg, #1e1e1e 0%, #111111 100%);
        border-radius: 16px;
        padding: 32px 36px;
        color: #fff;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        border-left: 6px solid var(--brand-red);
    }

    .category-hero-content {
        max-width: 600px;
        z-index: 2;
    }

    .category-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(200, 16, 46, 0.2);
        border: 1px solid rgba(200, 16, 46, 0.4);
        color: #ff6b7e;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 12px;
    }

    .category-hero-title {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
        color: #ffffff;
    }

    .category-hero-desc {
        color: #b3b3b3;
        font-size: 0.95rem;
        line-height: 1.5;
        margin: 0;
    }

    .category-hero-media {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .category-hero-thumb {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        border: 3px solid rgba(255,255,255,0.15);
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }

    .category-hero-thumb img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Horizontal Category Switcher Bar */
    .cat-nav-tabs-wrapper {
        margin-bottom: 30px;
        position: sticky;
        top: 72px;
        z-index: 50;
        background: #fbfbfb;
        padding: 10px 0;
    }

    .cat-nav-tabs {
        display: flex;
        align-items: center;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 6px;
        scrollbar-width: thin;
        scrollbar-color: #ddd transparent;
    }

    .cat-nav-tabs::-webkit-scrollbar {
        height: 4px;
    }

    .cat-nav-tabs::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }

    .cat-tab-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1.5px solid #eaeaea;
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 0.88rem;
        font-weight: 700;
        color: #444;
        white-space: nowrap;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .cat-tab-item img {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    .cat-tab-item:hover {
        border-color: var(--brand-red);
        color: var(--brand-red);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(200, 16, 46, 0.12);
    }

    .cat-tab-item.active {
        background: var(--brand-red);
        border-color: var(--brand-red);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(200, 16, 46, 0.28);
    }

    .cat-tab-item.active img {
        filter: brightness(0) invert(1);
    }

    /* Filter & Controls Toolbar */
    .category-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        background: #ffffff;
        padding: 14px 20px;
        border-radius: 12px;
        border: 1px solid #eaeaea;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .toolbar-left-info {
        font-size: 0.9rem;
        color: #555;
        font-weight: 600;
    }

    .toolbar-left-info strong {
        color: var(--brand-red);
    }

    .toolbar-right-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-filter-input {
        padding: 8px 14px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.85rem;
        outline: none;
        width: 220px;
        transition: border-color 0.2s;
    }

    .search-filter-input:focus {
        border-color: var(--brand-red);
    }

    .sort-select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #333;
        background: #fff;
        outline: none;
        cursor: pointer;
    }

    .sort-select:focus {
        border-color: var(--brand-red);
    }

    /* Product Grid */
    .products-grid {
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
        z-index: 2;
    }

    .badge-bestseller {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--brand-red);
        color: #fff;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
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
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .pizza-card-desc {
        font-size: 0.76rem;
        color: #777;
        line-height: 1.4;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 34px;
    }

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
        margin-top: auto;
        padding-top: 12px;
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

    /* Combos / Deals Grid */
    .deals-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 36px;
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

    .btn-red-add {
        background: var(--brand-red);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 8px 18px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }

    .btn-red-add:hover {
        background: var(--brand-red-dark);
    }

    /* Empty State */
    .empty-category-box {
        background: #ffffff;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 50px 24px;
        text-align: center;
        margin: 20px 0 40px 0;
    }

    .empty-cat-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--brand-red-light);
        color: var(--brand-red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin-bottom: 20px;
    }

    .empty-cat-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-cat-subtitle {
        color: #64748b;
        font-size: 0.92rem;
        max-width: 480px;
        margin: 0 auto 24px auto;
        line-height: 1.5;
    }

    .empty-action-btns {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-primary-pill {
        background: var(--brand-red);
        color: #fff;
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary-pill:hover {
        background: var(--brand-red-dark);
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-secondary-pill {
        background: #f1f5f9;
        color: #334155;
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-secondary-pill:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Modal Styles */
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
        font-family: 'Playfair Display', Georgia, serif;
        font-weight: 800;
        color: var(--brand-red);
        font-size: 1.3rem;
        display: inline-block;
        margin-bottom: 4px;
    }

    .modal-heading {
        font-family: 'Playfair Display', Georgia, serif;
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
        .products-grid, .deals-grid {
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
        .products-grid, .deals-grid {
            grid-template-columns: 1fr;
        }
        .category-hero-header {
            flex-direction: column;
            text-align: center;
            padding: 24px 20px;
        }
        .category-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .search-filter-input {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container category-page-wrap">

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="{{ route('menu.index') }}">Menu</a>
        <i class="fa-solid fa-chevron-right"></i>
        <span>{{ $catName }}</span>
    </div>

    <!-- Category Hero Header -->
    <div class="category-hero-header">
        <div class="category-hero-content">
            <div class="category-badge-pill">
                <i class="fa-solid fa-fire"></i>
                <span>{{ $totalCount }} {{ Str::plural('Item', $totalCount) }} Available</span>
            </div>
            <h1 class="category-hero-title">{{ $catName }}</h1>
            <p class="category-hero-desc">
                @if($category && $category->description)
                    {{ $category->description }}
                @elseif($category && strtolower($category->slug) === 'pizza')
                    Indulge in our oven-baked authentic Italian pan pizzas, loaded with 100% mozzarella cheese and signature sauces.
                @elseif($category && strtolower($category->slug) === 'deals')
                    Unbeatable value deals & combo meals crafted for family and friends.
                @elseif($category && strtolower($category->slug) === 'melts')
                    Crispy, cheesy, loaded Melts folded to perfection and served hot with savory dips.
                @elseif($category && strtolower($category->slug) === 'pasta')
                    Classic Italian pasta tossed in creamy parmesan, herbs, and slow-simmered sauces.
                @else
                    Freshly prepared with authentic ingredients, served piping hot and full of flavor.
                @endif
            </p>
        </div>

        <div class="category-hero-media">
            <div class="category-hero-thumb">
                @if($category && $category->image && file_exists(public_path('uploads/' . $category->image)))
                    <img src="{{ asset('uploads/' . $category->image) }}" alt="{{ $catName }}">
                @elseif($category && $category->image && file_exists(public_path('images/' . $category->image)))
                    <img src="{{ asset('images/' . $category->image) }}" alt="{{ $catName }}">
                @else
                    <img src="{{ asset('images/pizza-hero.jpg') }}" alt="{{ $catName }}">
                @endif
            </div>
        </div>
    </div>

    <!-- Category Navigation Pill Tabs (Horizontal Switcher) -->
    <div class="cat-nav-tabs-wrapper">
        <div class="cat-nav-tabs">
            <a href="{{ route('menu.index') }}" class="cat-tab-item {{ !$category ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i> All Menu
            </a>
            @foreach($categories as $c)
                @php
                    $isActive = $category && $category->id === $c->id;
                    $cImage = $c->image && file_exists(public_path('uploads/' . $c->image)) 
                                ? asset('uploads/' . $c->image) 
                                : ($c->image && file_exists(public_path('images/' . $c->image)) ? asset('images/' . $c->image) : null);
                @endphp
                <a href="{{ route('category.show', $c->slug) }}" class="cat-tab-item {{ $isActive ? 'active' : '' }}">
                    @if($cImage)
                        <img src="{{ $cImage }}" alt="{{ $c->name }}">
                    @else
                        <i class="fa-solid fa-pizza-slice"></i>
                    @endif
                    <span>{{ $c->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Controls Toolbar (Search & Sort) -->
    <div class="category-toolbar">
        <div class="toolbar-left-info">
            Showing <strong id="itemCountDisplay">{{ $products->count() }}</strong> products in <strong>{{ $catName }}</strong>
        </div>

        <div class="toolbar-right-actions">
            <input type="text" 
                   id="categorySearchInput" 
                   class="search-filter-input" 
                   placeholder="Search in {{ $catName }}..." 
                   oninput="filterCategoryProducts()">

            <select id="sortSelect" class="sort-select" onchange="applySort(this.value)">
                <option value="default" {{ request('sort') == '' ? 'selected' : '' }}>Default Sorting</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </div>
    </div>

    <!-- If Deals Category / Combos Exist -->
    @if(isset($combos) && $combos->isNotEmpty())
    <div style="margin-bottom: 30px;">
        <h3 class="heading-font" style="font-size: 1.4rem; font-weight: 800; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-layer-group" style="color:var(--brand-red);"></i> Special Combo Meals
        </h3>
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
    </div>
    @endif

    <!-- Products Grid -->
    @if($products->isNotEmpty())
        <div class="products-grid" id="productsGrid">
            @foreach($products as $pizza)
                @php
                    $hasVariations = $pizza->variations->isNotEmpty();
                    $groupedVariations = $hasVariations ? $pizza->variations->groupBy('size_id') : null;
                    $defaultImage = $pizza->image ? asset('uploads/' . $pizza->image) : asset('images/pizza-hero.jpg');
                @endphp
                <div class="pizza-item-card product-filter-item" 
                     id="card-prod-{{ $pizza->id }}"
                     data-name="{{ strtolower($pizza->name) }}"
                     data-desc="{{ strtolower($pizza->description ?? '') }}"
                     data-price="{{ (float)$pizza->base_price }}">
                    
                    <div class="pizza-img-holder">
                        @if($pizza->is_new)
                            <span class="badge-new-arrival">NEW</span>
                        @endif
                        @if($pizza->is_bestseller)
                            <span class="badge-bestseller">BESTSELLER</span>
                        @endif
                        <img src="{{ $defaultImage }}" alt="{{ $pizza->name }}" id="pizzaCardImg-{{ $pizza->id }}">
                    </div>

                    <div class="pizza-card-info">
                        <h3 class="pizza-card-title">{{ $pizza->name }}</h3>
                        @if($pizza->description)
                            <p class="pizza-card-desc">{{ $pizza->description }}</p>
                        @endif

                        <!-- Size Dropdown -->
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

                        <!-- Crust Dropdown -->
                        <div class="selector-group">
                            <label class="selector-label">Select Crust</label>
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
    @elseif(!isset($combos) || $combos->isEmpty())
        <!-- Empty State -->
        <div class="empty-category-box">
            <div class="empty-cat-icon">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <h3 class="empty-cat-title">No items found in {{ $catName }}</h3>
            <p class="empty-cat-subtitle">
                Our kitchen is preparing exciting new authentic Italian recipes for this section! In the meantime, explore our best-selling pizzas and combo deals.
            </p>
            <div class="empty-action-btns">
                <a href="{{ route('category.show', 'pizza') }}" class="btn-primary-pill">
                    <i class="fa-solid fa-pizza-slice"></i> Browse All Pizzas
                </a>
                <a href="{{ route('menu.index') }}" class="btn-secondary-pill">
                    View Complete Menu
                </a>
                <a href="{{ route('home') }}" class="btn-secondary-pill">
                    Back to Home
                </a>
            </div>
        </div>
    @endif

</div>

<!-- Customize Modal -->
<div class="modal-backdrop" id="customizeModal" onclick="closeModalOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-header-top">
            <button type="button" class="modal-close-btn" onclick="closeCustomizeModal()">✕</button>
            <div class="modal-brand-logo">Italian Pizza Hut</div>
            <h3 class="modal-heading">Customize Your Pizza</h3>
        </div>

        <div class="modal-body-split">
            <!-- Left Side -->
            <div class="modal-left-col">
                <div class="modal-preview-box">
                    <img src="{{ asset('images/pizza-hero.jpg') }}" alt="Pizza preview" class="modal-preview-img" id="modalPizzaImg">
                    <h4 class="modal-item-name" id="modalPizzaName">Classic Margherita</h4>
                    <div class="modal-item-meta" id="modalPizzaMeta">
                        Size: <strong id="modalSelectedSize">Medium</strong> • Crust: <strong id="modalSelectedCrust">Sausage Crust</strong>
                    </div>
                </div>

                <div class="instruction-box">
                    <label class="instruction-label">Special Instructions</label>
                    <textarea 
                        class="instruction-textarea" 
                        id="modalInstructions" 
                        placeholder="e.g. No Jalapeno, extra oregano" 
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

            <!-- Right Side (Add-ons) -->
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

    // Pizza data mapping for dynamic size, crust, and image switching
    const pizzaDataMap = {};
    @foreach($products as $pizza)
        pizzaDataMap[{{ $pizza->id }}] = {
            id: {{ $pizza->id }},
            name: @json($pizza->name),
            basePrice: {{ (float) $pizza->base_price }},
            baseImage: @json($pizza->image ? asset('uploads/' . $pizza->image) : asset('images/pizza-hero.jpg')),
            hasVariations: {{ $pizza->variations->isNotEmpty() ? 'true' : 'false' }},
            variations: @json($pizza->variations)
        };
    @endforeach

    // Modal state
    let currentPizza = null;
    let modalQuantity = 1;
    let modalBasePrice = 0;
    let modalCrustExtra = 0;
    let selectedAddonIds = new Set();
    let addonPriceMap = {};

    @foreach($addons as $ad)
        addonPriceMap[{{ $ad->id }}] = {{ (float)$ad->price }};
    @endforeach

    // When size changes on a card
    function onCardSizeChange(productId) {
        const pData = pizzaDataMap[productId];
        if (!pData) return;

        const sizeSelect = document.getElementById('size-' + productId);
        const crustSelect = document.getElementById('crust-' + productId);
        if (!sizeSelect || !crustSelect) return;

        const selectedSizeId = sizeSelect.value;

        if (pData.hasVariations) {
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

    // When crust changes on a card
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

    // Initialize all cards on page load
    document.addEventListener('DOMContentLoaded', () => {
        Object.keys(pizzaDataMap).forEach(productId => {
            onCardSizeChange(productId);
        });
    });

    // Client-side search within category
    function filterCategoryProducts() {
        const query = document.getElementById('categorySearchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.product-filter-item');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const desc = card.dataset.desc || '';
            if (name.includes(query) || desc.includes(query)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const countEl = document.getElementById('itemCountDisplay');
        if (countEl) {
            countEl.innerText = visibleCount;
        }
    }

    // Sorting
    function applySort(sortVal) {
        const url = new URL(window.location.href);
        if (sortVal === 'default') {
            url.searchParams.delete('sort');
        } else {
            url.searchParams.set('sort', sortVal);
        }
        window.location.href = url.toString();
    }

    // Open Customize Modal
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

        modalQuantity = 1;
        document.getElementById('modalQtyDisplay').innerText = '1';
        document.getElementById('modalInstructions').value = '';
        document.getElementById('charCount').innerText = '0/500';

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

    function adjustModalQty(delta) {
        modalQuantity += delta;
        if (modalQuantity < 1) modalQuantity = 1;
        document.getElementById('modalQtyDisplay').innerText = modalQuantity;
        recalcModalTotal();
    }

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
                const cartCountEl = document.getElementById('headerCartCount');
                if (cartCountEl) {
                    cartCountEl.innerText = data.cartCount;
                }

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
</script>
@endpush
