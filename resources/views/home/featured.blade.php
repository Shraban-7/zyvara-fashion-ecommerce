@if($featuredProducts->isNotEmpty())
<section class="home-section-alt">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">Featured Products</h2>
                <p class="section-sub">Handpicked highlights of the season</p>
            </div>
            <a href="{{ route('products.index', ['filter' => 'featured']) }}" class="section-link">
                View All <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="products-grid">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" badgeType="FEATURED" />
            @endforeach
        </div>
    </div>
</section>
@endif