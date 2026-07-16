@php
    $posts = \Illuminate\Support\Facades\Cache::remember('social_posts_active', now()->addHours(6), function () {
        return \App\Models\SocialPost::active()->ordered()->get();
    });

    $instagramUrl = \App\Models\Setting::get('instagram_url');
    $facebookUrl = \App\Models\Setting::get('facebook_url');

    $igHandle = $instagramUrl ? '@' . basename(rtrim($instagramUrl, '/')) : '@SpinnerFashion';
    $sectionTitle = isset($section) && $section->title ? $section->title : 'Follow Us';
@endphp

@if($posts->isNotEmpty() || $instagramUrl || $facebookUrl)
<section class="sf-section">
    <div class="sf-shell">
        <div class="sf-head">
            <h2 class="sf-title">{{ $sectionTitle }}</h2>
            <p class="sf-sub">Tag us in your looks for a chance to be featured. Follow along for daily inspiration.</p>

            <div class="sf-links">
                @if($instagramUrl)
                <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="sf-link">
                    <i class="fab fa-instagram"></i> {{ $igHandle }}
                </a>
                @endif
                @if($facebookUrl)
                <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="sf-link">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                @endif
            </div>
        </div>

        @if($posts->isNotEmpty())
        <div class="sf-grid">
            @foreach($posts as $post)
                <a href="{{ $post->post_url ?: ($post->platform === 'facebook' ? $facebookUrl : $instagramUrl) ?: '#' }}"
                    target="_blank" rel="noopener noreferrer"
                    class="sf-card group"
                    aria-label="{{ $post->platform_label }} post: {{ $post->caption }}">
                    <img src="{{ storage_url($post->image) }}" alt="{{ $post->caption ?? 'Social post' }}"
                        loading="lazy" class="sf-img">
                    <span class="sf-badge"><i class="{{ $post->platform_icon }}"></i></span>
                    <div class="sf-overlay">
                        <i class="{{ $post->platform_icon }}"></i>
                        @if($post->caption)<span class="sf-cap">{{ $post->caption }}</span>@endif
                    </div>
                </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

<style>
    .sf-section {
        padding: 64px 0 72px;
        background: var(--color-primary);
        width: 100%;
    }

    .sf-shell { max-width: 1320px; margin: 0 auto; padding: 0 20px; }

    .sf-head { text-align: center; margin-bottom: 36px; }

    .sf-title {
        font-size: clamp(26px, 4vw, 40px);
        font-weight: 600;
        font-family: var(--font-heading);
        color: var(--color-surface-elevated);
        letter-spacing: -0.02em;
        margin: 0;
    }

    .sf-sub {
        margin: 12px auto 0;
        max-width: 480px;
        font-size: clamp(13px, 2vw, 15px);
        color: rgba(250, 248, 245, 0.7);
        line-height: 1.6;
    }

    .sf-links { display: flex; justify-content: center; flex-wrap: wrap; gap: 12px; margin-top: 20px; }

    .sf-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 99px;
        border: 1px solid rgba(250, 248, 245, 0.3);
        color: var(--color-surface-elevated);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .sf-link:hover {
        background: var(--color-accent);
        color: var(--color-primary);
        border-color: var(--color-accent);
    }

    .sf-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    @media (min-width: 640px) { .sf-grid { grid-template-columns: repeat(3, 1fr); gap: 16px; } }
    @media (min-width: 1024px) { .sf-grid { grid-template-columns: repeat(6, 1fr); } }

    .sf-card {
        position: relative;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        border-radius: 16px;
        background: var(--color-surface-muted);
        display: block;
        text-decoration: none;
    }

    .sf-img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .sf-card:hover .sf-img { transform: scale(1.08); }

    .sf-badge {
        position: absolute;
        top: 10px; left: 10px;
        width: 28px; height: 28px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px;
        background: rgba(26, 26, 26, 0.55);
        color: #fff;
        font-size: 13px;
        backdrop-filter: blur(4px);
    }

    .sf-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: rgba(26, 26, 26, 0);
        opacity: 0;
        transition: all 0.3s ease;
        color: var(--color-surface-elevated);
    }
    .sf-overlay i { font-size: 22px; }
    .sf-cap { font-size: 12px; font-weight: 600; letter-spacing: 0.04em; }
    .sf-card:hover .sf-overlay { background: rgba(26, 26, 26, 0.45); opacity: 1; }

    @media (prefers-reduced-motion: reduce) {
        .sf-img { transition: none !important; }
    }
</style>
@endif
