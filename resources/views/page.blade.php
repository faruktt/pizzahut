@extends('layouts.app')

@section('title', $page->title . ' | Italian Pizza')

@push('styles')
<style>
    .cms-page-wrap {
        padding: 40px 0 80px 0;
        background: linear-gradient(180deg, #fdfbf7 0%, #f7f3ee 100%);
        min-height: 70vh;
    }

    .cms-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.88rem;
        color: #718096;
        margin-bottom: 24px;
    }

    .cms-breadcrumb a {
        color: #4a5568;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .cms-breadcrumb a:hover {
        color: var(--brand-red, #e31837);
    }

    .cms-breadcrumb i {
        font-size: 0.75rem;
        color: #cbd5e0;
    }

    .cms-card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
    }

    .cms-card-header {
        padding: 36px 40px 28px;
        border-bottom: 1px solid #edf2f7;
        background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
    }

    .cms-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: rgba(227, 24, 55, 0.08);
        color: var(--brand-red, #e31837);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-radius: 999px;
        margin-bottom: 12px;
    }

    .cms-title {
        font-size: 2.1rem;
        font-weight: 800;
        color: #1a202c;
        line-height: 1.25;
        margin-bottom: 10px;
    }

    .cms-meta {
        font-size: 0.85rem;
        color: #a0aec0;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .cms-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .cms-card-body {
        padding: 40px;
        color: #2d3748;
        font-size: 1.05rem;
        line-height: 1.85;
    }

    .cms-card-body h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a202c;
        margin-top: 28px;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #edf2f7;
    }

    .cms-card-body h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin-top: 24px;
        margin-bottom: 12px;
    }

    .cms-card-body p {
        margin-bottom: 18px;
    }

    .cms-card-body ul, .cms-card-body ol {
        margin-bottom: 20px;
        padding-left: 24px;
    }

    .cms-card-body li {
        margin-bottom: 8px;
    }

    .cms-card-body blockquote {
        border-left: 4px solid var(--brand-red, #e31837);
        padding-left: 18px;
        margin: 24px 0;
        color: #4a5568;
        font-style: italic;
        background: #fff5f5;
        padding-top: 12px;
        padding-bottom: 12px;
        border-radius: 0 8px 8px 0;
    }

    .cms-card-footer {
        padding: 24px 40px;
        background: #f8fafc;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .cms-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: #ffffff;
        color: #4a5568;
        font-weight: 600;
        font-size: 0.92rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .cms-btn-back:hover {
        background: #f1f5f9;
        color: #1a202c;
        border-color: #cbd5e1;
    }

    .cms-btn-order {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: var(--brand-red, #e31837);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.92rem;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(227, 24, 55, 0.25);
    }

    .cms-btn-order:hover {
        background: #c8102e;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(227, 24, 55, 0.35);
        color: #fff;
    }

    @media (max-width: 768px) {
        .cms-card-header, .cms-card-body, .cms-card-footer {
            padding: 24px 20px;
        }
        .cms-title {
            font-size: 1.6rem;
        }
    }
</style>
@endpush

@section('content')
<div class="cms-page-wrap">
    <div class="container" style="max-width: 920px; margin: 0 auto; padding: 0 16px;">
        <nav class="cms-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>{{ $page->title }}</span>
        </nav>

        <article class="cms-card">
            <header class="cms-card-header">
                <div class="cms-badge">
                    <i class="fa-solid fa-circle-info"></i> Information
                </div>
                <h1 class="cms-title">{{ $page->title }}</h1>
                <div class="cms-meta">
                    <span><i class="fa-regular fa-clock"></i> Updated {{ $page->updated_at ? $page->updated_at->diffForHumans() : 'Recently' }}</span>
                    <span><i class="fa-solid fa-shield-halved"></i> Official Italian Pizza</span>
                </div>
            </header>

            <div class="cms-card-body">
                {!! nl2br(e($page->content)) !!}
            </div>

            <footer class="cms-card-footer">
                <a href="{{ route('home') }}" class="cms-btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>
                <a href="{{ route('home') }}#menu" class="cms-btn-order">
                    <i class="fa-solid fa-pizza-slice"></i> Explore Our Menu
                </a>
            </footer>
        </article>
    </div>
</div>
@endsection
