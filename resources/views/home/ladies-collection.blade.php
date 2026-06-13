@if($womensProducts->isNotEmpty())
<section class="home-section-alt">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">Ladies Collection</h2>
                <p class="section-sub">Elegant styles for every woman</p>
            </div>
            <a href="{{ route('products.index', ['category' => 'women']) }}" class="section-link">
                View All <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="products-scroll">
            @foreach($womensProducts as $product)
                <div class="products-scroll-item">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif