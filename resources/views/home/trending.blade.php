@if(isset($trendingProducts) && $trendingProducts->isNotEmpty())
<section class="home-section home-section--trending">
    <div class="home-wrap">
        <div class="section-head">
            <div class="section-head-text">
                <span class="section-eyebrow section-eyebrow--trend">Hot Right Now</span>
                <h2 class="section-title">{{ isset($section) && $section->title ? $section->title : 'Trending Now' }}</h2>
                <p class="section-sub">{{ isset($section) && $section->subtitle ? $section->subtitle : 'What everyone is adding to cart this week' }}</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'trending']) }}" class="section-link">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="products-grid products-grid--trending">
            @foreach($trendingProducts as $product)
                <x-product-card :product="$product" badgeType="HOT" />
            @endforeach
        </div>
    </div>
</section>

<style>
    .home-section--trending {
        padding: 48px 0;
        background: var(--color-background);
        width: 100%;
        border-top: 1px solid var(--color-border);
    }

    @media (min-width: 768px) {
        .home-section--trending { padding: 64px 0; }
    }

    .home-section--trending .section-head {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 32px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .home-section--trending .section-head {
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
        }
    }

    .home-section--trending .section-head-text { flex: 1; min-width: 0; }

    .home-section--trending .section-eyebrow--trend {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        color: var(--color-primary);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 10px;
        padding: 5px 14px;
        background: var(--color-accent);
        border-radius: 99px;
    }

    .home-section--trending .section-title {
        font-size: clamp(22px, 4vw, 30px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: var(--color-primary);
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0 0 8px;
    }

    .home-section--trending .section-sub {
        font-size: clamp(13px, 2vw, 15px);
        color: var(--color-secondary);
        margin: 0;
        line-height: 1.5;
        max-width: 420px;
    }

    .home-section--trending .section-link {
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

    .home-section--trending .section-link:hover {
        background: var(--color-primary);
        color: var(--color-surface-elevated);
        border-color: var(--color-primary);
        box-shadow: 0 4px 12px rgba(26, 26, 26, 0.18);
    }

    .home-section--trending .section-link i { font-size: 11px; transition: transform 0.25s ease; }
    .home-section--trending .section-link:hover i { transform: translateX(3px); }

    .products-grid--trending {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .products-grid--trending { grid-template-columns: repeat(3, 1fr); gap: 16px; }
    }

    @media (min-width: 1024px) {
        .products-grid--trending { grid-template-columns: repeat(4, 1fr); gap: 20px; }
    }

    @media (min-width: 1280px) {
        .products-grid--trending { grid-template-columns: repeat(5, 1fr); gap: 20px; }
    }
</style>
@endif
