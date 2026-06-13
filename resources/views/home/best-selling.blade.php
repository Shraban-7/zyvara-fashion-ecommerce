@if($bestSelling->isNotEmpty())
<section class="home-section-alt">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">Best Selling</h2>
                <p class="section-sub">Top picks loved by customers</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'best-sellers']) }}" class="section-link">
                View All <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="products-grid">
            @foreach($bestSelling as $product)
                <x-product-card :product="$product" badgeType="HOT" />
            @endforeach
        </div>
    </div>
</section>
@endif