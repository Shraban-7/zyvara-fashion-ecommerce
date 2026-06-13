<section class="why-section">
    <div class="home-wrap">
        <div class="text-center" style="margin-bottom:24px">
            <h2 class="section-title" style="padding-left:0;display:inline-block;position:relative">
                Why Choose {{ $siteName }}?
            </h2>
            <p class="section-sub" style="padding-left:0;margin-top:6px">
                Quality, trust, and style — all in one place
            </p>
        </div>

        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon" style="background:#eff6ff">
                    <i class="fas fa-certificate" style="color:#228bcc;font-size:22px"></i>
                </div>
                <h3 class="why-title">Premium Quality</h3>
                <p class="why-desc">100% quality fabric, guaranteed satisfaction</p>
            </div>

            <div class="why-card">
                <div class="why-icon" style="background:#f0fdf4">
                    <i class="fas fa-tags" style="color:#16a34a;font-size:22px"></i>
                </div>
                <h3 class="why-title">Best Price</h3>
                <p class="why-desc">Unbeatable value on every item we sell</p>
            </div>

            <div class="why-card">
                <div class="why-icon" style="background:#faf5ff">
                    <i class="fas fa-shield-alt" style="color:#7c3aed;font-size:22px"></i>
                </div>
                <h3 class="why-title">Trusted Brand</h3>
                <p class="why-desc">10,000+ happy customers and counting</p>
            </div>

            <div class="why-card">
                <div class="why-icon" style="background:#fff7ed">
                    <i class="fas fa-truck" style="color:#ea580c;font-size:22px"></i>
                </div>
                <h3 class="why-title">Fast Delivery</h3>
                <p class="why-desc">Swift, reliable delivery right to your door</p>
            </div>

            <div class="why-card">
                <div class="why-icon" style="background:#fef2f2">
                    <i class="fas fa-undo-alt" style="color:#dc2626;font-size:22px"></i>
                </div>
                <h3 class="why-title">Easy Returns</h3>
                <p class="why-desc">Hassle-free returns & exchange policy</p>
            </div>

            <div class="why-card">
                <div class="why-icon" style="background:#f0f9ff">
                    <i class="fas fa-headset" style="color:#0284c7;font-size:22px"></i>
                </div>
                <h3 class="why-title">24/7 Support</h3>
                <p class="why-desc">Always here whenever you need help</p>
            </div>
        </div>
    </div>
</section>

<style>
    .why-section {
        padding: 32px 0;
        background: linear-gradient(160deg, #f0f7fd 0%, #fff 50%, #f8f9fa 100%);
    }
    @media (min-width: 768px) { .why-section { padding: 48px 0; } }

    .why-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    @media (min-width: 640px)  { .why-grid { grid-template-columns: repeat(3, 1fr); gap: 12px; } }
    @media (min-width: 1024px) { .why-grid { grid-template-columns: repeat(6, 1fr); gap: 14px; } }

    .why-card {
        background: #fff;
        border: 1.5px solid #f0f0f0;
        border-radius: 16px;
        padding: 18px 14px;
        text-align: center;
        transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
    }
    .why-card:hover {
        border-color: #a3d3ef;
        box-shadow: 0 6px 20px rgba(34,139,204,0.12);
        transform: translateY(-3px);
    }

    .why-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px;
        transition: transform 0.25s;
    }
    .why-card:hover .why-icon { transform: scale(1.1); }

    .why-title {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }
    @media (min-width: 768px) { .why-title { font-size: 14px; } }

    .why-desc {
        font-size: 11px;
        color: #9ca3af;
        line-height: 1.45;
    }
    @media (min-width: 768px) { .why-desc { font-size: 12px; } }
</style>