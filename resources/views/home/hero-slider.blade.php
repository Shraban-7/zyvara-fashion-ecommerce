{{-- Hero Slider — Premium Redesign --}}
<?php
$slides = [
    [
        'image'    => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1400&h=700&fit=crop&q=80',
        'eyebrow'  => 'NEW COLLECTION 2026',
        'eyeColor' => '#228bcc',
        'title'    => 'Premium Fashion',
        'accent'   => 'For Every Occasion',
        'accentC'  => '#228bcc',
        'sub'      => 'Discover the perfect blend of style and comfort with Bangladesh's most trusted clothing brand.',
        'cta'      => 'Shop Now',
        'ctaLink'  => route('products.index'),
        'badge'    => ['icon' => 'fa-award',   'label' => 'Premium Quality', 'note' => '100% Guaranteed'],
        'badge2'   => ['icon' => 'fa-truck',   'label' => 'Free Delivery',   'note' => 'Orders over ৳2000'],
    ],
    [
        'image'    => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1400&h=700&fit=crop&q=80',
        'eyebrow'  => 'LADIES COLLECTION',
        'eyeColor' => '#ec4899',
        'title'    => 'Elegant Styles',
        'accent'   => 'For Modern Women',
        'accentC'  => '#f472b6',
        'sub'      => 'Explore our exclusive collection designed for the contemporary woman who values style and sophistication.',
        'cta'      => 'Explore Now',
        'ctaLink'  => route('products.index', ['category' => 'women']),
        'badge'    => ['icon' => 'fa-gem',      'label' => 'Exclusive Designs', 'note' => 'Curated Collection'],
        'badge2'   => ['icon' => 'fa-heart',    'label' => '10K+ Happy',        'note' => 'Customers'],
    ],
    [
        'image'    => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1400&h=700&fit=crop&q=80',
        'eyebrow'  => 'EID SPECIAL 2026',
        'eyeColor' => '#f59e0b',
        'title'    => 'Festive Collection',
        'accent'   => 'Now Available',
        'accentC'  => '#fbbf24',
        'sub'      => 'Celebrate this Eid in style with our handpicked traditional and contemporary festive wear.',
        'cta'      => 'View Collection',
        'ctaLink'  => route('products.index', ['filter' => 'featured']),
        'badge'    => ['icon' => 'fa-mosque',   'label' => 'Festive Ready',   'note' => 'Traditional Elegance'],
        'badge2'   => ['icon' => 'fa-percent',  'label' => 'Up to 40% Off',   'note' => 'Special Discount'],
    ],
    [
        'image'    => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1400&h=700&fit=crop&q=80',
        'eyebrow'  => 'JUST ARRIVED',
        'eyeColor' => '#22c55e',
        'title'    => 'Fresh Arrivals',
        'accent'   => 'Every Week',
        'accentC'  => '#4ade80',
        'sub'      => 'Stay ahead of fashion trends with our weekly new arrivals. Be the first to discover what\'s hot.',
        'cta'      => 'New Arrivals',
        'ctaLink'  => route('products.index', ['filter' => 'new-arrivals']),
        'badge'    => ['icon' => 'fa-fire',     'label' => 'Trending Now',    'note' => 'Latest Styles'],
        'badge2'   => ['icon' => 'fa-tags',     'label' => 'Best Prices',     'note' => 'Value Guaranteed'],
    ],
];
$total = count($slides);
?>

