@if($onSaleProducts->isNotEmpty())
@php $h = isset($section) ? $section->headings() : ['eyebrow' => 'Limited Time', 'title' => 'On Sale', 'subtitle' => 'Flash deals — grab them before they are gone']; @endphp
<section class="home-section home-section--sale">
    <div class="home-wrap">
        <div class="section-head">
            <div class="section-head-text">
                <span class="section-eyebrow">{{ $h['eyebrow'] }}</span>
                <h2 class="section-title">{{ $h['title'] }}</h2>
                <p class="section-sub">{{ $h['subtitle'] }}</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'on-sale']) }}" class="section-link">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        {{-- Sale countdown banner --}}
        <div class="sale-strip">
            <div class="sale-strip-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <div class="sale-strip-text">
                <span class="sale-strip-label">Flash Sale</span>
                <span class="sale-strip-value">Save up to <strong>50% OFF</strong></span>
            </div>
            <div class="sale-strip-timer" id="saleTimer">
                <span class="timer-block"><span class="timer-num" id="timerHours">04</span><span class="timer-label">Hrs</span></span>
                <span class="timer-sep">:</span>
                <span class="timer-block"><span class="timer-num" id="timerMinutes">32</span><span class="timer-label">Min</span></span>
                <span class="timer-sep">:</span>
                <span class="timer-block"><span class="timer-num" id="timerSeconds">18</span><span class="timer-label">Sec</span></span>
            </div>
        </div>

        <div class="products-grid products-grid--sale">
            @foreach($onSaleProducts as $product)
                <x-product-card :product="$product" badgeType="SALE" />
            @endforeach
        </div>
    </div>
</section>

<style>
    /* ====================================================
       SALE SECTION — Charcoal Theme
    ==================================================== */

    .home-section--sale {
        padding: 48px 0;
        background: var(--color-surface-muted);
        width: 100%;
        border-top: 1px solid var(--color-border);
        border-bottom: 1px solid var(--color-border);
    }

    @media (min-width: 768px) {
        .home-section--sale {
            padding: 64px 0;
        }
    }

    /* ── Header ── */
    .home-section--sale .section-head {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 32px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .home-section--sale .section-head {
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }
    }

    .home-section--sale .section-head-text {
        flex: 1;
        min-width: 0;
    }

    .home-section--sale .section-eyebrow {
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

    .home-section--sale .section-title {
        font-size: clamp(22px, 4vw, 30px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: var(--color-primary);
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0 0 8px;
        word-wrap: break-word;
    }

    .home-section--sale .section-sub {
        font-size: clamp(13px, 2vw, 15px);
        color: var(--color-secondary);
        margin: 0;
        line-height: 1.5;
        max-width: 420px;
    }

    .home-section--sale .section-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--color-primary);
        text-decoration: none;
        padding: 10px 18px;
        background: var(--color-surface-elevated);
        border: 1.5px solid var(--color-border);
        border-radius: 12px;
        transition: all 0.25s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .home-section--sale .section-link:hover {
        background: var(--color-primary);
        color: var(--color-surface-elevated);
        border-color: var(--color-primary);
        box-shadow: 0 4px 12px rgba(26, 26, 26, 0.18);
    }

    .home-section--sale .section-link i {
        font-size: 11px;
        transition: transform 0.25s ease;
    }

    .home-section--sale .section-link:hover i {
        transform: translateX(3px);
    }

    /* ── Sale Strip (distinct dark band) ── */
    .sale-strip {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--color-primary);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 32px;
        box-shadow: 0 4px 16px rgba(26, 26, 26, 0.18);
        flex-wrap: wrap;
    }

    @media (min-width: 768px) {
        .sale-strip {
            padding: 16px 24px;
            gap: 16px;
            flex-wrap: nowrap;
        }
    }

    .sale-strip-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(250, 248, 245, 0.1);
        border-radius: 10px;
        flex-shrink: 0;
    }

    .sale-strip-icon i {
        font-size: 16px;
        color: var(--color-accent);
    }

    .sale-strip-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex: 1;
    }

    .sale-strip-label {
        font-size: 11px;
        font-weight: 800;
        color: rgba(250, 248, 245, 0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        line-height: 1;
    }

    .sale-strip-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--color-surface-elevated);
        line-height: 1.3;
    }

    .sale-strip-value strong {
        color: var(--color-accent);
        font-weight: 900;
    }

    /* ── Timer ── */
    .sale-strip-timer {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(250, 248, 245, 0.08);
        padding: 8px 14px;
        border-radius: 10px;
        flex-shrink: 0;
    }

    .timer-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1px;
        min-width: 32px;
    }

    .timer-num {
        font-size: 18px;
        font-weight: 900;
        color: var(--color-surface-elevated);
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }

    .timer-label {
        font-size: 9px;
        font-weight: 600;
        color: rgba(250, 248, 245, 0.5);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        line-height: 1;
    }

    .timer-sep {
        font-size: 16px;
        font-weight: 700;
        color: rgba(250, 248, 245, 0.3);
        line-height: 1;
        margin-top: -8px;
    }

    /* ── Grid ── */
    .products-grid--sale {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        width: 100%;
        margin-top: 0;
    }

    @media (min-width: 640px) {
        .products-grid--sale {
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
    }

    @media (min-width: 1024px) {
        .products-grid--sale {
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
    }

    @media (min-width: 1280px) {
        .products-grid--sale {
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }
    }

    /* ── Accessibility ── */
    @media (prefers-reduced-motion: reduce) {
        .home-section--sale .section-link,
        .home-section--sale .section-link i,
        .sale-strip {
            transition: none !important;
        }
    }
</style>

<script>
    // Simple countdown timer
    (function() {
        const timer = document.getElementById('saleTimer');
        if (!timer) return;

        let hours = 4, minutes = 32, seconds = 18;

        function updateTimer() {
            seconds--;
            if (seconds < 0) {
                seconds = 59;
                minutes--;
            }
            if (minutes < 0) {
                minutes = 59;
                hours--;
            }
            if (hours < 0) {
                hours = 23;
                minutes = 59;
                seconds = 59;
            }

            document.getElementById('timerHours').textContent = String(hours).padStart(2, '0');
            document.getElementById('timerMinutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('timerSeconds').textContent = String(seconds).padStart(2, '0');
        }

        setInterval(updateTimer, 1000);
    })();
</script>
@endif