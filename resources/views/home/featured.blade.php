@if($featuredProducts->isNotEmpty())
    <section class="py-6 md:py-10 bg-white">
        <div class="max-w-7xl mx-auto px-2 sm:px-4">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <div>
                    <h2 class="text-lg md:text-2xl font-bold text-black">Features</h2>
                    <p class="text-xs md:text-sm text-secondary">Customer favorites</p>
                </div>
                <a href="{{ route('products.index', ['filter' => 'featured']) }}"
                    class="text-primary text-sm font-semibold flex items-center gap-1 tap-effect">
                    View All
                    <i class="fas fa-chevron-right text-sm"></i>
                </a>
    </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-5">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" badgeType="FEATURED" />
                @endforeach
            </div>
        </div>
    </section>
@endif