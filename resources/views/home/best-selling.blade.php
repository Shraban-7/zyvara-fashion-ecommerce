@if($bestSelling->isNotEmpty())
@php $h = isset($section) ? $section->headings() : ['eyebrow' => 'Trending Now', 'title' => 'Best Selling', 'subtitle' => 'Top picks loved by thousands of customers']; @endphp
<section class="home-section home-section--best">
    <div class="home-wrap">
        <div class="section-head">
            <div class="section-head-text">
                <span class="section-eyebrow section-eyebrow--hot">{{ $h['eyebrow'] }}</span>
                <h2 class="section-title">{{ $h['title'] }}</h2>
                <p class="section-sub">{{ $h['subtitle'] }}</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'best-sellers']) }}" class="section-link">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="products-grid products-grid--best">
            @foreach($bestSelling as $product)
                <x-product-card :product="$product" badgeType="HOT" />
            @endforeach
        </div>
    </div>
</section>

<style>
    /* ====================================================
       BEST SELLING SECTION — Hot Trending
    ==================================================== */

    .home-section--best {
        padding: 48px 0;
        background: var(--color-surface-elevated);
        width: 100%;
        border-top: 1px solid var(--color-border);
    }

    @media (min-width: 768px) {
        .home-section--best {
            padding: 64px 0;
        }
    }

    /* ── Header ── */
    .home-section--best .section-head {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 32px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .home-section--best .section-head {
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }
    }

    .home-section--best .section-head-text {
        flex: 1;
        min-width: 0;
    }

    .home-section--best .section-eyebrow--hot {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        color: var(--color-accent-700);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 10px;
        padding: 5px 14px;
        background: var(--color-accent-50);
        border: 1px solid var(--color-accent-200);
        border-radius: 99px;
    }

    .home-section--best .section-title {
        font-size: clamp(22px, 4vw, 30px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: var(--color-primary);
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0 0 8px;
        word-wrap: break-word;
    }

    .home-section--best .section-sub {
        font-size: clamp(13px, 2vw, 15px);
        color: var(--color-secondary);
        margin: 0;
        line-height: 1.5;
        max-width: 420px;
    }

    .home-section--best .section-link {
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

    .home-section--best .section-link:hover {
        background: var(--color-accent);
        color: var(--color-primary);
        border-color: var(--color-accent);
        box-shadow: 0 4px 12px rgba(201, 168, 124, 0.3);
    }

    .home-section--best .section-link i {
        font-size: 11px;
        transition: transform 0.25s ease;
    }

    .home-section--best .section-link:hover i {
        transform: translateX(3px);
    }

    /* ── Grid ── */
    .products-grid--best {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .products-grid--best {
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
    }

    @media (min-width: 1024px) {
        .products-grid--best {
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
    }

    @media (min-width: 1280px) {
        .products-grid--best {
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }
    }

    /* ── Accessibility ── */
    @media (prefers-reduced-motion: reduce) {
        .home-section--best .section-link,
        .home-section--best .section-link i {
            transition: none !important;
        }
    }
</style>
@endif