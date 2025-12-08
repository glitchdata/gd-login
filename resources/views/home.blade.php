@extends('layouts.app')

@section('title', 'Glitchdata · Federated Login')

@section('content')
<header class="hero">
    <div>
        <p class="eyebrow">Federated access</p>
        <h1>One login for every Glitchdata service.</h1>
        <p class="lead">Authenticate once, land in your dashboard, and manage licenses, seats, and API tooling without hopping between apps.</p>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-top:1rem;align-items:center;">
            <button type="button" onclick="window.location='{{ route('login') }}'">Log in to the portal</button>
            <button type="button" class="link button-reset" onclick="window.location='{{ route('register') }}'">Create access</button>
            <button type="button" class="link button-reset" onclick="window.location='{{ route('shop') }}'">Browse licenses</button>
        </div>
        <p style="margin-top:0.75rem;color:var(--muted);">Already have a license code? Jump straight into the federated login to activate it.</p>
    </div>
</header>

<section class="card">
    <div class="grid">
        <article>
            <p class="eyebrow" style="margin-bottom:0.35rem;">00 · Login first</p>
            <h2 style="margin-top:0;">Make sign-in the front door</h2>
            <p>Everything routes through the login portal—SSO-ready, two-factor friendly, and the fastest path to your dashboard.</p>
            <a class="link" href="{{ route('login') }}">Log in now</a>
        </article>
        <article>
            <p class="eyebrow" style="margin-bottom:0.35rem;">01 · Self-serve licenses</p>
            <h2 style="margin-top:0;">Purchase seats from the catalog</h2>
            <p>Browse curated products, preview pricing and duration, then assign licenses to yourself or your team members directly from the dashboard.</p>
            <a class="link" href="{{ route('shop') }}">Visit the shop</a>
        </article>
        <article>
            <p class="eyebrow" style="margin-bottom:0.35rem;">02 · API validation</p>
            <h2 style="margin-top:0;">Verify entitlements programmatically</h2>
            <p>Use the hosted API Lab to post license codes and seat counts, mirroring how your backend can confirm availability in production.</p>
            <a class="link" href="{{ route('api.lab') }}">Open the API Lab</a>
        </article>
        <article>
            <p class="eyebrow" style="margin-bottom:0.35rem;">03 · Admin tooling</p>
            <h2 style="margin-top:0;">Manage users, products, and licenses</h2>
            <p>Admins gain a polished console to edit products, onboard users, and audit allocations—no extra front-end build pipeline required.</p>
            <a class="link" href="{{ route('login') }}">Sign in as admin</a>
        </article>
    </div>
</section>

<section class="card" style="display:grid;gap:1rem;">
    <div style="display:flex;flex-direction:column;gap:0.35rem;">
        <p class="eyebrow" style="margin-bottom:0;color:var(--muted);">How login federation works</p>
        <h2 style="margin:0;">Unified authentication, instant handoff</h2>
        <p style="margin:0;">Users authenticate once, then pivot to licensing, payments, and validation APIs with their session carried across.</p>
    </div>
    <div class="details" style="gap:0.75rem 1rem;">
        <div>
            <p class="eyebrow" style="margin:0;color:var(--muted);">Step 1</p>
            <p style="margin:0;font-weight:600;">Sign in via the portal</p>
            <p style="margin:0;color:var(--muted);">Email + password with optional two-factor for higher assurance.</p>
        </div>
        <div>
            <p class="eyebrow" style="margin:0;color:var(--muted);">Step 2</p>
            <p style="margin:0;font-weight:600;">Select the workspace</p>
            <p style="margin:0;color:var(--muted);">Choose the product context and seamlessly reach dashboard or API tools.</p>
        </div>
        <div>
            <p class="eyebrow" style="margin:0;color:var(--muted);">Step 3</p>
            <p style="margin:0;font-weight:600;">Manage and validate</p>
            <p style="margin:0;color:var(--muted);">Grant seats, activate licenses, and call the validation endpoint under one session.</p>
        </div>
    </div>
</section>

<section class="card alt">
    <div style="display:flex;flex-direction:column;gap:1rem;">
                <div>
                        <p class="eyebrow" style="color:rgba(255,255,255,0.7);">API quickstart</p>
                        <h2 style="margin:0;">`POST /api/licenses/validate`</h2>
                        <p style="margin:0;color:rgba(255,255,255,0.8);">Send a license code plus requested seats to confirm availability, expiration, and seat counts—all responses structured for easy automation.</p>
                </div>
                <pre style="margin:0;background:rgba(0,0,0,0.3);padding:1rem;border-radius:0.9rem;color:#fff;font-family:monospace;overflow:auto;">{
    "license_code": "ACTV-ABCD-1234",
    "seats_requested": 3
}</pre>
        <div>
            <a class="link" style="color:#fff;font-weight:700;" href="{{ route('api.lab') }}">Send a sample request →</a>
        </div>
    </div>
</section>
@endsection
