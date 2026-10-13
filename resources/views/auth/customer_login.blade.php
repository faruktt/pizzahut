@extends('layouts.app')

@section('title', 'Sign In | Pizza Hut Bangladesh')

@section('content')
<div class="container" style="max-width: 440px; padding: 60px 16px 80px 16px;">
    <div style="background: #ffffff; border: 1px solid #eaeaea; border-radius: 16px; padding: 36px 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); text-align: center;">
        <span style="font-size: 2.5rem; display: block; margin-bottom: 8px;">🍕</span>
        <h2 style="font-size: 1.4rem; font-weight: 800; color: #222; margin-bottom: 6px;">Sign in to Pizza Hut</h2>
        <p style="font-size: 0.85rem; color: #666; margin-bottom: 24px;">Order your favorite pizzas faster & earn reward points</p>

        @if(isset($errors) && $errors->any())
            <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 10px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 16px; text-align: left;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" style="text-align: left;">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display:block; font-size: 0.82rem; font-weight:700; color: #333; margin-bottom: 6px;">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email', 'customer@example.com') }}" required style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:0.9rem;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; font-size: 0.82rem; font-weight:700; color: #333; margin-bottom: 6px;">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" value="password123" required style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:0.9rem;">
            </div>

            <button type="submit" class="btn-signin" style="width: 100%; justify-content: center; padding: 12px; font-size: 0.95rem; border-radius: 8px;">
                Sign In ➔
            </button>
        </form>

        <div style="margin-top: 20px; font-size: 0.82rem; color: #666;">
            Don't have an account? <a href="{{ route('register') }}" style="color: #c8102e; font-weight: 700;">Create One</a>
        </div>

        <div style="margin-top: 14px; font-size: 0.78rem; color: #888; border-top: 1px solid #eee; padding-top: 14px;">
            Demo Login: <strong>customer@example.com</strong> / <strong>password123</strong><br>
            <em>(You can also order as a Guest without signing in!)</em>
        </div>
    </div>
</div>
@endsection
