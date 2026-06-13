@if($ourBrands->count())
<section class="home-section">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">Our Brands</h2>
                <p class="section-sub">Premium labels, exceptional quality</p>
            </div>
        </div>

        <div class="brands-grid">
            @foreach ($ourBrands as $brand)
            <a href="{{ route('products.index') }}?brands={{ $brand->slug }}"
                class="brand-card group">
                <div class="brand-logo-wrap">
                    <img src="{{ set_image($brand->logo) }}" alt="{{ $brand->name }}"
                        class="brand-logo">
                </div>
                <span class="brand-name">{{ $brand->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    .brands-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    @media (min-width: 480px)  { .brands-grid { grid-template-columns: repeat(4, 1fr); gap: 12px; } }
    @media (min-width: 768px)  { .brands-grid { grid-template-columns: repeat(5, 1fr); gap: 14px; } }
    @media (min-width: 1024px) { .brands-grid { grid-template-columns: repeat(6, 1fr); gap: 16px; } }

    .brand-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        padding: 14px 10px;
        border: 1.5px solid #f0f0f0;
        border-radius: 14px;
        background: #fff;
        transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
    }
    .brand-card:hover {
        border-color: #a3d3ef;
        box-shadow: 0 6px 20px rgba(34,139,204,0.14);
        transform: translateY(-3px);
    }

    .brand-logo-wrap {
        width: 64px; height: 64px;
        display: flex; align-items: center; justify-content: center;
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
        transition: background 0.2s;
    }
    .brand-card:hover .brand-logo-wrap { background: #e8f4fb; }

    .brand-logo {
        max-width: 100%; max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s;
    }
    .brand-card:hover .brand-logo { transform: scale(1.08); }

    .brand-name {
        font-size: 11px;
        font-weight: 700;
        color: #374151;
        text-align: center;
        line-height: 1.3;
        transition: color 0.2s;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 100%;
    }
    .brand-card:hover .brand-name { color: #228bcc; }
</style>
@endif