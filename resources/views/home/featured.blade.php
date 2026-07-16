@if($featuredProducts->isNotEmpty())
@php $h = isset($section) ? $section->headings() : ['eyebrow' => "Editor's Pick", 'title' => 'Featured Products', 'subtitle' => 'Handpicked highlights of the season']; @endphp
<section class="home-section home-section--featured">
    <div class="home-wrap">
        <div class="section-head">
            <div class="section-head-text">
                <span class="section-eyebrow">{{ $h['eyebrow'] }}</span>
                <h2 class="section-title">{{ $h['title'] }}</h2>
                <p class="section-sub">{{ $h['subtitle'] }}</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'featured']) }}" class="section-link">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="products-grid products-grid--featured">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" badgeType="FEATURED" />
            @endforeach
        </div>
    </div>
</section>

<style>
    /* ====================================================
       FEATURED SECTION — Curated
    ==================================================== */

    .home-section--featured {
        padding: 48px 0;
        background: var(--color-surface-muted);
        width: 100%;
        border-top: 1px solid var(--color-border);
    }

    @media (min-width: 768px) {
        .home-section--featured {
            padding: 64px 0;
        }
    }

    /* ── Header ── */
    .home-section--featured .section-head {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 32px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .home-section--featured .section-head {
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }
    }

    .home-section--featured .section-head-text {
        flex: 1;
        min-width: 0;
    }

    .home-section--featured .section-eyebrow {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        color: var(--color-primary);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 10px;
        padding: 5px 14px;
        background: var(--color-surface-elevated);
        border: 1px solid var(--color-border);
        border-radius: 99px;
    }

    .home-section--featured .section-title {
        font-size: clamp(22px, 4vw, 30px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: var(--color-primary);
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0 0 8px;
        word-wrap: break-word;
    }

    .home-section--featured .section-sub {
        font-size: clamp(13px, 2vw, 15px);
        color: var(--color-secondary);
        margin: 0;
        line-height: 1.5;
        max-width: 420px;
    }

    .home-section--featured .section-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--color-primary);
        text-decoration: none;
        padding: 10px 18px;
        background: var(--color-surface-elevated);
        border: 1.5px solid var(--color-border);
        border-radius: 12px;
        transition: all 0.25s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .home-section--featured .section-link:hover {
        background: var(--color-primary);
        color: var(--color-surface-elevated);
        border-color: var(--color-primary);
        box-shadow: 0 4px 12px rgba(26, 26, 26, 0.18);
    }

    .home-section--featured .section-link i {
        font-size: 11px;
        transition: transform 0.25s ease;
    }

    .home-section--featured .section-link:hover i {
        transform: translateX(3px);
    }

    /* ── Grid ── */
    .products-grid--featured {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .products-grid--featured {
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
    }

    @media (min-width: 1024px) {
        .products-grid--featured {
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
    }

    @media (min-width: 1280px) {
        .products-grid--featured {
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }
    }

    /* ── Accessibility ── */
    @media (prefers-reduced-motion: reduce) {
        .home-section--featured .section-link,
        .home-section--featured .section-link i {
            transition: none !important;
        }
    }
</style>
@endif