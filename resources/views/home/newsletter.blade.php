@php
    $heading = isset($section) ? $section->headings() : ['eyebrow' => '', 'title' => 'Subscribe for Exclusive Offers', 'subtitle' => 'Stay updated with our latest collections and offers.'];
@endphp

<section class="nl-section">
    <div class="nl-shell">
        @if($heading['eyebrow'])
        <p class="nl-eyebrow">{{ $heading['eyebrow'] }}</p>
        @endif
        <h2 class="nl-title">{{ $heading['title'] }}</h2>
        @if($heading['subtitle'])
        <p class="nl-sub">{{ $heading['subtitle'] }}</p>
        @endif

        <form class="nl-form" action="{{ route('subscribe') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Enter your email"
                class="nl-input" required>
            <button type="submit" class="nl-btn">Subscribe</button>
        </form>

        @if(session('success') && str_contains(session('success'), 'subscrib'))
            <p class="nl-note">{{ session('success') }}</p>
        @endif
    </div>
</section>

<style>
    .nl-section {
        padding: 56px 0 64px;
        background: var(--color-surface-elevated);
        width: 100%;
        border-top: 1px solid var(--color-border);
        border-bottom: 1px solid var(--color-border);
    }

    .nl-shell {
        max-width: 720px;
        margin: 0 auto;
        padding: 0 20px;
        text-align: center;
    }

    .nl-eyebrow {
        font-size: 11px;
        font-weight: 700;
        color: var(--color-accent);
        text-transform: uppercase;
        letter-spacing: 0.25em;
        margin: 0 0 12px;
    }

    .nl-title {
        font-size: clamp(22px, 4vw, 32px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: var(--color-primary);
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.15;
    }

    .nl-sub {
        margin: 12px 0 0;
        font-size: clamp(13px, 2vw, 15px);
        color: var(--color-secondary);
        line-height: 1.6;
    }

    .nl-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 520px;
        margin: 28px auto 0;
    }

    @media (min-width: 540px) {
        .nl-form { flex-direction: row; }
    }

    .nl-input {
        flex: 1;
        padding: 14px 18px;
        border-radius: 12px;
        border: 1.5px solid var(--color-border);
        background: var(--color-background);
        color: var(--color-primary);
        font-size: 14px;
        outline: none;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .nl-input::placeholder { color: var(--color-secondary); opacity: 0.7; }
    .nl-input:focus {
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(201, 168, 124, 0.18);
    }

    .nl-btn {
        padding: 14px 28px;
        border-radius: 12px;
        border: none;
        background: var(--color-primary);
        color: var(--color-surface-elevated);
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.25s ease, transform 0.2s ease;
    }
    .nl-btn:hover { background: var(--color-accent); transform: translateY(-1px); }

    .nl-note {
        margin: 16px 0 0;
        font-size: 13px;
        color: var(--color-accent);
        font-weight: 600;
    }

    @media (prefers-reduced-motion: reduce) {
        .nl-btn { transition: none !important; }
    }
</style>
