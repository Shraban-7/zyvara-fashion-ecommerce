@php
    $bentoBanners = \App\Models\Banner::bento()->active()->ordered()->get();
    $bentoHeadings = isset($section) ? $section->headings() : ['eyebrow' => 'Curated', 'title' => 'Explore The Collection', 'subtitle' => ''];
    $bentoTitle = $bentoHeadings['title'];
    $bentoSub = $bentoHeadings['eyebrow'];
@endphp

@if($bentoBanners->isNotEmpty())
<section class="home-section home-section--bento">
    <div class="home-wrap">
        @if($bentoTitle)
        <div class="section-head">
            <div class="section-head-text">
                @if($bentoSub)<span class="section-eyebrow">{{ $bentoSub }}</span>@endif
                <h2 class="section-title">{{ $bentoTitle }}</h2>
            </div>
        </div>
        @endif

        <div class="bento-grid">
            @foreach($bentoBanners as $banner)
                @php $sizeClass = $banner->size?->gridClass() ?? 'bento-item--small'; @endphp
                <a href="{{ $banner->button_link ?: '#' }}" class="bento-item {{ $sizeClass }}">
                    <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}" class="bento-img" loading="lazy">
                    <div class="bento-overlay">
                        @if($banner->subtitle)<span class="bento-eyebrow">{{ $banner->subtitle }}</span>@endif
                        <h3 class="bento-title">{{ $banner->title }}</h3>
                        @if($banner->button_text)
                            <span class="bento-cta">{{ $banner->button_text }} <i class="fas fa-arrow-right"></i></span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    .home-section--bento {
        padding: 48px 0;
        background: var(--color-background);
        width: 100%;
    }

    @media (min-width: 768px) {
        .home-section--bento { padding: 64px 0; }
    }

    .home-section--bento .section-head { margin-bottom: 28px; }

    .home-section--bento .section-eyebrow {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        color: var(--color-accent);
        text-transform: uppercase;
        letter-spacing: 0.14em;
        margin-bottom: 8px;
    }

    .home-section--bento .section-title {
        font-size: clamp(22px, 4vw, 32px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: var(--color-primary);
        letter-spacing: -0.02em;
        margin: 0;
    }

    .bento-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: 180px;
        gap: 12px;
        width: 100%;
    }

    @media (min-width: 768px) {
        .bento-grid {
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 220px;
            gap: 16px;
        }
    }

    .bento-item {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        display: block;
        text-decoration: none;
        background: var(--color-surface-muted);
        border: 1px solid var(--color-border);
    }

    .bento-item--small { grid-column: span 1; grid-row: span 1; }
    .bento-item--wide  { grid-column: span 2; grid-row: span 1; }
    .bento-item--tall  { grid-column: span 1; grid-row: span 2; }
    .bento-item--large { grid-column: span 2; grid-row: span 2; }

    .bento-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .bento-item:hover .bento-img { transform: scale(1.06); }

    .bento-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 18px 20px;
        background: linear-gradient(to top, rgba(26, 26, 26, 0.72) 0%, rgba(26, 26, 26, 0.15) 55%, transparent 100%);
    }

    .bento-eyebrow {
        font-size: 10px;
        font-weight: 700;
        color: var(--color-accent);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 4px;
    }

    .bento-title {
        font-size: clamp(15px, 2.2vw, 20px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: #FAF8F5;
        margin: 0;
        line-height: 1.25;
    }

    .bento-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #FAF8F5;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .bento-cta i { font-size: 10px; transition: transform 0.25s ease; }
    .bento-item:hover .bento-cta i { transform: translateX(4px); }

    @media (prefers-reduced-motion: reduce) {
        .bento-img, .bento-cta i { transition: none !important; }
    }
</style>
@endif
