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
            <i class="fas fa-heart pc3-heart-icon"></i>
        </button>
    </div>

    {{-- Info Block --}}
    <div class="pc3-info">
        {{-- Name --}}
        <a href="{{ route('products.show', $product->slug) }}" class="pc3-name-link">
            <h3 class="pc3-name truncate">{{ $product->name }}</h3>
        </a>

        {{-- Rating --}}
        @if($product->review_count > 0)
        <div class="pc3-rating">
            <div class="pc3-stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($product->average_rating))
                        <i class="fas fa-star"></i>
                    @elseif($i - 0.5 <= $product->average_rating)
                        <i class="fas fa-star-half-alt"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>
            <span class="pc3-rating-text">{{ number_format($product->average_rating, 1) }} <span class="pc3-rating-count">({{ $product->review_count }})</span></span>
        </div>
        @endif

        {{-- Price --}}
        <div class="pc3-price-row">
            <span class="pc3-price">{{ money($product->price) }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="pc3-compare">{{ money($product->compare_price) }}</span>
            @endif
        </div>

        {{-- Add to Cart Button (opens Quick View) --}}
        <button class="pc3-cart-btn"
                onclick="window.productVariantManager?.openQuickView({{ $product->id }})"
                aria-label="Add to cart">
            <i class="fas fa-shopping-cart"></i>
            Add to Cart
        </button>
    </div>
</div>

<style>
/* ====================================================
   PRODUCT CARD — Minimal Luxury
   Clean, spacious, single Add to Cart action
=================================================== */

.pc3-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: var(--color-surface-elevated);
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    border: 1px solid var(--color-border);
    transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.pc3-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px -8px rgba(26, 26, 26, 0.12);
}

/* ── Image ── */
.pc3-img-wrap {
    position: relative;
    overflow: hidden;
    background: var(--color-surface-muted);
    border-radius: 14px;
    aspect-ratio: 1 / 1;
    flex-shrink: 0;
    margin: 6px;
}

.pc3-img-link {
    display: block;
    width: 100%;
    height: 100%;
}

.pc3-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.5s ease;
}

.pc3-card:hover .pc3-img {
    transform: scale(1.04);
}

/* ── Badge ── */
.pc3-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 3;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 4px 10px;
    border-radius: 6px;
    line-height: 1;
    text-transform: uppercase;
}

.pc3-badge--new  { background: var(--color-accent-50); color: var(--color-accent-700); }
.pc3-badge--hot  { background: var(--color-accent-100); color: var(--color-accent-700); }
.pc3-badge--sale { background: var(--color-danger); color: #fff; }
.pc3-badge--feat { background: var(--color-primary); color: var(--color-surface-elevated); }

/* ── Heart ── */
.pc3-heart {
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 3;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(250, 248, 245, 0.95);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--color-secondary);
    transition: all 0.2s ease;
    opacity: 0;
    transform: scale(0.8);
}

.pc3-card:hover .pc3-heart {
    opacity: 1;
    transform: scale(1);
}

.pc3-heart:hover {
    background: var(--color-surface-elevated);
    color: var(--color-danger);
    box-shadow: 0 2px 8px rgba(26, 26, 26, 0.1);
}

.pc3-heart.active {
    color: var(--color-danger);
    background: var(--color-danger-50);
}

.pc3-heart-icon {
    transition: transform 0.2s ease;
}

.pc3-heart:hover .pc3-heart-icon {
    transform: scale(1.15);
}

/* ── Info ── */
.pc3-info {
    padding: 12px 14px 14px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex: 1;
}

/* Brand */
.pc3-brand {
    font-size: 11px;
    font-weight: 600;
    color: var(--color-secondary);
    letter-spacing: 0.03em;
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
    color: var(--color-primary);
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
    transition: color 0.2s ease;
}

.pc3-name-link:hover .pc3-name {
    color: var(--color-accent-700);
}

/* Rating */
.pc3-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 1px;
}

.pc3-stars {
    display: flex;
    gap: 2px;
    color: var(--color-accent);
}

.pc3-rating-text {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-secondary-700);
}

.pc3-rating-count {
    font-weight: 400;
    color: var(--color-secondary);
}

/* Price */
.pc3-price-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 2px;
}

.pc3-price {
    font-size: 15px;
    font-weight: 800;
    color: var(--color-primary);
    letter-spacing: -0.02em;
}

.pc3-compare {
    font-size: 12px;
    color: var(--color-secondary);
    text-decoration: line-through;
    font-weight: 500;
}

/* ── Add to Cart Button ── */
.pc3-cart-btn {
    width: 100%;
    margin-top: 10px;
    height: 40px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    background: var(--color-primary);
    color: var(--color-surface-elevated);
    transition: all 0.2s ease;
}

.pc3-cart-btn:hover {
    background: var(--color-accent);
    color: var(--color-primary);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(201, 168, 124, 0.3);
}

.pc3-cart-btn:active {
    transform: translateY(0);
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .pc3-card, .pc3-img, .pc3-heart, .pc3-cart-btn {
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
}
</style>

<script>
function toggleWishlist(btn, productId) {
    btn.classList.toggle('active');
    const isActive = btn.classList.contains('active');
    btn.setAttribute('aria-label', isActive ? 'Remove from wishlist' : 'Add to wishlist');
}
</script>