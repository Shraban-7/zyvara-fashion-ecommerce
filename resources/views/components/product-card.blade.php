@props(['product', 'showBadge' => true, 'badgeType' => 'auto'])

<?php
$badge = null;
$badgeClass = '';

if ($showBadge && $badgeType === 'auto') {
    if ($product->is_new_arrival)    { $badge = 'NEW';      $badgeClass = 'pc2-badge--new';  }
    elseif ($product->is_best_seller){ $badge = 'HOT';      $badgeClass = 'pc2-badge--hot';  }
    elseif ($product->is_on_sale)    { $badge = 'SALE';     $badgeClass = 'pc2-badge--sale'; }
    elseif ($product->is_featured)   { $badge = 'FEATURED'; $badgeClass = 'pc2-badge--feat'; }
} elseif ($showBadge && $badgeType !== 'auto') {
    $badge = $badgeType;
    $badgeClass = 'pc2-badge--new';
}

$discountPercent = 0;
if ($product->compare_price && $product->compare_price > $product->price) {
    $discountPercent = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
}

// Collect variant color swatches (up to 4)
$colorVariants = collect();
try {
    $colorVariants = $product->variants
        ->where('type', 'color')
        ->take(4);
} catch (\Throwable $e) {}

// Brand / category label
$brandLabel = '';
try {
    $brandLabel = $product->brand?->name ?? $product->category?->name ?? '';
} catch (\Throwable $e) {}
?>

<div class="pc2-card group">

    {{-- ── Image Block ── --}}
    <div class="pc2-img-wrap">

        {{-- Main image link --}}
        <a href="{{ route('products.show', $product->slug) }}" class="pc2-img-link">
            <img src="{{ $product->thumbnail }}"
                 alt="{{ $product->name }}"
                 class="pc2-img"
                 loading="lazy">
        </a>

        {{-- Badge top-left --}}
        @if($badge)
            <span class="pc2-badge {{ $badgeClass }}">
                @if($discountPercent > 0 && $product->is_on_sale)
                    -{{ $discountPercent }}%
                @else
                    {{ $badge }}
                @endif
            </span>
        @elseif($discountPercent > 0)
            <span class="pc2-badge pc2-badge--sale">-{{ $discountPercent }}%</span>
        @endif

        {{-- Wishlist / heart top-right --}}
        <button class="pc2-heart" aria-label="Add to wishlist"
            onclick="event.preventDefault()">
            <i class="far fa-heart text-gray-400 text-sm group-[:hover]:text-gray-600"></i>
        </button>

        {{-- Slide-up cart panel on hover --}}
        <div class="pc2-cart-panel">
            <button
                class="pc2-cart-btn"
                onclick="handleProductCardAddToCart({{ $product->id }}, {{ $product->variants->count() }})"
                data-product-id="{{ $product->id }}"
                aria-label="Add to cart">
                <i class="fas fa-shopping-bag text-xs"></i>
                Add to Cart
            </button>
            <button
                onclick="window.productVariantManager.openQuickView({{ $product->id }})"
                class="pc2-qv-btn"
                aria-label="Quick view">
                <i class="far fa-eye text-sm"></i>
            </button>
        </div>
    </div>

    {{-- ── Info Block ── --}}
    <div class="pc2-info">

        {{-- Color swatches + variant label --}}
        @if($colorVariants->isNotEmpty())
        <div class="pc2-swatches">
            @foreach($colorVariants as $variant)
                <span class="pc2-swatch"
                    title="{{ $variant->value }}"
                    style="background: {{ $variant->value }}; border-color: {{ $variant->value === '#ffffff' || strtolower($variant->value) === 'white' ? '#d1d5db' : $variant->value }};"></span>
            @endforeach
            <span class="pc2-swatch-label">{{ $colorVariants->first()->value }}</span>
        </div>
        @endif

        {{-- Brand / category --}}
        @if($brandLabel)
        <p class="pc2-brand">{{ strtoupper($brandLabel) }}</p>
        @endif

        {{-- Name --}}
        <a href="{{ route('products.show', $product->slug) }}" class="pc2-name-link">
            <h3 class="pc2-name">{{ $product->name }}</h3>
        </a>

        {{-- Rating --}}
        @if($product->review_count > 0)
        <div class="pc2-rating">
            <div class="pc2-stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($product->average_rating))
                        <i class="fas fa-star"></i>
                    @elseif($i - 0.5 <= $product->average_rating)
                        <i class="fas fa-star-half-alt"></i>
                    @else
                        <i class="far fa-star pc2-star-empty"></i>
                    @endif
                @endfor
            </div>
            <span class="pc2-rating-count">{{ number_format($product->average_rating, 1) }}</span>
            <span class="pc2-rating-total">({{ $product->review_count }})</span>
        </div>
        @endif

        {{-- Price --}}
        <div class="pc2-price-row">
            <span class="pc2-price">৳{{ number_format($product->price, 0) }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="pc2-compare">৳{{ number_format($product->compare_price, 0) }}</span>
            @endif
        </div>
    </div>
</div>

<style>
/* ====================================================
   PRODUCT CARD v2 — Reference Style
   Inspired by: minimal editorial e-commerce card
   Primary: #228bcc
==================================================== */

.pc2-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.04);
    transition: transform 0.32s cubic-bezier(0.34,1.2,0.64,1),
                box-shadow 0.32s ease;
}

