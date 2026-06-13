<section class="cat-section">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-sub">Discover our wide range of collections</p>
            </div>
        </div>

        <div class="cat-grid">
            @foreach($allMenuCategories as $category)
            <a href="{{ route('products.index') }}?categories={{ $category->slug }}" class="cat-item group">
                <div class="cat-circle">
                    <img src="{{ set_image($category->image) }}" alt="{{ $category->name }}"
                        class="w-full h-full object-cover transition-transform duration-400 group-hover:scale-110">
                </div>
                <span class="cat-label">{{ $category->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    .cat-section {
        padding: 28px 0 24px;
        background: #fff;
    }

    .cat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    @media (min-width: 480px)  { .cat-grid { grid-template-columns: repeat(5, 1fr); gap: 12px; } }
    @media (min-width: 768px)  { .cat-grid { grid-template-columns: repeat(7, 1fr); gap: 14px; } }
    @media (min-width: 1024px) { .cat-grid { grid-template-columns: repeat(9, 1fr); gap: 16px; } }

    .cat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .cat-circle {
        width: 60px; height: 60px;
        border-radius: 50%;
        overflow: hidden;
        border: 2.5px solid #f0f0f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
        flex-shrink: 0;
    }

    @media (min-width: 640px)  { .cat-circle { width: 72px;  height: 72px; } }
    @media (min-width: 768px)  { .cat-circle { width: 80px;  height: 80px; } }
    @media (min-width: 1024px) { .cat-circle { width: 88px;  height: 88px; } }

    .cat-item:hover .cat-circle {
        border-color: #228bcc;
        box-shadow: 0 4px 16px rgba(34,139,204,0.25);
        transform: translateY(-3px);
    }

    .cat-label {
        font-size: 10px;
        font-weight: 600;
        color: #374151;
        text-align: center;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
        max-width: 72px;
    }

    @media (min-width: 768px)  { .cat-label { font-size: 11px; max-width: 88px; } }
    @media (min-width: 1024px) { .cat-label { font-size: 12px; max-width: 96px; } }

    .cat-item:hover .cat-label { color: #228bcc; }
</style>