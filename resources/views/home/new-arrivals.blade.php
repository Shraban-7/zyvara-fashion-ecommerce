{{-- ============================================================
     SHARED HOME PAGE STYLES  (included once via new-arrivals)
============================================================ --}}
<style>
    /* ── Shared layout ── */
    .home-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 12px;
    }
    @media (min-width: 640px)  { .home-wrap { padding: 0 16px; } }
    @media (min-width: 1024px) { .home-wrap { padding: 0 24px; } }

    /* ── Section head ── */
    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        gap: 8px;
    }
    @media (min-width: 768px) { .section-head { margin-bottom: 20px; } }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
        position: relative;
        padding-left: 12px;
    }
    .section-title::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 4px; height: 70%;
        background: linear-gradient(180deg,#228bcc,#1b6fa3);
        border-radius: 4px;
    }
    @media (min-width: 768px) { .section-title { font-size: 22px; } }

    .section-sub {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 2px;
        padding-left: 12px;
    }
    @media (min-width: 768px) { .section-sub { font-size: 13px; } }

    .section-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        font-weight: 700;
        color: #228bcc;
        white-space: nowrap;
        text-decoration: none;
        padding: 6px 14px;
        border: 1.5px solid #d1e9f7;
        border-radius: 99px;
        transition: background 0.18s, color 0.18s, border-color 0.18s;
        flex-shrink: 0;
    }
    .section-link:hover {
        background: #228bcc;
        color: #fff;
        border-color: #228bcc;
    }

    /* ── Section wrapper spacing ── */
    .home-section       { padding: 24px 0; background: #fff; }
    .home-section-alt   { padding: 24px 0; background: #f8f9fa; }
    @media (min-width: 768px) {
        .home-section, .home-section-alt { padding: 36px 0; }
    }

    /* ── Products grid ── */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    @media (min-width: 640px)  { .products-grid { grid-template-columns: repeat(3, 1fr); gap: 12px; } }
    @media (min-width: 1024px) { .products-grid { grid-template-columns: repeat(4, 1fr); gap: 14px; } }
    @media (min-width: 1280px) { .products-grid { grid-template-columns: repeat(5, 1fr); } }

    /* ── Horizontal scroll row ── */
    .products-scroll {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 8px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .products-scroll::-webkit-scrollbar { display: none; }
    .products-scroll-item {
        min-width: 150px;
        max-width: 150px;
        flex-shrink: 0;
    }
    @media (min-width: 480px)  { .products-scroll-item { min-width: 170px; max-width: 170px; } }
    @media (min-width: 768px)  { .products-scroll-item { min-width: 200px; max-width: 200px; } }
    @media (min-width: 1024px) { .products-scroll-item { min-width: 220px; max-width: 220px; } }
</style>

{{-- New Arrivals Section --}}
@if($newArrivals->isNotEmpty())
<section class="home-section">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">New Arrivals</h2>
                <p class="section-sub">Fresh styles just dropped</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}" class="section-link">
                View All <i class="fas fa-arrow-right text-xs"></i>
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