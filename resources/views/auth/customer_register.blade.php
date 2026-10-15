@extends('layouts.app')

@section('title', 'Register | Italian Pizza Hut')

@push('styles')
<style>
    .auth-clean-wrap {
        min-height: calc(100vh - 140px);
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 16px 80px;
    }

    .auth-clean-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 40px 32px;
        text-align: center;
    }

    .auth-logo-box {
        margin-bottom: 28px;
    }

    .auth-logo-img {
        max-height: 48px;
        width: auto;
        max-width: 220px;
        object-fit: contain;
    }

    .auth-brand-fallback {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }
    .auth-brand-icon {
        width: 44px;
        height: 44px;
        background: var(--brand-red, #c8102e);
        color: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .auth-brand-name {
        text-align: left;
        line-height: 1.1;
    }
    .auth-brand-name strong {
        display: block;
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--brand-red, #c8102e);
    }
    .auth-brand-name span {
        display: block;
        font-size: 0.68rem;
        color: #64748b;
        letter-spacing: 2px;
        font-weight: 700;
    }

    .auth-alert-box {
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 0.84rem;
        margin-bottom: 20px;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .auth-alert-box.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .input-wrap {
        position: relative;
        margin-bottom: 16px;
    }

    .input-wrap i.prefix-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .clean-input {
        width: 100%;
        padding: 13px 16px 13px 42px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.92rem;
        color: #0f172a;
        font-family: inherit;
        outline: none;
        transition: all 0.2s ease;
    }

    .clean-input:focus {
        background: #fff;
        border-color: var(--brand-red, #c8102e);
        box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.1);
    }

    .clean-input:focus ~ .prefix-icon {
        color: var(--brand-red, #c8102e);
    }

    .btn-submit-clean {
        width: 100%;
        background: var(--brand-red, #c8102e);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 13px;
        font-size: 0.96rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 8px;
    }

    .btn-submit-clean:hover {
        background: #b00d28;
        transform: translateY(-1px);
    }

    .auth-switch-box {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
        font-size: 0.85rem;
        color: #64748b;
    }

    .auth-switch-box a {
        color: var(--brand-red, #c8102e);
        font-weight: 700;
        text-decoration: none;
    }
    .auth-switch-box a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="auth-clean-wrap">
    <div class="auth-clean-card">

        <!-- Website Logo Only -->
        <div class="auth-logo-box">
            <a href="{{ route('home') }}" style="text-decoration:none;">
                @if(!empty($siteLogo) && file_exists(public_path('uploads/' . $siteLogo)))
                    <img src="{{ asset('uploads/' . $siteLogo) }}" alt="{{ config('app.name', 'Italian Pizza Hut') }}" class="auth-logo-img">
                @else
                    <div class="auth-brand-fallback">
                        <span class="auth-brand-icon"><i class="fa-solid fa-pizza-slice"></i></span>
                        <div class="auth-brand-name">
                            <strong>Italian Pizza Hut</strong>
                            <span>BANGLADESH</span>
                        </div>
                    </div>
                @endif
            </a>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="auth-alert-box error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Form: Only Input Fields -->
        <form action="{{ route('register') }}" method="POST" style="text-align: left;">
            @csrf

            <!-- Name Field -->
            <div class="input-wrap">
                <input 
                    type="text" 
                    name="name" 
                    class="clean-input" 
                    placeholder="Full Name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                >
                <i class="fa-regular fa-user prefix-icon"></i>
            </div>

            <!-- Email Field -->
            <div class="input-wrap">
                <input 
                    type="email" 
                    name="email" 
                    class="clean-input" 
                    placeholder="Email Address" 
                    value="{{ old('email') }}" 
                    required
                >
                <i class="fa-regular fa-envelope prefix-icon"></i>
            </div>

            <!-- Password Field -->
            <div class="input-wrap">
                <input 
                    type="password" 
                    name="password" 
                    class="clean-input" 
                    placeholder="Password" 
                    required
                >
                <i class="fa-solid fa-lock prefix-icon"></i>
            </div>

            <!-- Confirm Password Field -->
            <div class="input-wrap">
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="clean-input" 
                    placeholder="Confirm Password" 
                    required
                >
                <i class="fa-solid fa-shield-halved prefix-icon"></i>
            </div>

            <button type="submit" class="btn-submit-clean">
                <span>Create Account</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-switch-box">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>

    </div>
</div>
@endsection