<section class="hs2-section">
    {{-- ── Slides ── --}}
    <div class="hs2-track" id="hs2Track">
        @foreach($slides as $i => $s)
        <div class="hs2-slide {{ $i === 0 ? 'is-active' : '' }}" data-index="{{ $i }}">
            {{-- Background image with Ken Burns --}}
            <div class="hs2-bg">
                <img src="{{ $s['image'] }}" alt="{{ $s['title'] }}" class="hs2-bg-img">
            </div>

            {{-- Overlays --}}
            <div class="hs2-overlay-l"></div>
            <div class="hs2-overlay-b"></div>

            {{-- Content --}}
            <div class="hs2-content">
                {{-- Eyebrow tag --}}
                <div class="hs2-eyebrow" style="border-color:{{ $s['eyeColor'] }}40;background:{{ $s['eyeColor'] }}15;">
                    <span class="hs2-dot" style="background:{{ $s['eyeColor'] }}"></span>
                    <span style="color:{{ $s['eyeColor'] }};font-size:11px;font-weight:800;letter-spacing:.1em;">{{ $s['eyebrow'] }}</span>
                </div>

                {{-- Heading --}}
                <h1 class="hs2-heading">
                    {{ $s['title'] }}
                    <span class="hs2-accent" style="color:{{ $s['accentC'] }}">{{ $s['accent'] }}</span>
                </h1>

                {{-- Sub-text --}}
                <p class="hs2-sub">{{ $s['sub'] }}</p>

                {{-- CTA --}}
                <div class="hs2-cta-row">
                    <a href="{{ $s['ctaLink'] }}" class="hs2-btn-primary" style="background:{{ $s['eyeColor'] }}">
                        {{ $s['cta'] }}
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ route('products.index') }}" class="hs2-btn-ghost">
                        Browse All
                    </a>
                </div>

                {{-- Mini badges --}}
                <div class="hs2-badges">
                    <div class="hs2-badge">
                        <div class="hs2-badge-icon" style="border-color:{{ $s['eyeColor'] }}40;background:{{ $s['eyeColor'] }}12;">
                            <i class="fas {{ $s['badge']['icon'] }}" style="color:{{ $s['eyeColor'] }};font-size:14px;"></i>
                        </div>
                        <div>
                            <p class="hs2-badge-label">{{ $s['badge']['label'] }}</p>
                            <p class="hs2-badge-note">{{ $s['badge']['note'] }}</p>
                        </div>
                    </div>
                    <div class="hs2-badge hs2-badge-hide">
                        <div class="hs2-badge-icon" style="border-color:{{ $s['eyeColor'] }}40;background:{{ $s['eyeColor'] }}12;">
                            <i class="fas {{ $s['badge2']['icon'] }}" style="color:{{ $s['eyeColor'] }};font-size:14px;"></i>
                        </div>
                        <div>
                            <p class="hs2-badge-label">{{ $s['badge2']['label'] }}</p>
                            <p class="hs2-badge-note">{{ $s['badge2']['note'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Bottom UI Bar ── --}}
    <div class="hs2-bar">
        {{-- Thumbnail nav --}}
        <div class="hs2-thumbs" id="hs2Thumbs">
            @foreach($slides as $i => $s)
            <button class="hs2-thumb {{ $i === 0 ? 'is-active' : '' }}"
                    data-goto="{{ $i }}"
                    aria-label="Go to slide {{ $i + 1 }}">
                <img src="{{ $s['image'] }}" alt="" class="hs2-thumb-img">
                <div class="hs2-thumb-overlay">
                    <span class="hs2-thumb-num">0{{ $i + 1 }}</span>
                </div>
                <div class="hs2-thumb-progress">
                    <div class="hs2-thumb-bar"></div>
                </div>
            </button>
            @endforeach
        </div>

        {{-- Counter + arrows --}}
        <div class="hs2-controls">
            <span class="hs2-counter">
                <span id="hs2Current">01</span>
                <span class="hs2-counter-sep">/</span>
                <span>0{{ $total }}</span>
            </span>
            <button id="hs2Prev" class="hs2-arrow" aria-label="Previous slide">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <button id="hs2Next" class="hs2-arrow" aria-label="Next slide">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
        </div>
    </div>
</section>

<style>
/* ====================================================
   HERO SLIDER 2 — Premium Redesign
   Primary: #228bcc
==================================================== */

.hs2-section {
    position: relative;
    overflow: hidden;
    background: #0f172a;
    height: 320px;
}
@media (min-width: 480px)  { .hs2-section { height: 380px; } }
@media (min-width: 768px)  { .hs2-section { height: 480px; } }
@media (min-width: 1024px) { .hs2-section { height: 580px; } }
@media (min-width: 1280px) { .hs2-section { height: 640px; } }

/* ── Track & Slides ── */
.hs2-track {
    position: relative;
    width: 100%;
    height: 100%;
}

.hs2-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.85s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 0;
}
.hs2-slide.is-active { opacity: 1; z-index: 2; }

