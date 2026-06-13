@if($womensProducts->isNotEmpty())
<section class="collection-section collection-section--alt">
    <div class="collection-wrap">
        <div class="section-head">
            <div class="section-head-text">
                <span class="section-eyebrow">Elegant Picks</span>
                <h2 class="section-title">Ladies Collection</h2>
                <p class="section-sub">Elegant styles for every woman — from casual to formal</p>
            </div>
            <div class="section-head-actions">
                <div class="swiper-nav">
                    <button class="swiper-btn swiper-prev" id="womenPrev" aria-label="Previous slide">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button class="swiper-btn swiper-next" id="womenNext" aria-label="Next slide">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                <a href="{{ route('products.index', ['category' => 'women']) }}" class="section-link">
                    View All
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="swiper-container">
            <div class="swiper women-swiper">
                <div class="swiper-wrapper">
                    @foreach($womensProducts as $product)
                        <div class="swiper-slide">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="swiper-progress">
            <div class="swiper-progress-bar" id="womenProgress"></div>
        </div>
    </div>
</section>

<style>
/* ====================================================
   LADIES COLLECTION — Swiper Slider (Matches Men's)
==================================================== */

.collection-section--alt {
    padding: 64px 0 48px;
    background: #fff;
    overflow: hidden;
    width: 100%;
}

.collection-section--alt .collection-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 768px) {
    .collection-section--alt {
        padding: 80px 0 64px;
    }
    .collection-section--alt .collection-wrap {
        padding: 0 24px;
    }
}

@media (min-width: 1280px) {
    .collection-section--alt .collection-wrap {
        padding: 0 32px;
    }
}

/* ── Section Header ── */
.collection-section--alt .section-head {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 36px;
    width: 100%;
}

@media (min-width: 640px) {
    .collection-section--alt .section-head {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
    }
}

.collection-section--alt .section-head-text {
    flex: 1;
    min-width: 0;
}

.collection-section--alt .section-eyebrow {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: #be185d;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    margin-bottom: 10px;
    padding: 5px 14px;
    background: #fdf2f8;
    border: 1px solid #fce7f3;
    border-radius: 99px;
}

.collection-section--alt .section-title {
    font-size: clamp(24px, 4vw, 32px);
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin: 0 0 8px;
    word-wrap: break-word;
}

.collection-section--alt .section-sub {
    font-size: clamp(14px, 2vw, 16px);
    color: #64748b;
    margin: 0;
    line-height: 1.5;
    max-width: 420px;
}

.collection-section--alt .section-head-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

@media (min-width: 640px) {
    .collection-section--alt .section-head-actions {
        gap: 16px;
    }
}

/* ── Swiper Navigation ── */
.collection-section--alt .swiper-nav {
    display: flex;
    gap: 8px;
}

.collection-section--alt .swiper-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    color: #475569;
    cursor: pointer;
    transition: all 0.25s ease;
    outline: none;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .collection-section--alt .swiper-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
    }
}

.collection-section--alt .swiper-btn:hover {
    background: #be185d;
    border-color: #be185d;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(190, 24, 93, 0.2);
}

.collection-section--alt .swiper-btn:active {
    transform: translateY(0);
}

.collection-section--alt .swiper-btn.swiper-button-disabled {
    opacity: 0.35;
    cursor: not-allowed;
    pointer-events: none;
}

.collection-section--alt .swiper-btn i {
    font-size: 12px;
}

@media (min-width: 768px) {
    .collection-section--alt .swiper-btn i {
        font-size: 13px;
    }
}

/* ── View All Link ── */
.collection-section--alt .section-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    text-decoration: none;
    padding: 10px 16px;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    transition: all 0.25s ease;
    white-space: nowrap;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .collection-section--alt .section-link {
        font-size: 13px;
        padding: 11px 20px;
        border-radius: 12px;
    }
}

.collection-section--alt .section-link:hover {
    background: #be185d;
    color: #fff;
    border-color: #be185d;
    box-shadow: 0 4px 12px rgba(190, 24, 93, 0.2);
}

.collection-section--alt .section-link i {
    font-size: 10px;
    transition: transform 0.25s ease;
}

@media (min-width: 768px) {
    .collection-section--alt .section-link i {
        font-size: 11px;
    }
}

.collection-section--alt .section-link:hover i {
    transform: translateX(3px);
}

/* ── Swiper Container ── */
.collection-section--alt .swiper-container {
    width: 100%;
    overflow: hidden;
    position: relative;
}

.collection-section--alt .women-swiper {
    width: 100%;
    overflow: visible;
}

.collection-section--alt .swiper-wrapper {
    width: 100%;
}

.collection-section--alt .swiper-slide {
    height: auto;
    width: calc(50% - 8px);
}

@media (min-width: 480px) {
    .collection-section--alt .swiper-slide {
        width: calc(50% - 12px);
    }
}

@media (min-width: 640px) {
    .collection-section--alt .swiper-slide {
        width: calc(33.333% - 14px);
    }
}

@media (min-width: 1024px) {
    .collection-section--alt .swiper-slide {
        width: calc(25% - 15px);
    }
}

@media (min-width: 1280px) {
    .collection-section--alt .swiper-slide {
        width: calc(20% - 16px);
    }
}

.collection-section--alt .swiper-slide > * {
    height: 100%;
}

/* ── Progress Bar ── */
.collection-section--alt .swiper-progress {
    margin-top: 28px;
    height: 3px;
    background: #e2e8f0;
    border-radius: 99px;
    overflow: hidden;
    width: 100%;
}

@media (min-width: 768px) {
    .collection-section--alt .swiper-progress {
        margin-top: 32px;
    }
}

.collection-section--alt .swiper-progress-bar {
    height: 100%;
    background: #be185d;
    border-radius: 99px;
    width: 0%;
    transition: width 0.3s ease;
}

/* ── Accessibility ── */
@media (prefers-reduced-motion: reduce) {
    .collection-section--alt .swiper-btn,
    .collection-section--alt .section-link,
    .collection-section--alt .section-link i,
    .collection-section--alt .swiper-progress-bar {
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const womenSwiper = new Swiper('.women-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        grabCursor: true,
        speed: 600,
        watchOverflow: true,
        
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        
        loop: true,
        
        navigation: {
            nextEl: '#womenNext',
            prevEl: '#womenPrev',
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
                updateProgress(this, 'womenProgress');
            },
            slideChange: function() {
                updateProgress(this, 'womenProgress');
            },
            resize: function() {
                updateProgress(this, 'womenProgress');
            },
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