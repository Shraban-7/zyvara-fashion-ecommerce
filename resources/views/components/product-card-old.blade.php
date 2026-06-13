@props(['product', 'showBadge' => true, 'badgeType' => 'auto'])

<?php
$badge = null;
$badgeStyle = '';

if ($showBadge && $badgeType === 'auto') {
    if ($product->is_new_arrival) {
        $badge = 'NEW';
        $badgeStyle = 'background: linear-gradient(135deg,#22c55e,#16a34a);';
    } elseif ($product->is_best_seller) {
        $badge = 'HOT 🔥';
        $badgeStyle = 'background: linear-gradient(135deg,#ef4444,#dc2626);';
    } elseif ($product->is_on_sale) {
        $badge = 'SALE';
        $badgeStyle = 'background: linear-gradient(135deg,#f97316,#ea580c);';
    } elseif ($product->is_featured) {
        $badge = 'FEATURED';
        $badgeStyle = 'background: linear-gradient(135deg,#228bcc,#1b6fa3);';
    }
} elseif ($showBadge && $badgeType !== 'auto') {
    $badge = $badgeType;
    $badgeStyle = 'background: linear-gradient(135deg,#22c55e,#16a34a);';
}

$discountPercent = 0;
if ($product->compare_price && $product->compare_price > $product->price) {
    $discountPercent = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
}
?>

