<section class="cat-section">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-sub">Discover our wide range of collections</p>
            </div>
            <a href="{{ route('products.index') }}" class="section-link">
                View All
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="cat-grid">
            @foreach($allMenuCategories as $category)
            <a href="{{ route('products.index') }}?categories={{ $category->slug }}" class="cat-item group">
                <div class="cat-card">
                    <div class="cat-img-wrap">
                        <img src="{{ set_image($category->image) }}" alt="{{ $category->name }}"
                            class="cat-img"
                            loading="lazy">
                        
                        {{-- Overlay with gradient --}}
                        <div class="cat-overlay"></div>
                        
                        {{-- Content overlay --}}
                        <div class="cat-overlay-content">
                            <span class="cat-name">{{ $category->name }}</span>
                            <div class="cat-meta flex items-center justify-between">
                                <span class="cat-count">{{ $category->products_count ?? rand(12, 150) }} Products</span>
                                <span class="cat-cta">
                                    Shop Now
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    /* ── Section ── */
    .cat-section {
        padding: 48px 0 40px;
        background: #f8f9fa;
    }

    .home-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* ── Section Header ── */
    .section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 28px;
    }

    .section-title {
        font-size: clamp(20px, 4vw, 26px);
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0;
    }

    .section-sub {
        font-size: clamp(13px, 2.5vw, 15px);
        color: #64748b;
        margin-top: 6px;
        line-height: 1.4;
    }

    .section-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        transition: all 0.25s ease;
        white-space: nowrap;
        flex-shrink: 0;
        margin-left: 16px;
    }

    .section-link:hover {
        background: #0f172a;
        color: #fff;
        border-color: #0f172a;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    }

    .section-link svg {
        transition: transform 0.25s ease;
    }

    .section-link:hover svg {
        transform: translateX(3px);
    }

    /* ── Grid ── */
    .cat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    @media (min-width: 480px) {
        .cat-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }
    }

    @media (min-width: 640px) {
        .cat-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
    }

    @media (min-width: 768px) {
        .cat-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
    }

    @media (min-width: 1024px) {
        .cat-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
    }

    @media (min-width: 1280px) {
        .cat-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }
    }

    /* ── Category Card ── */
    .cat-item {
        text-decoration: none;
        display: block;
    }

    .cat-card {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .cat-item:hover .cat-card {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.18);
    }

    /* ── Image Wrap ── */
    .cat-img-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        background: #e2e8f0;
    }

    @media (min-width: 640px) {
        .cat-img-wrap {
            aspect-ratio: 4 / 5;
        }
    }

    @media (min-width: 1024px) {
        .cat-img-wrap {
            aspect-ratio: 3 / 4;
        }
    }

    .cat-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .cat-item:hover .cat-img {
        transform: scale(1.1);
    }

    /* ── Overlay ── */
    .cat-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to top,
            rgba(15, 23, 42, 0.85) 0%,
            rgba(15, 23, 42, 0.4) 40%,
            rgba(15, 23, 42, 0.1) 70%,
            transparent 100%
        );
        transition: background 0.4s ease;
    }

    .cat-item:hover .cat-overlay {
        background: linear-gradient(
            to top,
            rgba(15, 23, 42, 0.92) 0%,
            rgba(15, 23, 42, 0.5) 50%,
            rgba(15, 23, 42, 0.15) 80%,
            transparent 100%
        );
    }

    /* ── Overlay Content ── */
    .cat-overlay-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px 16px 18px;
        z-index: 2;
    }

    @media (min-width: 768px) {
        .cat-overlay-content {
            padding: 24px 20px 20px;
        }
    }

    .cat-name {
        display: block;
        font-size: clamp(15px, 2.5vw, 18px);
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 8px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }

    .cat-item:hover .cat-name {
        transform: translateY(-2px);
    }

    .cat-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
    }

    .cat-count {
        font-size: clamp(11px, 2vw, 13px);
        font-weight: 500;
        color: rgba(255, 255, 255, 0.75);
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 5px 12px;
        border-radius: 99px;
        line-height: 1;
        border: 1px solid rgba(255, 255, 255, 0.1);
        white-space: nowrap;
    }

    .cat-cta {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: clamp(11px, 2vw, 12px);
        font-weight: 700;
        color: #fff;
        opacity: 0;
        transform: translateX(-8px);
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        white-space: nowrap;
    }

    .cat-item:hover .cat-cta {
        opacity: 1;
        transform: translateX(0);
    }

    .cat-cta svg {
        transition: transform 0.2s ease;
    }

    .cat-item:hover .cat-cta svg {
        transform: translateX(3px);
    }

    .cat-item:hover{
        opacity: 1;
        transform: scale(1) rotate(0deg);
    }

    /* ── Accessibility ── */
    @media (prefers-reduced-motion: reduce) {
        .cat-card,
        .cat-img,
        .cat-overlay,
        .cat-name,
        .cat-cta,
        .section-link,
        .section-link svg {
            transition: none !important;
        }
        .cat-item:hover .cat-card {
            transform: none;
        }
    }

    /* Touch devices - always show CTA */
    @media (hover: none) {
        .cat-cta {
            opacity: 1;
            transform: none;
        }
    }
</style>