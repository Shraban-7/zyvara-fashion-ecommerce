@props(['product', 'showBadge' => true, 'badgeType' => 'auto'])

<?php
$badge = null;
$badgeColor = 'green';

if ($showBadge && $badgeType === 'auto') {
    if ($product->is_new_arrival) {
        $badge = 'NEW';
        $badgeColor = 'green';
    } elseif ($product->is_best_seller) {
        $badge = 'HOT';
        $badgeColor = 'red';
    } elseif ($product->is_on_sale) {
        $badge = 'SALE';
        $badgeColor = 'orange';
    } elseif ($product->is_featured) {
        $badge = 'FEATURED';
        $badgeColor = 'blue';
    }
} elseif ($showBadge && $badgeType !== 'auto') {
    $badge = $badgeType;
    $badgeColor = 'green';
}

$discountPercent = 0;
if ($product->compare_price && $product->compare_price > $product->price) {
    $discountPercent = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
}
?>

<div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
    <div class="relative">
        <a href="{{ route('products.show', $product->slug) }}">
            <img
                src="{{ $product->image ? storage_url($product->image) : asset('assets/images/default.png') }}"
                alt="{{ $product->name }}"
                class="w-full h-40 sm:h-48 md:h-56 object-cover">
        </a>

        @if($badge)
        <span class="absolute top-2 left-2 bg-{{ $badgeColor }}-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">
            {{ $badge }}
        </span>
        @endif

        {{-- Wishlist Button --}}
        <button
            class="wishlist-btn absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect"
            data-product-id="{{ $product->id }}">
            <i class="far fa-heart text-gray-600"></i>
        </button>
    </div>

    <div class="p-3 md:p-4">
        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">
                {{ $product->name }}
            </h3>
        </a>

        @if($product->review_count > 0)
        <div class="flex items-center gap-1 mb-2">
            <div class="flex text-yellow-400">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <=floor($product->average_rating))
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
            <span class="text-brand-blue font-bold text-base md:text-lg">৳{{ number_format($product->price, 0) }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
            <span class="text-gray-400 text-xs line-through">৳{{ number_format($product->compare_price, 0) }}</span>
            @if($discountPercent > 0)
            <span class="text-green-600 text-[10px] md:text-xs font-semibold">-{{ $discountPercent }}%</span>
            @endif
            @endif
        </div>

        <button
            class="add-to-cart-btn w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect"
            onclick="handleProductCardAddToCart({{ $product->id }}, {{ $product->variants->count() }})"
            data-product-id="{{ $product->id }}">
            <i class="fas fa-shopping-bag mr-1"></i>
            Add to Cart
        </button>
    </div>
</div>