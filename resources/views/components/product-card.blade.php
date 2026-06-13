@props(['product', 'showBadge' => true, 'badgeType' => 'auto'])

<?php
$badge = null;
$badgeClass = '';

if ($showBadge && $badgeType === 'auto') {
    if ($product->is_new_arrival)    { $badge = 'NEW';      $badgeClass = 'pc3-badge--new';  }
    elseif ($product->is_best_seller){ $badge = 'HOT';      $badgeClass = 'pc3-badge--hot';  }
    elseif ($product->is_on_sale)    { $badge = 'SALE';     $badgeClass = 'pc3-badge--sale'; }
    elseif ($product->is_featured)   { $badge = 'FEATURED'; $badgeClass = 'pc3-badge--feat'; }
} elseif ($showBadge && $badgeType !== 'auto') {
    $badge = $badgeType;
    $badgeClass = 'pc3-badge--new';
}

$discountPercent = 0;
if ($product->compare_price && $product->compare_price > $product->price) {
    $discountPercent = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
}

// Collect variant color swatches (up to 5)
$colorVariants = collect();
try {
    $colorVariants = $product->variants
        ->where('type', 'color')
        ->take(5);
} catch (\Throwable $e) {}

$brandLabel = '';
try {
    $brandLabel = $product->brand?->name ?? $product->category?->name ?? '';
} catch (\Throwable $e) {}

$hasVariants = $product->variants->count() > 0;
?>

<div class="pc3-card group">

    {{-- Image Block --}}
    <div class="pc3-img-wrap">

        <a href="{{ route('products.show', $product->slug) }}" class="pc3-img-link">
            <img src="{{ $product->thumbnail }}"
                 alt="{{ $product->name }}"
                 class="pc3-img"
                 loading="lazy"
                 width="400"
                 height="400">
            
            {{-- Secondary image hover effect (if available) --}}
            @if($product->secondary_image)
            <img src="{{ $product->secondary_image }}"
                 alt="{{ $product->name }} - alternate view"
                 class="pc3-img-hover"
                 loading="lazy"
                 width="400"
                 height="400">
            @endif
        </a>

        {{-- Badge --}}
        @if($badge)
            <span class="pc3-badge {{ $badgeClass }}">
                @if($discountPercent > 0 && $product->is_on_sale)
                    -{{ $discountPercent }}%
                @else
                    {{ $badge }}
                @endif
            </span>
        @elseif($discountPercent > 0)
            <span class="pc3-badge pc3-badge--sale">-{{ $discountPercent }}%</span>
        @endif

        {{-- Wishlist --}}
        <button class="pc3-heart" 
                aria-label="Add to wishlist"
                onclick="event.preventDefault(); toggleWishlist(this, {{ $product->id }})">
            <svg class="pc3-heart-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
        </button>

        {{-- Action Panel --}}
        <div class="pc3-actions">
            <button class="pc3-action-btn pc3-cart-btn"
                    onclick="handleProductCardAddToCart({{ $product->id }}, {{ $hasVariants ? 1 : 0 }})"
                    data-product-id="{{ $product->id }}"
                    aria-label="Add to cart">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span>Add to Cart</span>
            </button>
            
            <button class="pc3-action-btn pc3-qv-btn"
                    onclick="window.productVariantManager?.openQuickView({{ $product->id }})"
                    aria-label="Quick view">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
        </div>
    </div>

    {{-- Info Block --}}
    <div class="pc3-info">

        {{-- Color swatches --}}
        @if($colorVariants->isNotEmpty())
        <div class="pc3-swatches">
            @foreach($colorVariants as $variant)
                <span class="pc3-swatch {{ $variant->value === '#ffffff' || strtolower($variant->value) === 'white' ? 'pc3-swatch--white' : '' }}"
                      title="{{ $variant->value }}"
                      style="background: {{ $variant->value }};"></span>
            @endforeach
            @if($product->variants->where('type', 'color')->count() > 5)
                <span class="pc3-swatch-more">+{{ $product->variants->where('type', 'color')->count() - 5 }}</span>
            @endif
        </div>
        @endif

        {{-- Brand --}}
        @if($brandLabel)
        <p class="pc3-brand">{{ $brandLabel }}</p>
        @endif

        {{-- Name --}}
        <a href="{{ route('products.show', $product->slug) }}" class="pc3-name-link">
            <h3 class="pc3-name">{{ $product->name }}</h3>
        </a>

        {{-- Rating --}}
        @if($product->review_count > 0)
        <div class="pc3-rating">
            <div class="pc3-stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($product->average_rating))
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @elseif($i - 0.5 <= $product->average_rating)
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none"><defs><linearGradient id="half{{ $product->id }}"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#e2e8f0"/></linearGradient></defs><polygon fill="url(#half{{ $product->id }})" points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @else
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="#e2e8f0" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endif
                @endfor
            </div>
            <span class="pc3-rating-text">{{ number_format($product->average_rating, 1) }} <span class="pc3-rating-count">({{ $product->review_count }})</span></span>
        </div>
        @endif

        {{-- Price --}}
        <div class="pc3-price-row">
            <span class="pc3-price">৳{{ number_format($product->price, 0) }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="pc3-compare">৳{{ number_format($product->compare_price, 0) }}</span>
                <span class="pc3-save">Save ৳{{ number_format($product->compare_price - $product->price, 0) }}</span>
            @endif
        </div>
    </div>
</div>

<style>
/* ====================================================
   PRODUCT CARD v3 — Modern Minimal
   Design: Clean, spacious, refined interactions
   Primary: #0f172a (slate-900)
   Accent:  #3b82f6 (blue-500)
==================================================== */

.pc3-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    border: 1px solid #f1f5f9;
    transition: box-shadow 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.pc3-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12),
                0 0 0 1px rgba(0, 0, 0, 0.04);
}

