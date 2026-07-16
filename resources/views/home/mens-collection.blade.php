@if($mensProducts->isNotEmpty())
@php $h = isset($section) ? $section->headings() : ['eyebrow' => 'Curated For You', 'title' => "Men's Collection", 'subtitle' => 'Stylish picks for the modern man — from casual to formal']; @endphp
<section class="collection-section">
    <div class="collection-wrap">
        <div class="section-head">
            <div class="section-head-text">
                <span class="section-eyebrow">{{ $h['eyebrow'] }}</span>
                <h2 class="section-title">{{ $h['title'] }}</h2>
                <p class="section-sub">{{ $h['subtitle'] }}</p>
            </div>
            <div class="section-head-actions">
                <div class="swiper-nav">
                    <button class="swiper-btn swiper-prev" id="menPrev" aria-label="Previous slide">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button class="swiper-btn swiper-next" id="menNext" aria-label="Next slide">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                <a href="{{ route('products.index', ['category' => 'men']) }}" class="section-link">
                    View All
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="swiper-container">
            <div class="swiper men-swiper">
                <div class="swiper-wrapper">
                    @foreach($mensProducts as $product)
                        <div class="swiper-slide">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="swiper-progress">
            <div class="swiper-progress-bar" id="menProgress"></div>
        </div>
    </div>
</section>

<style>
/* ====================================================
   COLLECTION SECTION — Swiper Slider with Autoplay
==================================================== */

.collection-section {
    padding: 64px 0 48px;
    background: var(--color-surface-muted);
    overflow: hidden;
    width: 100%;
}

.collection-wrap {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 768px) {
    .collection-section {
        padding: 80px 0 64px;
    }
    .collection-wrap {
        padding: 0 24px;
    }
}

@media (min-width: 1280px) {
    .collection-wrap {
        padding: 0 32px;
    }
}

/* ── Section Header ── */
.section-head {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 36px;
    width: 100%;
}

@media (min-width: 640px) {
    .section-head {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
    }
}

.section-head-text {
    flex: 1;
    min-width: 0;
}

.section-eyebrow {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary);
    text-transform: uppercase;
    letter-spacing: 0.12em;
    margin-bottom: 10px;
    padding: 5px 14px;
    background: var(--color-surface-elevated);
    border: 1px solid var(--color-border);
    border-radius: 99px;
}

.section-title {
    font-size: clamp(24px, 4vw, 32px);
    font-weight: 600;
    font-family: var(--font-heading);
    color: var(--color-primary);
    letter-spacing: -0.02em;
    line-height: 1.2;
    margin: 0 0 8px;
    word-wrap: break-word;
}

.section-sub {
    font-size: clamp(14px, 2vw, 16px);
    color: var(--color-secondary);
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

@media (min-width: 640px) {
    .section-head-actions {
        gap: 16px;
    }
}

/* ── Swiper Navigation ── */
.swiper-nav {
    display: flex;
    gap: 8px;
}

.swiper-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-surface-elevated);
    border: 1.5px solid var(--color-border);
    border-radius: 10px;
    color: var(--color-secondary-700);
    cursor: pointer;
    transition: all 0.25s ease;
    outline: none;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .swiper-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
    }
}

.swiper-btn:hover {
    background: var(--color-primary);
    border-color: var(--color-primary);
    color: var(--color-surface-elevated);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(26, 26, 26, 0.18);
}

.swiper-btn:active {
    transform: translateY(0);
}

.swiper-btn.swiper-button-disabled {
    opacity: 0.35;
    cursor: not-allowed;
    pointer-events: none;
}

.swiper-btn i {
    font-size: 12px;
}

@media (min-width: 768px) {
    .swiper-btn i {
        font-size: 13px;
    }
}

/* ── View All Link ── */
.section-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: var(--color-primary);
    text-decoration: none;
    padding: 10px 16px;
    background: var(--color-surface-elevated);
    border: 1.5px solid var(--color-border);
    border-radius: 10px;
    transition: all 0.25s ease;
    white-space: nowrap;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .section-link {
        font-size: 13px;
        padding: 11px 20px;
        border-radius: 12px;
    }
}

.section-link:hover {
    background: var(--color-primary);
    color: var(--color-surface-elevated);
    border-color: var(--color-primary);
    box-shadow: 0 4px 12px rgba(26, 26, 26, 0.18);
}

.section-link i {
    font-size: 10px;
    transition: transform 0.25s ease;
}

@media (min-width: 768px) {
    .section-link i {
        font-size: 11px;
    }
}

.section-link:hover i {
    transform: translateX(3px);
}

/* ── Swiper Container ── */
.swiper-container {
    width: 100%;
    overflow: hidden;
    position: relative;
}

.men-swiper {
    width: 100%;
    overflow: visible;
}

.swiper-wrapper {
    width: 100%;
}

.swiper-slide {
    height: auto;
    width: calc(50% - 8px);
}

@media (min-width: 480px) {
    .swiper-slide {
        width: calc(50% - 12px);
    }
}

@media (min-width: 640px) {
    .swiper-slide {
        width: calc(33.333% - 14px);
    }
}

@media (min-width: 1024px) {
    .swiper-slide {
        width: calc(25% - 15px);
    }
}

@media (min-width: 1280px) {
    .swiper-slide {
        width: calc(20% - 16px);
    }
}

/* Ensure product cards fill slide height */
.swiper-slide > * {
    height: 100%;
}

/* ── Progress Bar ── */
.swiper-progress {
    margin-top: 28px;
    height: 3px;
    background: var(--color-border);
    border-radius: 99px;
    overflow: hidden;
    width: 100%;
}

@media (min-width: 768px) {
    .swiper-progress {
        margin-top: 32px;
    }
}

.swiper-progress-bar {
    height: 100%;
    background: var(--color-primary);
    border-radius: 99px;
    width: 0%;
    transition: width 0.3s ease;
}

/* ── Accessibility ── */
@media (prefers-reduced-motion: reduce) {
    .swiper-btn,
    .section-link,
    .section-link i,
    .swiper-progress-bar {
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menSwiper = new Swiper('.men-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        grabCursor: true,
        speed: 600,
        watchOverflow: true,
        
        // Autoplay configuration
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        
        // Loop for infinite scrolling
        loop: true,
        
        // Navigation
        navigation: {
            nextEl: '#menNext',
            prevEl: '#menPrev',
        },
        
        breakpoints: {
            0: {
                slidesPerView: 2,
                spaceBetween: 12,
            },
            480: {
                slidesPerView: 2,
                spaceBetween: 16,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 16,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 20,
            },
            1280: {
                slidesPerView: 5,
                spaceBetween: 20,
            },
        },
        
        on: {
            init: function() {
                updateProgress(this, 'menProgress');
            },
            slideChange: function() {
                updateProgress(this, 'menProgress');
            },
            resize: function() {
                updateProgress(this, 'menProgress');
            },
            autoplayTimeLeft(s, time, progress) {
                // Optional: You can use this for a custom progress animation
            }
        },
    });

    function updateProgress(swiper, progressId) {
        const totalSlides = swiper.slides.length;
        const visibleSlides = swiper.params.slidesPerView;
        const maxIndex = Math.max(totalSlides - visibleSlides, 0);
        const progress = maxIndex > 0 ? (swiper.activeIndex / maxIndex) * 100 : 100;
        const bar = document.getElementById(progressId);
        if (bar) {
            bar.style.width = Math.min(Math.max(progress, 0), 100) + '%';
        }
    }
});
</script>
@endif