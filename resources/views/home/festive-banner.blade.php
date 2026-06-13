<?php
$banner = $banners->where('position', \App\Enums\BannerPosition::FESTIVAL)->sortBy('sort_order')->first();
?>

@if($banner)
<section class="home-section home-section--festival">
    <div class="home-wrap">
        <div class="fb-card">
            <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}"
                class="fb-img" loading="lazy">

            <div class="fb-overlay"></div>

            <div class="fb-content">
                <p class="fb-eyebrow">Limited Time Offer</p>
                <h2 class="fb-title">{{ $banner->title }}</h2>
                <p class="fb-subtitle">{{ $banner->subtitle }}</p>
                <div class="fb-actions">
                    <a href="{{ $banner->link ?? '#' }}" class="fb-btn-primary">
                        {{ $banner->button_text ?? 'Shop Now' }}
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('products.index') }}" class="fb-btn-outline">
                        Browse All
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* ====================================================
       FESTIVAL BANNER — Charcoal Theme
    ==================================================== */

    .home-section--festival {
        padding: 48px 0;
        background: #fff;
        width: 100%;
    }

    @media (min-width: 768px) {
        .home-section--festival {
            padding: 64px 0;
        }
    }

    .home-section--festival .home-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
        width: 100%;
        box-sizing: border-box;
    }

    @media (min-width: 768px) {
        .home-section--festival .home-wrap {
            padding: 0 24px;
        }
    }

    @media (min-width: 1280px) {
        .home-section--festival .home-wrap {
            padding: 0 32px;
        }
    }

    /* ── Card ── */
    .fb-card {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        min-height: 320px;
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.12);
        transition: box-shadow 0.3s ease;
    }

    .fb-card:hover {
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.18);
    }

    @media (min-width: 640px) {
        .fb-card {
            min-height: 360px;
        }
    }

    @media (min-width: 768px) {
        .fb-card {
            min-height: 420px;
            border-radius: 24px;
        }
    }

    @media (min-width: 1024px) {
        .fb-card {
            min-height: 480px;
        }
    }

    /* ── Image ── */
    .fb-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .fb-card:hover .fb-img {
        transform: scale(1.04);
    }

    /* ── Overlay ── */
    .fb-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            105deg,
            rgba(15, 23, 42, 0.85) 0%,
            rgba(15, 23, 42, 0.5) 50%,
            rgba(15, 23, 42, 0.2) 100%
        );
    }

    /* ── Content ── */
    .fb-content {
        position: relative;
        z-index: 2;
        padding: 32px 24px;
        max-width: 560px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        height: 100%;
        min-height: 320px;
        box-sizing: border-box;
    }

    @media (min-width: 640px) {
        .fb-content {
            padding: 40px 32px;
            min-height: 360px;
        }
    }

    @media (min-width: 768px) {
        .fb-content {
            padding: 48px;
            min-height: 420px;
        }
    }

    @media (min-width: 1024px) {
        .fb-content {
            padding: 56px 48px;
            min-height: 480px;
            max-width: 600px;
        }
    }

    /* ── Eyebrow ── */
    .fb-eyebrow {
        display: inline-block;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.12em;
        color: #0f172a;
        text-transform: uppercase;
        margin-bottom: 12px;
        padding: 6px 14px;
        background: #fff;
        border-radius: 99px;
        width: fit-content;
    }

    /* ── Title ── */
    .fb-title {
        font-size: clamp(28px, 5vw, 48px);
        font-weight: 900;
        color: #fff;
        line-height: 1.1;
        margin: 0 0 12px;
        letter-spacing: -0.03em;
        text-shadow: 0 2px 16px rgba(0, 0, 0, 0.3);
    }

    /* ── Subtitle ── */
    .fb-subtitle {
        font-size: clamp(14px, 2vw, 16px);
        color: rgba(255, 255, 255, 0.75);
        margin: 0 0 28px;
        line-height: 1.6;
        max-width: 440px;
    }

    @media (min-width: 768px) {
        .fb-subtitle {
            margin-bottom: 36px;
        }
    }

    /* ── Actions ── */
    .fb-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (min-width: 768px) {
        .fb-actions {
            gap: 16px;
        }
    }

    /* ── Primary Button ── */
    .fb-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: #fff;
        color: #0f172a;
        font-weight: 800;
        font-size: 13px;
        border-radius: 12px;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        transition: all 0.25s ease;
        border: none;
        cursor: pointer;
    }

    .fb-btn-primary:hover {
        background: #0f172a;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    .fb-btn-primary i {
        font-size: 11px;
        transition: transform 0.25s ease;
    }

    .fb-btn-primary:hover i {
        transform: translateX(3px);
    }

    /* ── Outline Button ── */
    .fb-btn-outline {
        display: inline-flex;
        align-items: center;
        padding: 12px 28px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.25s ease;
        background: transparent;
    }

    .fb-btn-outline:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: #fff;
        transform: translateY(-2px);
    }

    /* ── Accessibility ── */
    @media (prefers-reduced-motion: reduce) {
        .fb-card,
        .fb-img,
        .fb-btn-primary,
        .fb-btn-outline {
            transition: none !important;
        }
        .fb-card:hover .fb-img,
        .fb-btn-primary:hover,
        .fb-btn-outline:hover {
            transform: none;
        }
    }
</style>
@endif