/* ── Background + Ken Burns ── */
.hs2-bg {
    position: absolute;
    inset: 0;
    overflow: hidden;
}
.hs2-bg-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transform: scale(1.08);
    transition: transform 8s ease-in-out;
}
.hs2-slide.is-active .hs2-bg-img { transform: scale(1.0); }

/* ── Overlays ── */
.hs2-overlay-l {
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg, rgba(0,0,0,0.80) 0%, rgba(0,0,0,0.45) 50%, rgba(0,0,0,0.10) 100%);
    z-index: 1;
}
.hs2-overlay-b {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 45%);
    z-index: 1;
}

/* ── Content ── */
.hs2-content {
    position: absolute;
    inset: 0;
    z-index: 3;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 20px 20px 80px;
    max-width: 680px;
}
@media (min-width: 640px)  { .hs2-content { padding: 28px 32px 90px; } }
@media (min-width: 1024px) { .hs2-content { padding: 40px 64px 110px; } }
@media (min-width: 1280px) { .hs2-content { padding: 48px 80px 120px; } }

/* Eyebrow */
.hs2-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid;
    border-radius: 99px;
    padding: 5px 14px;
    width: fit-content;
    margin-bottom: 14px;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);

    /* entrance */
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.55s 0.1s ease, transform 0.55s 0.1s ease;
}
.hs2-slide.is-active .hs2-eyebrow { opacity: 1; transform: translateY(0); }

.hs2-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    animation: hs2pulse 1.6s ease-in-out infinite;
}
@keyframes hs2pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.55; transform: scale(0.8); }
}

/* Heading */
.hs2-heading {
    font-size: clamp(26px, 5vw, 64px);
    font-weight: 900;
    color: #fff;
    line-height: 1.1;
    margin-bottom: 12px;
    text-shadow: 0 2px 20px rgba(0,0,0,0.35);

    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s 0.22s ease, transform 0.6s 0.22s ease;
}
.hs2-slide.is-active .hs2-heading { opacity: 1; transform: translateY(0); }

.hs2-accent { display: block; }

/* Sub */
.hs2-sub {
    font-size: clamp(12px, 1.6vw, 16px);
    color: rgba(255,255,255,0.78);
    line-height: 1.6;
    margin-bottom: 22px;
    max-width: 480px;

    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s 0.35s ease, transform 0.6s 0.35s ease;
}
.hs2-slide.is-active .hs2-sub { opacity: 1; transform: translateY(0); }

/* CTA row */
.hs2-cta-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;

    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s 0.46s ease, transform 0.6s 0.46s ease;
}
.hs2-slide.is-active .hs2-cta-row { opacity: 1; transform: translateY(0); }

.hs2-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    border-radius: 99px;
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.28);
    transition: filter 0.18s, transform 0.15s;
    letter-spacing: 0.01em;
}
.hs2-btn-primary:hover { filter: brightness(1.12); transform: translateY(-2px); }

