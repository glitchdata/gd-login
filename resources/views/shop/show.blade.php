@extends('layouts.app')

@section('title', 'Shop · ' . $product->name)

@section('content')
<header class="hero">
    <div>
        <p class="eyebrow">Shop</p>
        <h1>{{ $product->name }}</h1>
        <p class="lead">{{ $product->description ?: 'No marketing copy available yet.' }}</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <a class="link" href="{{ route('shop') }}">&larr; Back to catalog</a>
        <a class="link" style="font-weight:600;" href="{{ route('login') }}">Purchase in dashboard →</a>
    </div>
</header>

<section class="card" style="display:grid;gap:1.5rem;">
    <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-end;">
        <span style="font-size:3rem;font-weight:700;">${{ number_format($product->price, 2) }}<span style="font-size:1rem;font-weight:500;color:var(--muted);">/seat</span></span>
        <span style="font-weight:600;color:var(--muted);">{{ $product->duration_months }}-month term</span>
        <span style="font-family:monospace;color:var(--muted);">Code: {{ $product->product_code }}</span>
    </div>
    <dl class="details">
        <div>
            <dt>Vendor</dt>
            <dd>{{ $product->vendor ?? '—' }}</dd>
        </div>
        <div>
            <dt>Category</dt>
            <dd>{{ $product->category ?? '—' }}</dd>
        </div>
    </dl>
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
        <a class="link" style="font-weight:600;" href="{{ route('register') }}">Need an account? Sign up →</a>
        <a class="link" href="{{ route('api.lab') }}">Validate via API Lab</a>
    </div>
</section>
@endsection
