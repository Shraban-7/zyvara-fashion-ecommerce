{{-- Quick Category Menu --}}
<section class="bg-gradient-to-b from-white to-gray-50 py-6 md:py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">Shop by Category</h2>
            <p class="text-xs md:text-sm text-gray-600">Discover our wide range of fashion collections</p>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-3 md:gap-4">
            @foreach($allMenuCategories as $category)
            <a href="{{ route('products.index') }}?category={{ $category->slug }}" class="group">
                <div class="flex flex-col items-center gap-2 p-2">
                    {{-- Image Container --}}
                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24">
                        <div class="absolute inset-0 rounded-full overflow-hidden group-hover:scale-105 transition-all duration-300 ring-2 ring-gray-200 group-hover:ring-brand-blue/50">
                            <img
                                src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/images/default.png') }}"
                                alt="{{ $category->name }}"
                                class="w-full h-full object-cover">
                        </div>
                    </div>

                    {{-- Category Name --}}
                    <span class="text-[10px] sm:text-xs font-medium text-gray-700 group-hover:text-brand-blue transition-colors text-center leading-tight">
                        {{ $category->name }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>

        {{-- View All Categories Button --}}
        <div class="text-center mt-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white border-2 border-brand-blue text-brand-blue font-semibold rounded-lg hover:bg-brand-blue hover:text-white transition-all duration-300 text-sm">
                <span>View All Products</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</section>