<div class="pc-card group">

    {{-- Image Area --}}
    <div class="pc-image-wrap">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $product->thumbnail }}"
                 alt="{{ $product->name }}"
                 class="pc-img"
                 loading="lazy">
        </a>

        {{-- Subtle hover scrim --}}
        <div class="pc-scrim"></div>

        {{-- Badge --}}
        @if($badge)
            <span class="pc-badge" style="{{ $badgeStyle }}">{{ $badge }}</span>
        @endif

        {{-- Discount pill --}}
        @if($discountPercent > 0)
            <span class="pc-discount-pill">-{{ $discountPercent }}%</span>
        @endif
    </div>

    {{-- Info Area --}}
    <div class="pc-body">

        {{-- Name --}}
        <a href="{{ route('products.show', $product->slug) }}" class="block mb-1">
            <h3 class="pc-name">{{ $product->name }}</h3>
        </a>

        {{-- Rating --}}
        @if($product->review_count > 0)
            <div class="flex items-center gap-1 mb-1.5">
                <div class="flex gap-px">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->average_rating))
                            <i class="fas fa-star text-[10px] text-yellow-400"></i>
                        @elseif($i - 0.5 <= $product->average_rating)
                            <i class="fas fa-star-half-alt text-[10px] text-yellow-400"></i>
                        @else
                            <i class="far fa-star text-[10px] text-yellow-300"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-[10px] text-gray-400">({{ $product->review_count }})</span>
            </div>
        @endif

        {{-- Price --}}
        <div class="flex items-baseline gap-1.5 flex-wrap mb-2.5">
            <span class="pc-price">৳{{ number_format($product->price, 0) }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="pc-compare-price">৳{{ number_format($product->compare_price, 0) }}</span>
            @endif
        </div>

        {{-- Actions: single cart + single eye --}}
        <div class="flex gap-1.5 mt-auto">
            <button
                class="pc-cart-btn flex-1"
                onclick="handleProductCardAddToCart({{ $product->id }}, {{ $product->variants->count() }})"
                data-product-id="{{ $product->id }}"
                aria-label="Add to cart">
                <i class="fas fa-shopping-cart text-xs flex-shrink-0"></i>
                <span>Add to Cart</span>
            </button>
            <button
                onclick="window.productVariantManager.openQuickView({{ $product->id }})"
                class="pc-eye-btn"
                aria-label="Quick view">
                <i class="far fa-eye text-xs"></i>
            </button>
        </div>
    </div>
</div>

<style>
    /* ================================================
       PRODUCT CARD — Premium Redesign
       Primary: #228bcc
    ================================================ */

    .pc-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1.5px solid #f0f0f0;
        transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
        position: relative;
    }

    .pc-card:hover {
        border-color: #a3d3ef;
        box-shadow: 0 8px 32px rgba(34, 139, 204, 0.14), 0 2px 8px rgba(0,0,0,0.06);
        transform: translateY(-3px);
    }

    /* ---------- Image ---------- */
    .pc-image-wrap {
        position: relative;
        overflow: hidden;
        background: #f8f9fa;
        aspect-ratio: 3 / 4;
        flex-shrink: 0;
    }

    .pc-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.55s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        will-change: transform;
    }

    .pc-card:hover .pc-img { transform: scale(1.06); }

    /* ---------- Hover Scrim ---------- */
    .pc-scrim {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.12) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }

    .pc-card:hover .pc-scrim { opacity: 1; }

    /* ---------- Badge ---------- */
    .pc-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.04em;
        padding: 3px 8px;
        border-radius: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        line-height: 1.4;
    }

    /* ---------- Discount Pill ---------- */
    .pc-discount-pill {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 7px;
        border-radius: 20px;
        box-shadow: 0 2px 8px rgba(220,38,38,0.35);
        line-height: 1.4;
    }

    /* ---------- Body ---------- */
    .pc-body {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 10px;
    }

    /* ---------- Name ---------- */
    .pc-name {
        font-size: 12px;
        font-weight: 600;
        color: #1f2937;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.18s;
    }

    .pc-card:hover .pc-name { color: #228bcc; }

    /* ---------- Price ---------- */
    .pc-price {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        line-height: 1;
    }

    .pc-compare-price {
        font-size: 11px;
        color: #9ca3af;
        text-decoration: line-through;
        font-weight: 500;
    }

    /* ---------- Cart Button ---------- */
    .pc-cart-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 32px;
        background: linear-gradient(135deg, #228bcc 0%, #1b6fa3 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: filter 0.18s, transform 0.15s, box-shadow 0.18s;
        box-shadow: 0 3px 10px rgba(34,139,204,0.3);
        white-space: nowrap;
        letter-spacing: 0.01em;
    }

    .pc-cart-btn:hover {
        filter: brightness(1.1);
        box-shadow: 0 5px 16px rgba(34,139,204,0.4);
        transform: translateY(-1px);
    }

    .pc-cart-btn:active { transform: scale(0.97); }

    /* ---------- Eye Button ---------- */
    .pc-eye-btn {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        color: #6b7280;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.18s, color 0.18s, transform 0.15s;
    }

    .pc-eye-btn:hover {
        background: #e8f4fb;
        color: #228bcc;
        transform: translateY(-1px);
    }

    .pc-eye-btn:active { transform: scale(0.95); }
</style>

@isset($oldProductCard)
    {{-- Legacy card preserved for backward compatibility --}}
    <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
        <div class="relative">
            <a href="{{ route('products.show', $product->slug) }}">
                <img src="{{ $product->image ? storage_url($product->image) : asset('assets/images/default.png') }}"
                    alt="{{ $product->name }}" class="w-full h-40 sm:h-48 md:h-56 object-cover">
            </a>
            @if($badge)
                <span class="absolute top-2 left-2 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full"
                      style="{{ $badgeStyle }}">{{ $badge }}</span>
            @endif
        </div>
        <div class="p-3 md:p-4">
            <a href="{{ route('products.show', $product->slug) }}">
                <h3 class="text-sm md:text-base font-medium text-black mb-1 line-clamp-2 hover:text-primary transition">
                    {{ $product->name }}
                </h3>
            </a>
            @if($product->review_count > 0)
                <div class="flex items-center gap-1 mb-2">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($product->average_rating))
                                <i class="fas fa-star text-xs md:text-sm"></i>
                            @elseif($i - 0.5 <= $product->average_rating)
                                <i class="fas fa-star-half-alt text-xs md:text-sm"></i>
                            @else
                                <i class="far fa-star text-xs md:text-sm"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-[10px] md:text-xs text-gray-500">({{ $product->review_count }})</span>
                </div>
            @endif
            <div class="flex items-center gap-2 mb-3">
                <span class="text-primary font-bold text-base md:text-lg">৳{{ number_format($product->price, 0) }}</span>
                @if($product->compare_price && $product->compare_price > $product->price)
                    <span class="text-gray-400 text-xs line-through">৳{{ number_format($product->compare_price, 0) }}</span>
                    @if($discountPercent > 0)
                        <span class="text-green-600 text-[10px] md:text-xs font-semibold">-{{ $discountPercent }}%</span>
                    @endif
                @endif
            </div>
            <button class="add-to-cart-btn w-full bg-primary text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect"
                onclick="handleProductCardAddToCart({{ $product->id }}, {{ $product->variants->count() }})"
                data-product-id="{{ $product->id }}">
                <i class="fas fa-shopping-bag mr-1"></i> Add to Cart
            </button>
        </div>
    </div>
@endisset