.hs2-btn-ghost {
    display: inline-flex;
    align-items: center;
    padding: 10px 22px;
    border-radius: 99px;
    border: 1.5px solid rgba(255,255,255,0.55);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.18s, border-color 0.18s;
}
.hs2-btn-ghost:hover { background: rgba(255,255,255,0.15); border-color: #fff; }

/* Badges */
.hs2-badges {
    display: flex;
    gap: 16px;

    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.6s 0.56s ease, transform 0.6s 0.56s ease;
}
.hs2-slide.is-active .hs2-badges { opacity: 1; transform: translateY(0); }

.hs2-badge {
    display: flex;
    align-items: center;
    gap: 10px;
}
.hs2-badge-hide { display: none; }
@media (min-width: 480px) { .hs2-badge-hide { display: flex; } }

.hs2-badge-icon {
    width: 40px; height: 40px;
    border-radius: 50%;
    border: 1px solid;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.hs2-badge-label {
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    margin: 0;
}
.hs2-badge-note {
    font-size: 10px;
    color: rgba(255,255,255,0.55);
    margin: 0;
}

/* ── Bottom Bar ── */
.hs2-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 16px 12px;
    gap: 12px;
}
@media (min-width: 640px)  { .hs2-bar { padding: 0 32px 16px; } }
@media (min-width: 1024px) { .hs2-bar { padding: 0 64px 20px; } }
@media (min-width: 1280px) { .hs2-bar { padding: 0 80px 24px; } }

/* Thumbnails */
.hs2-thumbs {
    display: flex;
    gap: 8px;
    flex: 1;
}

.hs2-thumb {
    position: relative;
    width: 52px;
    height: 36px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid rgba(255,255,255,0.25);
    cursor: pointer;
    flex-shrink: 0;
    transition: border-color 0.25s, transform 0.2s;
    padding: 0;
    background: none;
}
@media (min-width: 480px) { .hs2-thumb { width: 64px; height: 44px; } }
@media (min-width: 768px) { .hs2-thumb { width: 80px; height: 52px; border-radius: 10px; } }

.hs2-thumb.is-active {
    border-color: #fff;
    transform: scale(1.06);
}
.hs2-thumb:hover:not(.is-active) { border-color: rgba(255,255,255,0.6); }

.hs2-thumb-img {
    width: 100%; height: 100%;
    object-fit: cover;
}

.hs2-thumb-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.hs2-thumb.is-active .hs2-thumb-overlay { background: rgba(0,0,0,0.15); }

.hs2-thumb-num {
    font-size: 10px;
    font-weight: 800;
    color: rgba(255,255,255,0.85);
}

/* Progress bar inside thumb */
.hs2-thumb-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: rgba(255,255,255,0.2);
    overflow: hidden;
}
.hs2-thumb-bar {
    height: 100%;
    width: 0%;
    background: #fff;
    border-radius: 2px;
}
.hs2-thumb.is-active .hs2-thumb-bar {
    animation: hs2fill 5s linear forwards;
}
@keyframes hs2fill {
    from { width: 0%; }
    to   { width: 100%; }
}

/* Controls */
.hs2-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.hs2-counter {
    font-size: 12px;
    font-weight: 700;
    color: rgba(255,255,255,0.75);
    display: none;
    gap: 4px;
}
@media (min-width: 480px) { .hs2-counter { display: flex; align-items: center; } }

.hs2-counter-sep {
    color: rgba(255,255,255,0.3);
    margin: 0 2px;
}

.hs2-arrow {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: rgba(255,255,255,0.12);
    border: 1.5px solid rgba(255,255,255,0.28);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background 0.18s, border-color 0.18s, transform 0.15s;
    backdrop-filter: blur(4px);
}
.hs2-arrow:hover {
    background: rgba(255,255,255,0.25);
    border-color: rgba(255,255,255,0.6);
    transform: scale(1.08);
}
@media (min-width: 768px) { .hs2-arrow { width: 40px; height: 40px; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slides     = document.querySelectorAll('.hs2-slide');
    const thumbs     = document.querySelectorAll('.hs2-thumb');
    const bars       = document.querySelectorAll('.hs2-thumb-bar');
    const currentEl  = document.getElementById('hs2Current');
    const total      = slides.length;
    if (!total) return;

    let current = 0;
    let timer;
    const INTERVAL = 5000;

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function goTo(idx) {
        slides[current].classList.remove('is-active');
        thumbs[current].classList.remove('is-active');

        // Reset current bar animation by cloning
        const oldBar = bars[current];
        const newBar = oldBar.cloneNode(true);
        oldBar.parentNode.replaceChild(newBar, oldBar);

        current = (idx + total) % total;

        slides[current].classList.add('is-active');
        thumbs[current].classList.add('is-active');
        if (currentEl) currentEl.textContent = pad(current + 1);
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }

    function startTimer() {
        clearInterval(timer);
        timer = setInterval(next, INTERVAL);
    }

    // Thumb clicks
    thumbs.forEach((t) => {
        t.addEventListener('click', () => {
            goTo(parseInt(t.dataset.goto, 10));
            startTimer();
        });
    });

    // Arrow buttons
    document.getElementById('hs2Prev').addEventListener('click', () => { prev(); startTimer(); });
    document.getElementById('hs2Next').addEventListener('click', () => { next(); startTimer(); });

    // Keyboard
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft')  { prev(); startTimer(); }
        if (e.key === 'ArrowRight') { next(); startTimer(); }
    });

    // Init
    goTo(0);
    startTimer();
});
</script>