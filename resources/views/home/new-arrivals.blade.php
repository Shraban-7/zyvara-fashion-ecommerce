
<style>
    .home-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
    }
    @media (min-width: 640px)  { .home-wrap { padding: 0 20px; } }
    @media (min-width: 1024px) { .home-wrap { padding: 0 24px; } }

    .section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 16px;
    }
    @media (min-width: 768px) { .section-head { margin-bottom: 32px; } }

    .section-head-text {
        flex: 1;
        min-width: 0;
    }

    .section-eyebrow {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 8px;
        padding: 5px 14px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 99px;
    }

    .section-title {
        font-size: clamp(20px, 3.5vw, 28px);
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        letter-spacing: -0.02em;
        margin: 0 0 6px;
    }

    .section-sub {
        font-size: clamp(13px, 2vw, 15px);
        color: #64748b;
        margin: 0;
        line-height: 1.5;
        max-width: 420px;
    }

    .section-head-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .section-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        text-decoration: none;
        padding: 10px 18px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }
    .section-link:hover {
        background: #0f172a;
        color: #fff;
        border-color: #0f172a;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    }
    .section-link i {
        font-size: 11px;
        transition: transform 0.25s ease;
    }
    .section-link:hover i {
        transform: translateX(3px);
    }

    .home-section       { padding: 48px 0; background: #fff; }
    .home-section-alt   { padding: 48px 0; background: #f8fafc; }
    @media (min-width: 768px) {
        .home-section, .home-section-alt { padding: 64px 0; }
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    @media (min-width: 640px)  { .products-grid { grid-template-columns: repeat(3, 1fr); gap: 16px; } }
    @media (min-width: 1024px) { .products-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; } }
    @media (min-width: 1280px) { .products-grid { grid-template-columns: repeat(5, 1fr); gap: 20px; } }

    .products-scroll {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 8px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .products-scroll::-webkit-scrollbar { display: none; }
    .products-scroll-item {
        min-width: 160px;
        max-width: 160px;
        flex-shrink: 0;
    }
    @media (min-width: 480px)  { .products-scroll-item { min-width: 180px; max-width: 180px; } }
    @media (min-width: 768px)  { .products-scroll-item { min-width: 210px; max-width: 210px; } }
    @media (min-width: 1024px) { .products-scroll-item { min-width: 230px; max-width: 230px; } }
</style>

@if($newArrivals->isNotEmpty())
<section class="home-section">
    <div class="home-wrap">
        <div class="section-head">
            <div class="section-head-text">
                <span class="section-eyebrow">Just Dropped</span>
                <h2 class="section-title">New Arrivals</h2>
                <p class="section-sub">Fresh styles curated for the season</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}" class="section-link">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="products-grid">
            @foreach($newArrivals as $product)
                <x-product-card :product="$product" badgeType="NEW" />
            @endforeach
        </div>
    </div>
</section>
@endif