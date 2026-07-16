<section class="why-section">
    <div class="home-wrap">
        <div class="why-header">
            <h2 class="why-title">Why Choose {{ $siteName }}?</h2>
            <p class="why-subtitle">Quality, trust, and style — all in one place</p>
        </div>

        {{-- Main Features Row --}}
        <div class="why-grid">
            {{-- Premium Quality --}}
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <h3 class="why-card-title">Premium Quality</h3>
                <p class="why-card-desc">100% quality fabric guaranteed</p>
            </div>

            {{-- Best Price --}}
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h3 class="why-card-title">Best Price</h3>
                <p class="why-card-desc">Unbeatable value every day</p>
            </div>

            {{-- Fast Delivery --}}
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 class="why-card-title">Fast Delivery</h3>
                <p class="why-card-desc">2-3 days nationwide</p>
            </div>

            {{-- Easy Returns --}}
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <h3 class="why-card-title">Easy Returns</h3>
                <p class="why-card-desc">7-day hassle-free policy</p>
            </div>
        </div>

        {{-- Bottom Row — Same Card Style --}}
        <div class="why-grid why-grid--bottom">
            {{-- Cash on Delivery --}}
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3 class="why-card-title">Cash on Delivery</h3>
                <p class="why-card-desc">Pay when you receive</p>
            </div>

            {{-- WhatsApp Support --}}
            <div class="why-card">
                <div class="why-icon why-icon--green">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3 class="why-card-title">WhatsApp Support</h3>
                <p class="why-card-desc">24/7 instant help</p>
            </div>

            {{-- Secure Payment --}}
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="why-card-title">Secure Payment</h3>
                <p class="why-card-desc">100% safe checkout</p>
            </div>

            {{-- Authentic Products --}}
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="why-card-title">100% Authentic</h3>
                <p class="why-card-desc">Original guaranteed</p>
            </div>
        </div>
    </div>
</section>

<style>
/* ====================================================
   WHY CHOOSE US — Two Row Matching Cards
==================================================== */

.why-section {
    padding: 48px 0 40px;
    background: var(--color-surface-elevated);
    width: 100%;
    border-top: 1px solid var(--color-border);
    border-bottom: 1px solid var(--color-border);
}

.home-wrap {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
    box-sizing: border-box;
}

@media (min-width: 768px) {
    .why-section {
        padding: 56px 0 48px;
    }
    .home-wrap {
        padding: 0 24px;
    }
}

/* ── Header ── */
.why-header {
    text-align: center;
    margin-bottom: 32px;
}

@media (min-width: 768px) {
    .why-header {
        margin-bottom: 40px;
    }
}

.why-title {
    font-size: clamp(22px, 4vw, 28px);
    font-weight: 600;
    font-family: var(--font-heading);
    color: var(--color-primary);
    letter-spacing: -0.01em;
    line-height: 1.2;
    margin: 0 0 8px;
}

.why-subtitle {
    font-size: clamp(13px, 2vw, 15px);
    color: var(--color-secondary);
    margin: 0;
    line-height: 1.5;
}

/* ── Grid ── */
.why-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    width: 100%;
}

.why-grid--bottom {
    margin-top: 12px;
}

@media (min-width: 640px) {
    .why-grid {
        gap: 16px;
    }
    .why-grid--bottom {
        margin-top: 16px;
    }
}

@media (min-width: 1024px) {
    .why-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .why-grid--bottom {
        margin-top: 20px;
    }
}

/* ── Card ── */
.why-card {
    background: var(--color-surface-muted);
    border: 1.5px solid var(--color-border);
    border-radius: 16px;
    padding: 24px 20px;
    text-align: center;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    width: 100%;
    box-sizing: border-box;
}

.why-card:hover {
    background: var(--color-surface-elevated);
    border-color: var(--color-secondary-200);
    transform: translateY(-3px);
    box-shadow: 0 8px 24px -8px rgba(26, 26, 26, 0.1);
}

@media (min-width: 768px) {
    .why-card {
        padding: 28px 24px;
    }
}

/* ── Icon ── */
.why-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-surface-elevated);
    border: 1.5px solid var(--color-border);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.why-icon--green {
    background: var(--color-success-50);
    border-color: var(--color-success-200);
}

.why-icon--green i {
    color: var(--color-success);
}

.why-card:hover .why-icon {
    background: var(--color-primary);
    border-color: var(--color-primary);
    transform: scale(1.05);
}

.why-card:hover .why-icon--green {
    background: var(--color-success);
    border-color: var(--color-success);
}

.why-icon i {
    font-size: 16px;
    color: var(--color-secondary-700);
    transition: color 0.3s ease;
}

.why-card:hover .why-icon i {
    color: var(--color-surface-elevated);
}

/* ── Text ── */
.why-card-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--color-primary);
    margin: 0;
    line-height: 1.3;
}

.why-card-desc {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-secondary);
    margin: 0;
    line-height: 1.4;
}

/* ── Accessibility ── */
@media (prefers-reduced-motion: reduce) {
    .why-card,
    .why-icon {
        transition: none !important;
    }
    .why-card:hover {
        transform: none;
    }
}
</style>