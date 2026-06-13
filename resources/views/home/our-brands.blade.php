@if($ourBrands->count())
<section class="home-section">
    <div class="home-wrap">
        <div class="section-head">
            <div class="section-head-text">
                <span class="section-eyebrow">Trusted Partners</span>
                <h2 class="section-title">Our Brands</h2>
                <p class="section-sub">Premium labels, exceptional quality</p>
            </div>
            <a href="{{ route('products.index') }}" class="section-link">
                All Brands
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="brands-grid">
            @foreach ($ourBrands as $brand)
            <a href="{{ route('products.index') }}?brands={{ $brand->slug }}"
                class="brand-card group">
                <div class="brand-logo-wrap">
                    <img src="{{ set_image($brand->logo) }}" alt="{{ $brand->name }}"
                        class="brand-logo" loading="lazy">
                </div>
                <span class="brand-name">{{ $brand->name }}</span>
                <span class="brand-count">{{ $brand->products_count ?? '0' }} items</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    /* ====================================================
       BRANDS SECTION — Modern Charcoal Design
    ==================================================== */

    .brands-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    @media (min-width: 480px) {
        .brands-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
    }

    @media (min-width: 768px) {
        .brands-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }
    }

    @media (min-width: 1024px) {
        .brands-grid {
            grid-template-columns: repeat(6, 1fr);
            gap: 20px;
        }
    }

    @media (min-width: 1280px) {
        .brands-grid {
            grid-template-columns: repeat(8, 1fr);
        }
    }

    /* ── Brand Card ── */
    .brand-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        padding: 20px 14px;
        border: 1.5px solid #f1f5f9;
        border-radius: 16px;
        background: #fff;
        transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
        overflow: hidden;
    }

    .brand-card:hover {
        border-color: #e2e8f0;
        transform: translateY(-4px);
        box-shadow: 0 16px 40px -12px rgba(15, 23, 42, 0.1);
    }

    @media (min-width: 768px) {
        .brand-card {
            padding: 24px 16px;
            gap: 10px;
        }
    }

    /* ── Logo ── */
    .brand-logo-wrap {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .brand-logo-wrap {
            width: 64px;
            height: 64px;
        }
    }

    .brand-card:hover .brand-logo-wrap {
        background: #0f172a;
    }

    .brand-logo {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
        transition: all 0.3s ease;
        filter: grayscale(0.3);
    }

    .brand-card:hover .brand-logo {
        transform: scale(1.08);
        filter: grayscale(0) brightness(1.1);
    }

    /* ── Name ── */
    .brand-name {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        text-align: center;
        line-height: 1.3;
        transition: color 0.2s;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 100%;
    }

    .brand-card:hover .brand-name {
        color: #0f172a;
    }

    /* ── Count ── */
    .brand-count {
        font-size: 11px;
        font-weight: 500;
        color: #94a3b8;
        text-align: center;
        line-height: 1;
        transition: color 0.2s;
    }

    .brand-card:hover .brand-count {
        color: #64748b;
    }

    /* ── Accessibility ── */
    @media (prefers-reduced-motion: reduce) {
        .brand-card,
        .brand-logo-wrap,
        .brand-logo {
            transition: none !important;
        }
        .brand-card:hover {
            transform: none;
        }
    }
</style>
@endif