/* ── Image ── */
.pc3-img-wrap {
    position: relative;
    overflow: hidden;
    background: #f8fafc;
    border-radius: 16px;
    aspect-ratio: 1 / 1;
    flex-shrink: 0;
    margin: 8px;
}

.pc3-img-link {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
}

.pc3-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                opacity 0.4s ease;
    will-change: transform;
}

.pc3-img-hover {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    opacity: 0;
    transition: opacity 0.5s ease, transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.pc3-card:hover .pc3-img {
    transform: scale(1.03);
}

.pc3-card:hover .pc3-img-hover {
    opacity: 1;
    transform: scale(1.03);
}

/* ── Badge ── */
.pc3-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 3;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.06em;
    padding: 5px 12px;
    border-radius: 6px;
    line-height: 1;
    text-transform: uppercase;
}

.pc3-badge--new  { background: #ecfdf5; color: #059669; }
.pc3-badge--hot  { background: #fef2f2; color: #dc2626; }
.pc3-badge--sale { background: #dc2626; color: #fff; }
.pc3-badge--feat { background: #eff6ff; color: #2563eb; }

/* ── Heart ── */
.pc3-heart {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 3;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #94a3b8;
    transition: all 0.2s ease;
    opacity: 0;
    transform: scale(0.8);
}

.pc3-card:hover .pc3-heart {
    opacity: 1;
    transform: scale(1);
}

.pc3-heart:hover {
    background: #fff;
    color: #ef4444;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.pc3-heart.active {
    color: #ef4444;
    background: #fef2f2;
}

.pc3-heart-icon {
    transition: transform 0.2s ease;
}

.pc3-heart:hover .pc3-heart-icon {
    transform: scale(1.15);
}

/* ── Actions Panel ── */
.pc3-actions {
    position: absolute;
    bottom: 12px;
    left: 12px;
    right: 12px;
    display: flex;
    gap: 8px;
    opacity: 0;
    transform: translateY(8px);
    transition: all 0.35s cubic-bezier(0.34, 1.2, 0.64, 1);
    z-index: 4;
}

.pc3-card:hover .pc3-actions {
    opacity: 1;
    transform: translateY(0);
}

.pc3-action-btn {
    height: 36px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.pc3-cart-btn {
    flex: 1;
    background: #0f172a;
    color: #fff;
    padding: 0 14px;
}

.pc3-cart-btn:hover {
    background: #1e293b;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.pc3-cart-btn:active {
    transform: translateY(0);
}

.pc3-qv-btn {
    width: 36px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px);
    color: #475569;
}

.pc3-qv-btn:hover {
    background: #fff;
    color: #0f172a;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* ── Info ── */
.pc3-info {
    padding: 12px 14px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
}

/* Swatches */
.pc3-swatches {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 2px;
}

.pc3-swatch {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid transparent;
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1);
    flex-shrink: 0;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.pc3-swatch:hover {
    transform: scale(1.2);
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.2), 0 0 0 2px #fff, 0 0 0 3px #cbd5e1;
}

.pc3-swatch--white {
    box-shadow: inset 0 0 0 1px #cbd5e1;
    border: 2px solid #fff;
}

.pc3-swatch-more {
    font-size: 10px;
    color: #94a3b8;
    font-weight: 500;
    margin-left: 2px;
}

/* Brand */
.pc3-brand {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    line-height: 1;
    margin: 0;
}

/* Name */
.pc3-name-link {
    text-decoration: none;
    display: block;
}

.pc3-name {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
    transition: color 0.2s ease;
}

.pc3-name-link:hover .pc3-name {
    color: #3b82f6;
}

/* Rating */
.pc3-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 2px;
}

.pc3-stars {
    display: flex;
    gap: 2px;
    color: #f59e0b;
}

.pc3-rating-text {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
}

.pc3-rating-count {
    font-weight: 400;
    color: #94a3b8;
}

/* Price */
.pc3-price-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 4px;
}

.pc3-price {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    line-height: 1;
}

.pc3-compare {
    font-size: 13px;
    color: #94a3b8;
    text-decoration: line-through;
    font-weight: 500;
}

.pc3-save {
    font-size: 11px;
    font-weight: 600;
    color: #059669;
    background: #ecfdf5;
    padding: 2px 6px;
    border-radius: 4px;
    line-height: 1;
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .pc3-card, .pc3-img, .pc3-img-hover, .pc3-actions, .pc3-heart {
        transition: none !important;
    }
    .pc3-card:hover {
        transform: none;
    }
}

/* Mobile touch optimization */
@media (hover: none) {
    .pc3-heart {
        opacity: 1;
        transform: scale(1);
    }
    .pc3-actions {
        opacity: 1;
        transform: translateY(0);
        position: relative;
        bottom: auto;
        left: auto;
        right: auto;
        margin-top: 8px;
    }
    .pc3-img-wrap {
        margin-bottom: 0;
    }
}
</style>

<script>
function toggleWishlist(btn, productId) {
    btn.classList.toggle('active');
    // Add your wishlist AJAX logic here
    const isActive = btn.classList.contains('active');
    btn.setAttribute('aria-label', isActive ? 'Remove from wishlist' : 'Add to wishlist');
    
    // Optional: Show toast notification
    // showToast(isActive ? 'Added to wishlist' : 'Removed from wishlist');
}

// Handle variant-aware add to cart
function handleProductCardAddToCart(productId, hasVariants) {
    if (hasVariants) {
        window.productVariantManager?.openQuickView(productId);
    } else {
        // Direct add to cart
        addToCart(productId, 1);
    }
}
</script>