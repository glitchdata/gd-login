@extends('layouts.app')

@section('title', 'Sign in · GD Login')

@section('content')
<header class="hero">
    <div>
        <p class="eyebrow">Welcome back</p>
        <h1>Access your workspace</h1>
        <p class="lead">Sign in to reach your personalized dashboard.</p>
    </div>
</header>

<div class="grid">
    <section class="card">
        <h2>Sign in</h2>
        <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1rem;">
            <a href="{{ route('login.google.redirect') }}" style="display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.85rem 1rem;border-radius:0.9rem;border:1px solid rgba(15,23,42,0.15);background:#fff;box-shadow:0 6px 18px rgba(15,23,42,0.08);font-weight:600;color:var(--text);text-decoration:none;">
                <span aria-hidden="true" style="font-size:1.1rem;">🔐</span>
                <span>Continue with Google</span>
            </a>
            <a href="{{ route('login.apple.redirect') }}" style="display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.85rem 1rem;border-radius:0.9rem;border:1px solid rgba(15,23,42,0.15);background:#000;color:#fff;box-shadow:0 6px 18px rgba(15,23,42,0.08);font-weight:600;text-decoration:none;">
                <span aria-hidden="true" style="font-size:1.1rem;"></span>
                <span>Sign in with Apple</span>
            </a>
            <p class="lead" style="margin:0;color:var(--muted);font-size:0.95rem;">Federated sign-in keeps your dashboard, shop, and API lab connected under one session.</p>
        </div>
        @if (session('status'))
            <div class="banner success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="banner error">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="password" required>
            </label>
            <label style="display:flex;align-items:center;gap:0.5rem;">
                <input type="checkbox" name="remember" value="1" style="width:auto;"> Remember me
            </label>
            <button type="submit">Sign in</button>
            <p class="hint">Need an account? <a class="link" href="{{ route('register') }}">Create one</a>.</p>
        </form>
    </section>
</div>
@endsection
