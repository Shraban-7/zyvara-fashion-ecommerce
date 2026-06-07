<section class="bg-gradient-to-b from-white to-gray-50 py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="text-center mb-8 md:mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Shop by Category</h2>
            <p class="text-sm md:text-base text-gray-500">Discover our wide range of fashion collections</p>
        </div>

        <div class="grid grid-cols-4 md:grid-cols-8 gap-3 md:gap-4 lg:gap-6">
            @foreach($allMenuCategories as $category)
            <a href="{{ route('products.index') }}?categories={{ $category->slug }}" class="group">
                <div class="flex flex-col items-center text-center gap-2">
                    <div class="relative w-14 h-14 sm:w-18 sm:h-18 md:w-20 md:h-20 lg:w-24 lg:h-24 xl:w-28 xl:h-28">
                        <div class="absolute inset-0 rounded-full overflow-hidden group-hover:scale-110 transition-all duration-300 ring-2 ring-white shadow-md group-hover:shadow-xl group-hover:ring-primary/50">
                            <img src="{{ set_image($category->image) }}" alt="{{ $category->name }}"
                                class="w-full h-full object-cover">
                        </div>
                    </div>

                    <span class="text-[10px] sm:text-xs lg:text-sm font-semibold text-gray-800 group-hover:text-primary transition-colors tracking-wide line-clamp-2 px-0.5 standard-prose">
                        {{ $category->name }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>