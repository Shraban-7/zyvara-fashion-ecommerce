@php
    $heading = isset($section) ? $section->headings() : ['eyebrow' => 'Collections', 'title' => 'Shop by Category', 'subtitle' => 'Explore our curated edit — crafted for everyday ease and quiet luxury.'];
    $cats = $allMenuCategories->take(5);
    $catCount = $cats->count();
@endphp

@if($cats->isNotEmpty())
<section id="categories" class="cat-section">
    <div class="cat-shell">
        {{-- Section header --}}
        <div class="cat-head">
            <div>
                <p class="cat-eyebrow">{{ $heading['eyebrow'] }}</p>
                <h2 class="cat-title">{{ $heading['title'] }}</h2>
                <p class="cat-sub">{{ $heading['subtitle'] }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="cat-viewall">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        {{-- Z-pattern bento grid --}}
        <div class="cat-bento {{ $catCount === 5 ? 'cat-bento--5' : '' }}">
            @foreach ($cats as $index => $category)
                <a href="{{ route('products.index', $category->slug) }}"
                    class="cat-card cat-card--{{ $index }} group"
                    aria-label="Shop {{ $category->name }}">
                    <div class="cat-card__media">
                        <img src="{{ set_image($category->image) }}" alt="{{ $category->name }}"
                            loading="lazy" class="cat-card__img">
                        <span class="cat-card__scrim"></span>
                    </div>

                    <span class="cat-card__index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>

                    <div class="cat-card__body">
                        <h3 class="cat-card__name">{{ $category->name }}</h3>
                        @if($index === 0)
                            <p class="cat-card__blurb">The season's defining pieces, hand-picked for the modern wardrobe.</p>
                            <span class="cat-card__cta">
                                Explore Collection
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        @else
                            <span class="cat-card__cue">
                                Shop now
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    .cat-section {
        padding: 80px 0 88px;
        background: var(--color-background);
        width: 100%;
        border-top: 1px solid var(--color-border);
    }

    @media (min-width: 768px) {
        .cat-section { padding: 96px 0 104px; }
    }

    .cat-shell {
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 20px;
    }
    @media (min-width: 1024px) { .cat-shell { padding: 0 24px; } }

    /* ── Header ── */
    .cat-head {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 40px;
    }
    @media (min-width: 640px) {
        .cat-head { flex-direction: row; align-items: flex-end; justify-content: space-between; }
    }

    .cat-eyebrow {
        font-size: 11px;
        font-weight: 700;
        color: var(--color-accent);
        text-transform: uppercase;
        letter-spacing: 0.25em;
        margin: 0 0 12px;
    }

    .cat-title {
        font-size: clamp(28px, 5vw, 48px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: var(--color-primary);
        letter-spacing: -0.02em;
        line-height: 1.05;
        margin: 0;
    }

    .cat-sub {
        margin: 14px 0 0;
        max-width: 440px;
        font-size: clamp(13px, 2vw, 15px);
        color: var(--color-secondary);
        line-height: 1.6;
    }

    .cat-viewall {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--color-primary);
        text-decoration: none;
        padding: 11px 20px;
        background: var(--color-surface-elevated);
        border: 1.5px solid var(--color-border);
        border-radius: 12px;
        transition: all 0.25s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .cat-viewall i { font-size: 11px; transition: transform 0.25s ease; }
    .cat-viewall:hover {
        background: var(--color-primary);
        color: var(--color-surface-elevated);
        border-color: var(--color-primary);
        box-shadow: 0 6px 18px rgba(26, 26, 26, 0.18);
    }
    .cat-viewall:hover i { transform: translateX(3px); }

    /* ── Bento grid ── */
    /* 4 cards: single row of 4 equal tiles (2 rows tall for presence) */
    /* 5 cards: all 5 squeezed into a single row (adjusted spans) */
    .cat-bento {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-auto-rows: 240px;
        gap: 14px;
    }

    .cat-bento--5 {
        grid-template-columns: repeat(5, 1fr);
    }

    .cat-card {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        display: block;
        text-decoration: none;
        background: var(--color-surface-muted);
        border: 1px solid var(--color-border);
        isolation: isolate;
        grid-column: span 1;
        grid-row: span 1;
    }

    /* With 5 cards, keep every tile equal so they share one row */
    .cat-bento--5 .cat-card { grid-column: span 1; grid-row: span 1; }

    /* Hero tile gets a touch more height only when there are exactly 4 */
    .cat-bento:not(.cat-bento--5) .cat-card--0 { grid-row: span 2; }

    .cat-card__media { position: absolute; inset: 0; z-index: -2; }
    .cat-card__img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .cat-card:hover .cat-card__img { transform: scale(1.08); }

    /* dual-tone scrim for legibility + depth */
    .cat-card__scrim {
        position: absolute; inset: 0;
        background:
            linear-gradient(to top, rgba(26, 26, 26, 0.82) 0%, rgba(26, 26, 26, 0.15) 48%, rgba(26, 26, 26, 0) 75%),
            linear-gradient(to bottom right, rgba(201, 168, 124, 0.18), transparent 55%);
        transition: opacity 0.4s ease;
    }
    .cat-card:hover .cat-card__scrim { opacity: 0.92; }

    .cat-card__index {
        position: absolute;
        top: 16px; left: 18px;
        font-family: var(--font-heading);
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.1em;
        color: var(--color-surface-elevated);
        opacity: 0.7;
        z-index: 1;
    }

    .cat-card__body {
        position: absolute;
        inset: auto 0 0 0;
        padding: 22px 24px;
        z-index: 1;
    }
    .cat-card--0 .cat-card__body { padding: 30px 32px; }

    .cat-card__name {
        font-family: var(--font-heading);
        font-weight: 600;
        color: var(--color-surface-elevated);
        font-size: 20px;
        line-height: 1.15;
        margin: 0;
        letter-spacing: -0.01em;
    }
    .cat-card--0 .cat-card__name { font-size: clamp(26px, 3.4vw, 38px); }

    /* In the 5-card single row, keep tiles uniform (no oversized blurb) */
    .cat-bento--5 .cat-card--0 .cat-card__blurb,
    .cat-bento--5 .cat-card--0 .cat-card__cta { display: none; }
    .cat-bento--5 .cat-card--0 .cat-card__name { font-size: 20px; }

    .cat-card__blurb {
        margin: 12px 0 18px;
        max-width: 360px;
        font-size: 14px;
        line-height: 1.55;
        color: rgba(250, 248, 245, 0.82);
    }

    .cat-card__cta,
    .cat-card__cue {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: var(--color-accent);
    }
    .cat-card__cta i, .cat-card__cue i { font-size: 10px; transition: transform 0.25s ease; }

    .cat-card__cue {
        opacity: 0;
        transform: translateY(6px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .cat-card:hover .cat-card__cue { opacity: 1; transform: translateY(0); }
    .cat-card:hover .cat-card__cta i,
    .cat-card:hover .cat-card__cue i { transform: translateX(4px); }

    /* ── Responsive ── */
    @media (max-width: 859px) {
        .cat-bento,
        .cat-bento--5 {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 200px;
        }
        .cat-bento:not(.cat-bento--5) .cat-card--0 { grid-column: span 2; grid-row: span 2; }
    }

    @media (max-width: 479px) {
        .cat-bento,
        .cat-bento--5 {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 170px;
            gap: 10px;
        }
        .cat-card__body { padding: 16px 18px; }
        .cat-card--0 .cat-card__body { padding: 22px 22px; }
        .cat-card__blurb { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .cat-card__img, .cat-card__cue i, .cat-viewall i { transition: none !important; }
    }
</style>
@endif
