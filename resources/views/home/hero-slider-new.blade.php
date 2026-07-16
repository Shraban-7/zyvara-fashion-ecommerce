<?php
$sliders = $banners->where('position', \App\Enums\BannerPosition::HERO)->sortBy('sort_order');
$promos = $banners->where('position', \App\Enums\BannerPosition::PROMOTIONAL)->sortBy('sort_order');
$promoList = $promos->take(2)->values();
?>

<section class="relative h-[78vh] min-h-[520px] w-full overflow-hidden bg-primary md:h-screen" aria-label="Featured promotions">
    {{-- Background image grid / editorial image --}}
    <div id="hero-slider" class="absolute inset-0 h-full w-full" role="region" aria-roledescription="carousel" aria-label="Hero banner carousel">
        @foreach ($sliders as $index => $banner)
            <div class="hero-slide absolute inset-0 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                role="group" aria-roledescription="slide"
                aria-label="Slide {{ $index + 1 }} of {{ $sliders->count() }}"
                aria-hidden="{{ $index !== 0 ? 'true' : 'false' }}">
                <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}" class="h-full w-full object-cover"
                    width="1600" height="900">
            </div>
        @endforeach

        {{-- Top gradient behind the (fixed) header for nav legibility --}}
        <div class="pointer-events-none absolute inset-x-0 top-0 z-20 h-40 bg-gradient-to-b from-primary/70 to-transparent"></div>

        {{-- Subtle dark gradient overlay for text contrast --}}
        <div class="pointer-events-none absolute inset-0 z-20 bg-gradient-to-t from-primary/70 via-primary/20 to-primary/30"></div>
    </div>

    {{-- Hero content --}}
    <div class="relative z-30 flex h-full w-full items-end">
        <div class="mx-auto w-full max-w-[1320px] px-5 pb-16 sm:px-8 md:pb-24">
            @if ($sliders->isNotEmpty() && $sliders->first()->title)
                <p class="mb-4 font-body text-sm font-medium uppercase tracking-[0.25em] text-accent">
                    {{ $sliders->first()->subtitle ?? 'New Season' }}
                </p>
                <h1 class="max-w-3xl font-heading text-4xl font-semibold leading-[1.1] text-surface-elevated sm:text-6xl md:text-7xl">
                    {{ $sliders->first()->title }}
                </h1>
                <p class="mt-5 max-w-xl font-body text-base text-surface-elevated/85 sm:text-lg">
                    Timeless essentials, thoughtfully made. Discover the pieces defining our new collection.
                </p>

                <div class="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center">
                    <a href="{{ $sliders->first()->link ?? route('products.index') }}"
                        class="tap-effect inline-flex items-center justify-center rounded-full bg-accent px-9 py-4 font-body text-sm font-semibold tracking-wide text-primary transition-colors duration-300 hover:bg-accent-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-primary">
                        Shop Now
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="font-body text-sm font-medium text-secondary transition-colors duration-300 hover:text-surface-elevated focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-primary">
                        View Lookbook
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @else
                <h1 class="max-w-3xl font-heading text-4xl font-semibold leading-[1.1] text-surface-elevated sm:text-6xl md:text-7xl">
                    New Season Arrivals
                </h1>
                <p class="mt-5 max-w-xl font-body text-base text-surface-elevated/85 sm:text-lg">
                    Timeless essentials, thoughtfully made. Discover the pieces defining our new collection.
                </p>
                <div class="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center">
                    <a href="{{ route('products.index') }}"
                        class="tap-effect inline-flex items-center justify-center rounded-full bg-accent px-9 py-4 font-body text-sm font-semibold tracking-wide text-primary transition-colors duration-300 hover:bg-accent-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-primary">
                        Discover the Collection
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="font-body text-sm font-medium text-secondary transition-colors duration-300 hover:text-surface-elevated focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-primary">
                        View Lookbook
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Scroll-down indicator --}}
    <a href="#categories" aria-label="Scroll to categories"
        class="absolute bottom-6 left-1/2 z-30 hidden -translate-x-1/2 flex-col items-center gap-2 text-secondary transition-colors duration-300 hover:text-surface-elevated focus:outline-none focus-visible:ring-2 focus-visible:ring-accent md:flex">
        <span class="font-body text-[11px] uppercase tracking-[0.3em]">Scroll</span>
        <i class="fas fa-chevron-down animate-bounce text-xs"></i>
    </a>

    {{-- Progress Dots --}}
    @if ($sliders->count() > 1)
        <div class="progress-dots absolute bottom-6 right-5 z-30 flex gap-2 md:right-8" role="tablist" aria-label="Slide indicators">
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
    @endif

    {{-- Nav Arrows --}}
    @if ($sliders->count() > 1)
        <button id="prevBtn" aria-label="Previous slide"
            class="bento-nav absolute left-4 top-1/2 z-30 -translate-y-1/2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button id="nextBtn" aria-label="Next slide"
            class="bento-nav absolute right-4 top-1/2 z-30 -translate-y-1/2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    @endif
</section>

<style>
    .hero-slide {
        transition: opacity 700ms ease-in-out;
    }

    /* Navigation */
    .bento-nav {
        width: 40px;
        height: 40px;
        border-radius: 9999px;
        background: rgba(250, 248, 245, 0.9);
        backdrop-filter: blur(8px);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px rgba(26, 26, 26, 0.18);
        transition: opacity 0.2s, transform 0.18s, background 0.2s;
        color: #1A1A1A;
        opacity: 0;
        outline: none;
    }

    section:hover .bento-nav,
    .bento-nav:focus-visible {
        opacity: 1;
    }

    .bento-nav:hover {
        transform: translateY(-50%) scale(1.08);
        background: #FAF8F5;
    }

    .bento-nav:focus-visible {
        box-shadow: 0 0 0 3px rgba(201, 168, 124, 0.6);
    }

    /* Progress Dots */
    .progress-dot {
        background: none;
        border: none;
        padding: 6px;
        cursor: pointer;
        border-radius: 4px;
        outline: none;
        transition: transform 0.15s;
    }

    .progress-dot:hover {
        transform: scale(1.2);
    }

    .progress-dot:focus-visible {
        box-shadow: 0 0 0 2px rgba(201, 168, 124, 0.7);
    }

    .dot-track {
        width: 28px;
        height: 3px;
        border-radius: 2px;
        background: rgba(250, 248, 245, 0.35);
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #C9A87C;
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    @media (prefers-reduced-motion: reduce) {
        .hero-slide,
        .progress-fill {
            transition: none !important;
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

        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        if (nextBtn) nextBtn.addEventListener('click', () => {
            next();
            startTimer();
        });
        if (prevBtn) prevBtn.addEventListener('click', () => {
            prev();
            startTimer();
        });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                showSlide(i);
                startTimer();
            });
        });

        const heroSection = document.querySelector('section[aria-label="Featured promotions"]');
        heroSection.addEventListener('keydown', (e) => {
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

        heroSection.addEventListener('mouseenter', stopTimer);
        heroSection.addEventListener('mouseleave', startTimer);

        document.addEventListener('visibilitychange', () => {
            document.hidden ? stopTimer() : startTimer();
        });

        showSlide(0);
        startTimer();
    });
</script>
