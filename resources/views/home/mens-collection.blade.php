@if($mensProducts->isNotEmpty())
<section class="home-section">
    <div class="home-wrap">
        <div class="section-head">
            <div>
                <h2 class="section-title">Men's Collection</h2>
                <p class="section-sub">Stylish picks for the modern man</p>
            </div>
            <a href="{{ route('products.index', ['category' => 'men']) }}" class="section-link">
                View All <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="products-scroll">
            @foreach($mensProducts as $product)
                <div class="products-scroll-item">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif