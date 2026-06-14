<?php
$sliders = $banners->where('position', \App\Enums\BannerPosition::HERO)->sortBy('sort_order');
$promos = $banners->where('position', \App\Enums\BannerPosition::PROMOTIONAL)->sortBy('sort_order');
$promoList = $promos->take(2)->values();
?>

<section class="bento-section" aria-label="Featured promotions">
    <div class="bento-wrap">
        <div class="bento-grid">

            {{-- Main Slider (Left Large) --}}
            <div class="bento-main group" role="region" aria-roledescription="carousel" aria-label="Hero banner carousel">
                <div id="hero-slider" class="relative w-full h-full overflow-hidden rounded-2xl">
                    @foreach ($sliders as $index => $banner)
                        <div class="hero-slide absolute inset-0 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                            role="group" aria-roledescription="slide"
                            aria-label="Slide {{ $index + 1 }} of {{ $sliders->count() }}"
                            aria-hidden="{{ $index !== 0 ? 'true' : 'false' }}">
                            <a href="{{ $banner->link ?? '#' }}" class="block w-full h-full" tabindex="-1">
                                <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}" class="w-full h-full object-cover"
                                    width="800" height="600">
                                @if ($banner->title)
                                    <div class="slide-caption">
                                        <h2 class="slide-title">{{ $banner->title }}</h2>
                                        @if ($banner->subtitle)
                                            <p class="slide-subtitle">{{ $banner->subtitle }}</p>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        </div>
                    @endforeach

                    {{-- Nav Arrows --}}
                    <button id="prevBtn" aria-label="Previous slide" class="bento-nav left-3">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button id="nextBtn" aria-label="Next slide" class="bento-nav right-3">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>

                    {{-- Progress Dots --}}
                    <div class="progress-dots" role="tablist" aria-label="Slide indicators">
                        @foreach ($sliders as $index => $banner)
                            <button class="progress-dot" role="tab" aria-label="Go to slide {{ $index + 1 }}"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}" data-index="{{ $index }}"
                                tabindex="{{ $index === 0 ? '0' : '-1' }}">
                                <div class="dot-track">
                                    <div class="progress-fill {{ $index === 0 ? 'w-full' : 'w-0' }}"></div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Promotional Cards --}}
            @foreach ($promoList as $i => $promo)
                <div class="bento-card bento-card-{{ $i + 1 }} group">
                    <a href="{{ $promo->link ?? '#' }}"
                        class="block w-full h-full relative overflow-hidden rounded-2xl">
                        <img src="{{ storage_url($promo->image) }}" alt="{{ $promo->title }}" loading="lazy"
                            class="bento-img" width="400" height="300">
                        <div class="bento-overlay"></div>
                        @if ($promo->title)
                            <div class="bento-text">
                                <span class="bento-label">{{ $promo->title }}</span>
                                @if ($promo->subtitle)
                                    <p class="bento-desc">{{ $promo->subtitle }}</p>
                                @endif
                            </div>
                        @endif
                    </a>
                </div>
            @endforeach



        </div>
    </div>
</section>

<style>
    .bento-section {
        background: #f8f9fa;
        padding: 20px 0;
    }

    .bento-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* ====================================================
       MOBILE: 2 cards per row, all same size
    ==================================================== */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .bento-main {
        grid-column: 1 / -1;
        grid-row: 1;
        height: 280px;
    }

    .bento-card {
        height: 200px;
    }

    /* ====================================================
       TABLET: 2 cards per row, main stays full width
    ==================================================== */
    @media (min-width: 640px) {
        .bento-grid {
            gap: 16px;
        }

        .bento-main {
            height: 360px;
        }

        .bento-card {
            height: 240px;
        }
    }

    /* ====================================================
       DESKTOP: Large-Small-Small-Large layout
       Grid: 2 columns, 2 rows
       Card 1: spans full width (large)
       Card 2: normal (small)
       Card 3: normal (small)
       Card 4: spans full width (large)
    ==================================================== */
    @media (min-width: 1024px) {

        .bento-section {
            padding: 24px 0;
        }

        .bento-grid {
            grid-template-columns: 2fr 1fr;
            grid-template-rows: repeat(2, 220px);
            gap: 20px;
        }

        /* Main slider */
        .bento-main {
            grid-column: 1;
            grid-row: 1 / 3;
            height: 100%;
        }

        /* Promo 1 */
        .bento-card-1 {
            grid-column: 2;
            grid-row: 1;
            height: 100%;
        }

        /* Promo 2 */
        .bento-card-2 {
            grid-column: 2;
            grid-row: 2;
            height: 100%;
        }
    }

    /* Large screens: 3-column layout with large-small-small-large */
    @media (min-width: 1280px) {

        .bento-grid {
            grid-template-columns: 2.5fr 1fr;
            grid-template-rows: repeat(2, 250px);
            gap: 20px;
            height: 520px;
        }

        .bento-main {
            grid-column: 1;
            grid-row: 1 / 3;
        }

        .bento-card-1 {
            grid-column: 2;
            grid-row: 1;
        }

        .bento-card-2 {
            grid-column: 2;
            grid-row: 2;
        }
    }

    /* ====================================================
       MAIN SLIDER
    ==================================================== */
    .bento-main #hero-slider {
        position: relative;
        width: 100%;
        height: 100%;
        background: #e5e7eb;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    }

    .hero-slide {
        transition: opacity 700ms ease-in-out;
    }

    .hero-slide:first-child {
        opacity: 1;
        z-index: 10;
    }

    .hero-slide:not(:first-child) {
        opacity: 0;
        z-index: 0;
    }

    .slide-caption {
        position: absolute;
        bottom: 56px;
        left: 24px;
        z-index: 20;
        max-width: 400px;
        pointer-events: none;
    }

    .slide-title {
        color: white;
        font-size: 1.75rem;
        font-weight: 700;
        text-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
        line-height: 1.2;
        margin: 0;
    }

    .slide-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9375rem;
        margin-top: 8px;
        text-shadow: 0 1px 6px rgba(0, 0, 0, 0.4);
    }

    /* Navigation */
    .bento-nav {
        position: absolute;
        z-index: 30;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(8px);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        transition: opacity 0.2s, transform 0.18s;
        color: #1f2937;
        opacity: 0;
        outline: none;
    }

    .group:hover .bento-nav {
        opacity: 1;
    }

    .bento-nav:hover {
        transform: translateY(-50%) scale(1.1);
        background: #fff;
    }

    .bento-nav:focus-visible {
        opacity: 1;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }

    /* Progress Dots */
    .progress-dots {
        position: absolute;
        z-index: 30;
        bottom: 16px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 6px;
    }

    .progress-dot {
        background: none;
        border: none;
        padding: 4px;
        cursor: pointer;
        border-radius: 4px;
        outline: none;
        transition: transform 0.15s;
    }

    .progress-dot:hover {
        transform: scale(1.2);
    }

    .progress-dot:focus-visible {
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.6);
    }

    .dot-track {
        width: 24px;
        height: 3px;
        border-radius: 2px;
        background: rgba(255, 255, 255, 0.3);
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: white;
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    /* ====================================================
       BENTO CARDS
    ==================================================== */
    .bento-card {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        background: #e5e7eb;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .bento-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .bento-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .group:hover .bento-img {
        transform: scale(1.08);
    }

    .bento-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0.1) 40%, transparent 70%);
        transition: background 0.3s;
    }

    .group:hover .bento-overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0.05) 40%, transparent 70%);
    }

    .bento-text {
        position: absolute;
        bottom: 16px;
        left: 16px;
        right: 16px;
        z-index: 10;
    }

    .bento-label {
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
        display: block;
    }

    .bento-desc {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.75rem;
        margin-top: 4px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        line-height: 1.4;
    }

    .bento-empty {
        background: #f1f3f5;
        border: 2px dashed #d1d5db;
    }

    /* ====================================================
       REDUCED MOTION
    ==================================================== */
    @media (prefers-reduced-motion: reduce) {

        .hero-slide,
        .bento-card,
        .bento-img,
        .progress-fill {
            transition: none !important;
        }

        .bento-card:hover {
            transform: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.progress-dot');
        const fills = document.querySelectorAll('.progress-fill');
        if (!slides.length) return;

        let currentIndex = 0;
        const INTERVAL = 5000;
        let timer;

        function updateAria() {
            slides.forEach((s, i) => {
                const active = i === currentIndex;
                s.setAttribute('aria-hidden', !active);
                s.setAttribute('tabindex', active ? '0' : '-1');
            });
            dots.forEach((d, i) => {
                d.setAttribute('aria-selected', i === currentIndex);
                d.setAttribute('tabindex', i === currentIndex ? '0' : '-1');
            });
        }

        function showSlide(idx) {
            slides.forEach((s, i) => {
                const isActive = i === idx;
                s.classList.toggle('opacity-100', isActive);
                s.classList.toggle('z-10', isActive);
                s.classList.toggle('opacity-0', !isActive);
                s.classList.toggle('z-0', !isActive);
            });

            fills.forEach((f, i) => {
                f.style.transition = 'none';
                f.style.width = '0%';
                if (i === idx) {
                    void f.offsetWidth;
                    f.style.transition = `width ${INTERVAL}ms linear`;
                    f.style.width = '100%';
                }
            });

            currentIndex = idx;
            updateAria();
        }

        function next() {
            showSlide((currentIndex + 1) % slides.length);
        }

        function prev() {
            showSlide((currentIndex - 1 + slides.length) % slides.length);
        }

        function startTimer() {
            clearInterval(timer);
            timer = setInterval(next, INTERVAL);
        }

        function stopTimer() {
            clearInterval(timer);
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            next();
            startTimer();
        });
        document.getElementById('prevBtn').addEventListener('click', () => {
            prev();
            startTimer();
        });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                showSlide(i);
                startTimer();
            });
        });

        const slider = document.querySelector('.bento-main');
        slider.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') {
                next();
                startTimer();
            }
            if (e.key === 'ArrowLeft') {
                prev();
                startTimer();
            }
        });

        let touchStartX = 0;
        const heroSlider = document.getElementById('hero-slider');

        heroSlider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            stopTimer();
        }, {
            passive: true
        });

        heroSlider.addEventListener('touchend', (e) => {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) {
                diff > 0 ? next() : prev();
            }
            startTimer();
        }, {
            passive: true
        });

        slider.addEventListener('mouseenter', stopTimer);
        slider.addEventListener('mouseleave', startTimer);

        document.addEventListener('visibilitychange', () => {
            document.hidden ? stopTimer() : startTimer();
        });

        showSlide(0);
        startTimer();
    });
</script>
