{{-- Best Selling Section --}}
<section class="py-6 md:py-10 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <div>
                <h2 class="text-lg md:text-2xl font-bold text-brand-black">Best Selling</h2>
                <p class="text-xs md:text-sm text-brand-gray">Customer favorites</p>
            </div>
            <a href="#" class="text-brand-blue text-sm font-semibold flex items-center gap-1 tap-effect">
                View All
                <i class="fas fa-chevron-right text-sm"></i>
            </a>
        </div>

        {{-- Products Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">

            {{-- Product Card 1 --}}
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=400&h=500&fit=crop" alt="Slim Fit Jeans" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">HOT</span>
                    <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                        <i class="fas fa-heart text-red-500"></i>
                    </button>
                </div>
                <div class="p-3 md:p-4">
                    <h3 class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2">Slim Fit Stretch Jeans</h3>
                    <div class="flex items-center gap-1 mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                        </div>
                        <span class="text-[10px] md:text-xs text-gray-500">(234)</span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-brand-blue font-bold text-base md:text-lg">৳1,450</span>
                        <span class="text-gray-400 text-xs line-through">৳1,800</span>
                    </div>
                    <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                </div>
            </div>

            {{-- Product Card 2 --}}
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=400&h=500&fit=crop" alt="Casual Shirt" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">HOT</span>
                    <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                        <i class="far fa-heart text-gray-600"></i>
                    </button>
                </div>
                <div class="p-3 md:p-4">
                    <h3 class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2">Printed Casual Shirt</h3>
                    <div class="flex items-center gap-1 mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="far fa-star text-xs md:text-sm text-gray-300"></i>
                        </div>
                        <span class="text-[10px] md:text-xs text-gray-500">(189)</span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-brand-blue font-bold text-base md:text-lg">৳950</span>
                        <span class="text-gray-400 text-xs line-through">৳1,200</span>
                    </div>
                    <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                </div>
            </div>

            {{-- Product Card 3 --}}
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1609505848912-b7c3b8b4beda?w=400&h=500&fit=crop" alt="Chino Pants" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                    <span class="absolute top-2 left-2 bg-orange-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">-20%</span>
                    <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                        <i class="far fa-heart text-gray-600"></i>
                    </button>
                </div>
                <div class="p-3 md:p-4">
                    <h3 class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2">Comfortable Chino Pants</h3>
                    <div class="flex items-center gap-1 mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="far fa-star text-xs md:text-sm text-gray-300"></i>
                        </div>
                        <span class="text-[10px] md:text-xs text-gray-500">(156)</span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-brand-blue font-bold text-base md:text-lg">৳1,280</span>
                        <span class="text-gray-400 text-xs line-through">৳1,600</span>
                    </div>
                    <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                </div>
            </div>

            {{-- Product Card 4 --}}
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop" alt="Hoodie" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">HOT</span>
                    <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                        <i class="far fa-heart text-gray-600"></i>
                    </button>
                </div>
                <div class="p-3 md:p-4">
                    <h3 class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2">Premium Cotton Hoodie</h3>
                    <div class="flex items-center gap-1 mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                            <i class="fas fa-star text-xs md:text-sm"></i>
                        </div>
                        <span class="text-[10px] md:text-xs text-gray-500">(312)</span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-brand-blue font-bold text-base md:text-lg">৳1,850</span>
                        <span class="text-gray-400 text-xs line-through">৳2,200</span>
                    </div>
                    <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                </div>
            </div>

        </div>
    </div>
</section>