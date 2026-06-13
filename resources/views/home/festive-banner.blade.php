<?php
$banner = $banners->where('position', \App\Enums\BannerPosition::FESTIVAL)->sortBy('sort_order')->first();
?>

@if($banner)
<section class="home-section">
    <div class="home-wrap">
        <div class="fb-card">
            <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}"
                class="fb-img">

            <div class="fb-overlay"></div>

            <div class="fb-content">
                <p class="fb-eyebrow">Limited Time Offer</p>
                <h2 class="fb-title">{{ $banner->title }}</h2>
                <p class="fb-subtitle">{{ $banner->subtitle }}</p>
                <div class="fb-actions">
                    <a href="{{ $banner->link ?? '#' }}" class="fb-btn-primary">
                        {{ $banner->button_text ?? 'Shop Now' }}
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
    .fb-card {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        min-height: 260px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }
    @media (min-width: 768px) { .fb-card { min-height: 340px; border-radius: 24px; } }

    .fb-img {
        position: absolute;
        inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .fb-card:hover .fb-img { transform: scale(1.04); }

    .fb-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(105deg, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.35) 55%, transparent 100%);
    }

    .fb-content {
        position: relative;
        z-index: 2;
        padding: 28px 24px;
        max-width: 520px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        height: 100%;
    }
    @media (min-width: 768px) { .fb-content { padding: 40px 48px; } }

    .fb-eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.12em;
        color: #fbbf24;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .fb-title {
        font-size: 26px;
        font-weight: 900;
        color: #fff;
        line-height: 1.15;
        margin-bottom: 10px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.3);
    }
    @media (min-width: 768px) { .fb-title { font-size: 40px; } }

    .fb-subtitle {
        font-size: 13px;
        color: rgba(255,255,255,0.85);
        margin-bottom: 20px;
        line-height: 1.5;
    }
    @media (min-width: 768px) { .fb-subtitle { font-size: 15px; margin-bottom: 28px; } }

    .fb-actions { display: flex; gap: 10px; flex-wrap: wrap; }

    .fb-btn-primary {
        display: inline-flex; align-items: center;
        padding: 10px 24px;
        background: #fff;
        color: #111827;
        font-weight: 800;
        font-size: 13px;
        border-radius: 99px;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        transition: background 0.18s, transform 0.15s;
    }
    .fb-btn-primary:hover { background: #f3f4f6; transform: translateY(-2px); }

    .fb-btn-outline {
        display: inline-flex; align-items: center;
        padding: 10px 24px;
        border: 2px solid rgba(255,255,255,0.7);
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        border-radius: 99px;
        text-decoration: none;
        transition: background 0.18s, border-color 0.18s;
    }
    .fb-btn-outline:hover { background: rgba(255,255,255,0.15); border-color: #fff; }
</style>
@endif