.pc2-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 48px rgba(0,0,0,0.13), 0 4px 12px rgba(34,139,204,0.08);
}

/* ── Image ── */
.pc2-img-wrap {
    position: relative;
    overflow: hidden;
    background: #f4f4f5;
    border-radius: 16px;
    aspect-ratio: 1 / 1;
    flex-shrink: 0;
    /* small inner padding so image feels framed */
    margin: 6px 6px 0;
}

.pc2-img-link {
    display: block;
    width: 100%;
    height: 100%;
}

.pc2-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.55s cubic-bezier(0.25,0.46,0.45,0.94);
    will-change: transform;
}

.pc2-card:hover .pc2-img { transform: scale(1.06); }

/* ── Badge ── */
.pc2-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 3;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.04em;
    padding: 4px 10px;
    border-radius: 99px;
    line-height: 1.4;
}

/* Reference style: white pill with colored text */
.pc2-badge--new  { background: #fff; color: #16a34a; box-shadow: 0 1px 6px rgba(0,0,0,0.12); }
.pc2-badge--hot  { background: #fff; color: #dc2626; box-shadow: 0 1px 6px rgba(0,0,0,0.12); }
.pc2-badge--sale {
    background: #ef4444;
    color: #fff;
    box-shadow: 0 1px 8px rgba(239,68,68,0.35);
}
.pc2-badge--feat { background: #fff; color: #228bcc; box-shadow: 0 1px 6px rgba(0,0,0,0.12); }

/* ── Heart button ── */
.pc2-heart {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 3;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    transition: background 0.2s, transform 0.18s;
}
.pc2-heart:hover {
    background: #fff;
    transform: scale(1.08);
}
.pc2-heart:hover i { color: #ef4444 !important; }

/* ── Slide-up cart panel ── */
.pc2-cart-panel {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    gap: 8px;
    padding: 12px 10px;
    background: linear-gradient(to top, rgba(0,0,0,0.60) 0%, rgba(0,0,0,0.20) 70%, transparent 100%);
    transform: translateY(8px);
    opacity: 0;
    transition: transform 0.3s cubic-bezier(0.34,1.2,0.64,1),
                opacity 0.25s ease;
    z-index: 4;
}

.pc2-card:hover .pc2-cart-panel {
    transform: translateY(0);
    opacity: 1;
}

.pc2-cart-btn {
    flex: 1;
    height: 38px;
    background: #fff;
    color: #111827;
    border: none;
    border-radius: 99px;
    font-size: 12.5px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: background 0.18s, color 0.18s, transform 0.15s;
    box-shadow: 0 2px 12px rgba(0,0,0,0.20);
    letter-spacing: 0.01em;
}
.pc2-cart-btn:hover {
    background: #228bcc;
    color: #fff;
    transform: scale(1.02);
}



.pc2-qv-btn {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(4px);
    border: 1.5px solid rgba(255,255,255,0.5);
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.18s;
}
.pc2-qv-btn:hover { background: rgba(255,255,255,0.35); }

/* ── Info block ── */
.pc2-info {
    padding: 12px 10px 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex: 1;
}

/* Color swatches */
.pc2-swatches {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 1px;
}

.pc2-swatch {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid;
    flex-shrink: 0;
    display: inline-block;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
}

.pc2-swatch-label {
    font-size: 10.5px;
    color: #6b7280;
    font-weight: 500;
    letter-spacing: 0.01em;
}

/* Brand */
.pc2-brand {
    font-size: 10px;
    font-weight: 700;
    color: #b0b8c8;
    letter-spacing: 0.10em;
    text-transform: uppercase;
    line-height: 1;
    margin: 0;
}

/* Name */
.pc2-name-link { text-decoration: none; }

.pc2-name {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.42;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
    transition: color 0.18s;
    letter-spacing: -0.01em;
}
.pc2-name-link:hover .pc2-name { color: #228bcc; }

/* Rating */
.pc2-rating {
    display: flex;
    align-items: center;
    gap: 5px;
}

.pc2-stars {
    display: flex;
    gap: 1.5px;
    color: #fbbf24;
    font-size: 10.5px;
}
.pc2-star-empty { color: #e2e8f0; }

.pc2-rating-count {
    font-size: 11.5px;
    font-weight: 700;
    color: #1f2937;
}
.pc2-rating-total {
    font-size: 10.5px;
    color: #9ca3af;
}

/* Price */
.pc2-price-row {
    display: flex;
    align-items: baseline;
    gap: 7px;
    flex-wrap: wrap;
    margin-top: 3px;
}

.pc2-price {
    font-size: 16px;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.03em;
    line-height: 1;
}

.pc2-compare {
    font-size: 12px;
    color: #cbd5e1;
    text-decoration: line-through;
    font-weight: 400;
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .pc2-card, .pc2-img, .pc2-cart-panel { transition: none !important; }
}
</style>