<?php
$sliders = $banners->where('position', \App\Enums\BannerPosition::HERO)->sortBy('sort_order');
$promos = $banners->where('position', \App\Enums\BannerPosition::PROMOTIONAL)->sortBy('sort_order');
?>

<section class="hs-section">
    <div class="hs-wrap">
        <div class="hs-layout">

            {{-- ── Left: Main Slider ── --}}
            <div class="hs-main-slider group">
                <div id="hero-slider" class="relative w-full h-full">
                    @foreach ($sliders as $index => $banner)
                    <div class="hero-slide absolute inset-0 transition-opacity duration-[900ms] ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
                        <a href="{{ $banner->link ?? '#' }}" class="block w-full h-full">
                            <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}"
                                class="w-full h-full object-cover">
                        </a>
                    </div>
                    @endforeach
                </div>

                {{-- Nav Arrows --}}
                <button id="prevBtn" aria-label="Previous"
                    class="hs-nav-btn left-3 opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <button id="nextBtn" aria-label="Next"
                    class="hs-nav-btn right-3 opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>

                {{-- Progress dots --}}
                <div class="absolute z-20 bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    @foreach ($sliders as $index => $banner)
                    <div class="w-6 h-1.5 rounded-full bg-white/30 overflow-hidden">
                        <div class="progress-fill h-full bg-white w-0 rounded-full"></div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Right: Promo Banners ── --}}
            <div class="hs-promos">
                @foreach ($promos->take(2) as $promo)
                <div class="relative flex-1 overflow-hidden rounded-2xl bg-gray-200 group min-h-[130px]">
                    <a href="{{ $promo->link ?? '#' }}" class="block w-full h-full">
                        <img src="{{ storage_url($promo->image) }}" alt="{{ $promo->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent group-hover:from-black/10 transition-all duration-300"></div>
                    </a>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

<style>
    .hs-section {
        background: #f8f9fa;
        padding: 20px 0;
    }

    .hs-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .hs-layout {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    @media (min-width: 1024px) {
        .hs-section { padding: 24px 0; }
        .hs-layout {
            flex-direction: row;
            height: 480px;
        }
    }

    /* Main Slider */
    .hs-main-slider {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        background: #e5e7eb;
        box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        aspect-ratio: 16/8;
        flex-shrink: 0;
    }

    @media (min-width: 1024px) {
        .hs-main-slider {
            width: 68%;
            aspect-ratio: unset;
            height: 100%;
        }
    }

    /* Nav Buttons */
    .hs-nav-btn {
        position: absolute;
        z-index: 20;
        top: 50%;
        transform: translateY(-50%);
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(4px);
        border: none;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.18);
        transition: opacity 0.2s, background 0.18s, transform 0.18s;
        color: #1f2937;
    }

    .hs-nav-btn:hover { background: #fff; transform: translateY(-50%) scale(1.1); }

    /* Promo Column */
    .hs-promos {
        display: flex;
        gap: 12px;
        flex-direction: row;
        height: 150px;
    }

    @media (min-width: 640px) { .hs-promos { height: 190px; } }

    @media (min-width: 1024px) {
        .hs-promos {
            flex: 1;
            flex-direction: column;
            height: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.hero-slide');
        const fills  = document.querySelectorAll('.progress-fill');
        if (!slides.length) return;

        let currentIndex = 0;
        const INTERVAL   = 5000;
        let timer;

        function showSlide(idx) {
            slides.forEach((s, i) => {
                s.style.opacity = i === idx ? '1' : '0';
                s.style.zIndex  = i === idx ? '10' : '0';
                fills[i].style.transition = i === idx ? `width ${INTERVAL}ms linear` : 'none';
                fills[i].style.width      = i === idx ? '100%' : '0%';
            });
        }

        function next() { currentIndex = (currentIndex + 1) % slides.length; showSlide(currentIndex); }
        function prev() { currentIndex = (currentIndex - 1 + slides.length) % slides.length; showSlide(currentIndex); }

        function startTimer() { clearInterval(timer); timer = setInterval(next, INTERVAL); }

        document.getElementById('nextBtn').addEventListener('click', () => { next(); startTimer(); });
        document.getElementById('prevBtn').addEventListener('click', () => { prev(); startTimer(); });

        showSlide(0);
        startTimer();
    });
</script>