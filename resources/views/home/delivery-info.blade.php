{{-- Delivery & Support Info --}}
<section class="features-section">
    <div class="features-wrap">
        <div class="features-grid">

            {{-- Cash on Delivery --}}
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="4"/>
                        <path d="M12 12h.01M8 12h.01M16 12h.01"/>
                        <path d="M2 10h20"/>
                    </svg>
                </div>
                <h3 class="feature-title">Cash on Delivery</h3>
                <p class="feature-desc">Pay when you receive your order safely</p>
            </div>

            {{-- Nationwide Delivery --}}
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                </div>
                <h3 class="feature-title">Nationwide Delivery</h3>
                <p class="feature-desc">Fast shipping across all of Bangladesh</p>
            </div>

            {{-- Easy Return --}}
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
                <h3 class="feature-title">Easy Return</h3>
                <p class="feature-desc">Hassle-free 7-day return policy</p>
            </div>

            {{-- WhatsApp Support --}}
            <div class="feature-card">
                <div class="feature-icon-wrap feature-icon-wrap--green">
                    <svg class="feature-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <h3 class="feature-title">WhatsApp Support</h3>
                <p class="feature-desc">24/7 instant customer assistance</p>
            </div>

        </div>
    </div>
</section>

<style>
/* ====================================================
   FEATURES SECTION — Trust Badges
==================================================== */

.features-section {
    padding: 48px 0;
    background: #0f172a;
    position: relative;
}

.features-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at top, rgba(30, 41, 59, 0.5) 0%, transparent 70%);
    pointer-events: none;
}

.features-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 16px;
    position: relative;
    z-index: 1;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

@media (min-width: 640px) {
    .features-grid {
        gap: 16px;
    }
}

@media (min-width: 768px) {
    .features-section {
        padding: 64px 0;
    }
    .features-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
}

@media (min-width: 1024px) {
    .features-grid {
        gap: 24px;
    }
}

/* ── Feature Card ── */
.feature-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 24px 16px;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(51, 65, 85, 0.5);
    border-radius: 20px;
    transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    backdrop-filter: blur(8px);
}

.feature-card:hover {
    background: rgba(30, 41, 59, 0.7);
    border-color: rgba(71, 85, 105, 0.6);
    transform: translateY(-4px);
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.3);
}

@media (min-width: 768px) {
    .feature-card {
        padding: 32px 20px;
    }
}

/* ── Icon ── */
.feature-icon-wrap {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon-wrap {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.05) rotate(-2deg);
}

.feature-icon-wrap--green {
    background: rgba(34, 197, 94, 0.1);
    border-color: rgba(34, 197, 94, 0.2);
}

.feature-card:hover .feature-icon-wrap--green {
    background: rgba(34, 197, 94, 0.15);
}

.feature-icon {
    width: 24px;
    height: 24px;
    color: #e2e8f0;
}

.feature-icon-wrap--green .feature-icon {
    color: #4ade80;
}

/* ── Text ── */
.feature-title {
    font-size: 14px;
    font-weight: 700;
    color: #f8fafc;
    margin: 0 0 6px;
    line-height: 1.3;
}

@media (min-width: 768px) {
    .feature-title {
        font-size: 16px;
    }
}

.feature-desc {
    font-size: 12px;
    font-weight: 500;
    color: #64748b;
    margin: 0;
    line-height: 1.5;
    max-width: 200px;
}

@media (min-width: 768px) {
    .feature-desc {
        font-size: 13px;
    }
}

/* ── Accessibility ── */
@media (prefers-reduced-motion: reduce) {
    .feature-card,
    .feature-icon-wrap {
        transition: none !important;
    }
    .feature-card:hover {
        transform: none;
    }
}
</style>