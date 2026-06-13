@if($onSaleProducts->isNotEmpty())
<section class="home-section">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">On Sale</h2>
                <p class="section-sub">Limited-time deals — grab them fast</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'on-sale']) }}" class="section-link">
                View All <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        {{-- Sale countdown banner --}}
        <div class="sale-strip">
            <i class="fas fa-bolt text-yellow-300 text-sm"></i>
            <span class="text-xs font-bold text-white tracking-wide">FLASH SALE — Save up to
                <span class="text-yellow-300">50% OFF</span> on selected items
            </span>
        </div>

        <div class="products-grid mt-4">
            @foreach($onSaleProducts as $product)
                <x-product-card :product="$product" badgeType="SALE" />
            @endforeach
        </div>
    </div>
</section>

<style>
    .sale-strip {
        display: flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(90deg, #ef4444, #dc2626);
        border-radius: 10px;
        padding: 10px 16px;
        box-shadow: 0 3px 12px rgba(220,38,38,0.25);
    }
</style>
@endif