@if($ourBrands->count())
<section class="relative bg-white py-12 md:py-16 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 via-white to-blue-50/30"></div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-brand-blue/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10 md:mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Our Brands</h2>
            <p class="text-sm md:text-base text-gray-600 max-w-2xl mx-auto">
                Handpicked premium brands delivering exceptional quality and timeless style
            </p>
        </div>
        <div class="grid grid-cols-3 md:grid-cols-5 gap-3 sm:gap-4 md:gap-5">
            @foreach ($ourBrands as $brand)
            <a href="{{ route('products.index') }}?brands={{ $brand->slug }}" class="group block">
                <div
                    class="relative bg-white rounded-xl overflow-hidden transition-all duration-500 hover:scale-105">
                    {{-- Card Shadow & Border --}}
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-gray-100/50 to-white rounded-xl border border-gray-200 group-hover:border-brand-blue/30 transition-all duration-500 group-hover:shadow-xl group-hover:shadow-brand-blue/10">
                    </div>

                    {{-- Content Container --}}
                    <div class="relative">
                        {{-- Accent Bar --}}
                        <div
                            class="h-1 bg-gradient-to-r from-brand-blue via-blue-500 to-brand-blue transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-700 ease-out">
                        </div>

                        {{-- Logo Container --}}
                        <div class="relative p-2.5 sm:p-3 md:p-4">
                            <div
                                class="aspect-square flex items-center justify-center bg-gradient-to-br from-gray-50/80 to-white rounded-lg group-hover:from-blue-50/50 group-hover:to-white transition-all duration-500">
                                <div class="w-full h-full p-2 sm:p-2.5 flex items-center justify-center">
                                    <img src="{{ set_image($brand->logo) }}" alt="{{ $brand->name }}"
                                        class="max-w-full max-h-full object-contain transform group-hover:scale-110 transition-transform duration-500 ease-out">
                                </div>
                            </div>

                            {{-- Hover Shine Effect --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transform -translate-x-full group-hover:translate-x-full transition-all duration-1000 pointer-events-none">
                            </div>
                        </div>

                        {{-- Brand Name Section --}}
                        <div class="relative px-2.5 pb-2.5 sm:px-3 sm:pb-3">
                            <div
                                class="bg-gradient-to-r from-gray-50 to-gray-100/50 group-hover:from-brand-blue group-hover:to-blue-600 rounded-lg p-1.5 sm:p-2 transition-all duration-500">
                                <p
                                    class="text-center text-[11px] sm:text-xs font-bold text-gray-800 group-hover:text-white transition-colors duration-500 truncate">
                                    {{ $brand->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif