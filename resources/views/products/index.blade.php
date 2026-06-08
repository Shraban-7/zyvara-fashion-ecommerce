@extends('layouts.app')

@section('title', 'Products')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ url('/') }}" class="text-gray-500 hover:text-primary transition">Home</a>
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <span class="text-gray-900 font-medium">Products</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Mobile Filter Toggle --}}
        <div class="lg:hidden flex items-center justify-between mb-2">
            <h1 class="text-xl font-bold text-black">All Products</h1>
            <button onclick="toggleMobileFilter()" class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 tap-effect">
                <i class="fas fa-sliders-h"></i>
                Filters
            </button>
        </div>

        {{-- Sidebar Filters --}}
        <aside id="filterSidebar" class="hidden lg:block w-full lg:w-72 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-28">

                {{-- Filter Header --}}
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-bold text-black">Filters</h2>
                    <button onclick="clearAllFilters()" class="text-sm text-primary hover:underline font-medium">Clear All</button>
                </div>

                {{-- ============================================================
                     CATEGORY FILTER — 3 LEVEL TREE
                     ============================================================ --}}
                @php
                    $selectedCategories = request('categories', []);
                    if (!is_array($selectedCategories)) {
                        $selectedCategories = array_filter(explode(',', $selectedCategories));
                    }
                    $selectedCategories = array_map('trim', $selectedCategories);
                @endphp

                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('categoryFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Category
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="categoryFilterIcon"></i>
                    </button>

                    <div id="categoryFilter" class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @foreach($categories as $category)

                            {{-- LEVEL 1 --}}
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox"
                                    class="cat-checkbox w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                                    data-role="parent"
                                    data-id="cat-{{ $category->id }}"
                                    name="categories[]"
                                    value="{{ $category->slug }}"
                                    {{ in_array($category->slug, $selectedCategories) ? 'checked' : '' }}
                                    onchange="handleTree(this)">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900 font-medium">
                                    {{ $category->name }}
                                </span>
                                <span class="ml-auto text-xs text-gray-400 flex-shrink-0">
                                    ({{ $categoryCounts[$category->id] ?? 0 }})
                                </span>
                            </label>

                            @foreach($category->children as $child)

                                {{-- LEVEL 2 --}}
                                <label class="flex items-center gap-3 cursor-pointer group ml-3">
                                    <input type="checkbox"
                                        class="cat-checkbox w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                                        data-role="child"
                                        data-parent="cat-{{ $category->id }}"
                                        data-id="sub-{{ $child->id }}"
                                        name="categories[]"
                                        value="{{ $child->slug }}"
                                        {{ in_array($child->slug, $selectedCategories) ? 'checked' : '' }}
                                        onchange="handleTree(this)">
                                    <span class="text-sm text-gray-500 group-hover:text-gray-900">
                                        {{ $child->name }}
                                    </span>
                                    <span class="ml-auto text-xs text-gray-400 flex-shrink-0">
                                        ({{ $categoryCounts[$child->id] ?? 0 }})
                                    </span>
                                </label>

                                @foreach($child->children ?? [] as $subChild)

                                    {{-- LEVEL 3 --}}
                                    <label class="flex items-center gap-3 cursor-pointer group ml-6">
                                        <input type="checkbox"
                                            class="cat-checkbox w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                                            data-role="leaf"
                                            data-parent="sub-{{ $child->id }}"
                                            data-id="leaf-{{ $subChild->id }}"
                                            name="categories[]"
                                            value="{{ $subChild->slug }}"
                                            {{ in_array($subChild->slug, $selectedCategories) ? 'checked' : '' }}
                                            onchange="handleTree(this)">
                                        <span class="text-sm text-gray-400 group-hover:text-gray-900 truncate">
                                            {{ $subChild->name }}
                                        </span>
                                        <span class="ml-auto text-xs text-gray-400 flex-shrink-0">
                                            ({{ $categoryCounts[$subChild->id] ?? 0 }})
                                        </span>
                                    </label>

                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     BRAND FILTER
                     ============================================================ --}}
                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('brandFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Brand
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="brandFilterIcon"></i>
                    </button>

                    <div id="brandFilter" class="space-y-2 max-h-64 overflow-y-auto">
                        @php
                            $selectedBrands = request('brands', []);
                            if (!is_array($selectedBrands)) {
                                $selectedBrands = array_filter(explode(',', $selectedBrands));
                            }
                        @endphp

                        @foreach($brands as $brand)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox"
                                name="brands[]"
                                value="{{ $brand->slug }}"
                                {{ in_array($brand->slug, $selectedBrands) ? 'checked' : '' }}
                                onchange="applyFilters()"
                                class="w-4 h-4 rounded border-gray-300 text-primary">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900">
                                {{ $brand->name }}
                            </span>
                            <span class="ml-auto text-xs text-gray-400">
                                ({{ $brandCounts[$brand->id] ?? 0 }})
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     PRICE RANGE FILTER
                     ============================================================ --}}
                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('priceFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Price Range
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="priceFilterIcon"></i>
                    </button>
                    <div id="priceFilter" class="space-y-3">

                        {{-- Custom min/max inputs --}}
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <label class="text-xs text-gray-500 mb-1 block">Min</label>
                                <input type="number"
                                    id="minPriceInput"
                                    name="min_price"
                                    value="{{ request('min_price') }}"
                                    placeholder="৳0"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </div>
                            <span class="text-gray-400 mt-5">-</span>
                            <div class="flex-1">
                                <label class="text-xs text-gray-500 mb-1 block">Max</label>
                                <input type="number"
                                    id="maxPriceInput"
                                    name="max_price"
                                    value="{{ request('max_price') }}"
                                    placeholder="৳10000"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </div>
                        </div>

                        {{-- Apply price button --}}
                        <button onclick="applyPriceFilter()" class="w-full bg-primary/10 text-primary text-sm font-medium py-2 rounded-lg hover:bg-primary hover:text-white transition">
                            Apply Price
                        </button>

                        {{-- Quick range presets --}}
                        <div class="space-y-1.5">
                            @php
                                $priceRanges = [
                                    ['label' => 'Under ৳500',      'min' => '',    'max' => '500'],
                                    ['label' => '৳500 – ৳1,000',   'min' => '500', 'max' => '1000'],
                                    ['label' => '৳1,000 – ৳2,000', 'min' => '1000','max' => '2000'],
                                    ['label' => '৳2,000 – ৳5,000', 'min' => '2000','max' => '5000'],
                                    ['label' => 'Above ৳5,000',    'min' => '5000','max' => ''],
                                ];
                                $activeMin = request('min_price', '');
                                $activeMax = request('max_price', '');
                            @endphp

                            @foreach($priceRanges as $range)
                            @php
                                $isActive = ($activeMin == $range['min'] && $activeMax == $range['max']);
                            @endphp
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio"
                                    name="price_preset"
                                    class="price-preset w-4 h-4 border-gray-300 text-primary focus:ring-primary"
                                    data-min="{{ $range['min'] }}"
                                    data-max="{{ $range['max'] }}"
                                    {{ $isActive ? 'checked' : '' }}
                                    onchange="applyPricePreset(this)">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900">{{ $range['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ============================================================
                     SIZE FILTER
                     ============================================================ --}}
                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('sizeFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Size
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="sizeFilterIcon"></i>
                    </button>
                    <div id="sizeFilter" class="flex flex-wrap gap-2">
                        @php
                            $selectedSizes = request('sizes', []);
                            if (!is_array($selectedSizes)) {
                                $selectedSizes = array_filter(explode(',', $selectedSizes));
                            }
                        @endphp

                        @foreach($sizes->whereIn('code', ['xs','s','m','l','xl','xxl','3xl']) as $size)
                        <button
                            type="button"
                            data-filter-type="sizes[]"
                            data-filter-value="{{ $size->id }}"
                            onclick="toggleSizeColor(this, 'sizes[]', '{{ $size->id }}')"
                            class="size-color-btn px-4 py-2 border {{ in_array((string)$size->id, array_map('strval', $selectedSizes)) ? 'border-primary bg-primary/5 text-primary' : 'border-gray-200 text-gray-600' }} rounded-lg text-sm font-medium hover:border-primary hover:text-primary transition">
                            {{ $size->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     COLOR FILTER
                     ============================================================ --}}
                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('colorFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Color
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="colorFilterIcon"></i>
                    </button>
                    <div id="colorFilter" class="flex flex-wrap gap-2">
                        @php
                            $selectedColors = request('colors', []);
                            if (!is_array($selectedColors)) {
                                $selectedColors = array_filter(explode(',', $selectedColors));
                            }
                        @endphp

                        @foreach($colors->take(15) as $color)
                        @php
                            $isColorSelected = in_array((string)$color->id, array_map('strval', $selectedColors));
                        @endphp
                        <button
                            type="button"
                            title="{{ $color->name }}"
                            data-filter-type="colors[]"
                            data-filter-value="{{ $color->id }}"
                            onclick="toggleSizeColor(this, 'colors[]', '{{ $color->id }}')"
                            class="size-color-btn w-8 h-8 rounded-full border-2 {{ $isColorSelected ? 'border-primary ring-2 ring-primary/30' : 'border-gray-300' }} hover:border-primary transition relative flex items-center justify-center"
                            style="background-color: {{ $color->hex_code ?? '#CCCCCC' }}">
                            @if($isColorSelected)
                            <i class="fas fa-check text-xs" style="color: {{ in_array($color->code ?? '', ['white','cream','yellow']) ? '#000' : '#FFF' }}"></i>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     RATING FILTER
                     ============================================================ --}}
                <div class="pb-2">
                    <button onclick="toggleFilterSection('ratingFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Rating
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="ratingFilterIcon"></i>
                    </button>
                    <div id="ratingFilter" class="space-y-2">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio"
                                name="min_rating"
                                value="{{ $rating }}"
                                {{ request('min_rating') == $rating ? 'checked' : '' }}
                                onchange="applyFilters()"
                                class="w-4 h-4 border-gray-300 text-primary focus:ring-primary">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-sm {{ $i <= $rating ? '' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-400">& up</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Apply Filter Button (Mobile) --}}
                <button onclick="toggleMobileFilter()" class="lg:hidden w-full bg-primary text-white py-3 rounded-xl font-semibold text-sm mt-4 tap-effect">
                    Apply Filters
                </button>

            </div>
        </aside>

        {{-- ====================================================================
             PRODUCTS GRID SECTION
             ==================================================================== --}}
        <div class="flex-1">

            {{-- Products Header (Desktop) --}}
            <div class="hidden lg:flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-black">
                        @if(request('category'))
                            {{ ucfirst(request('category')) }} Products
                        @elseif(request('search'))
                            Search Results for &ldquo;{{ request('search') }}&rdquo;
                        @else
                            All Products
                        @endif
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $totalProducts }} products found</p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Sort Dropdown --}}
                    <div class="relative">
                        <select onchange="window.location.href=this.value" class="appearance-none bg-white border border-gray-200 rounded-xl py-2.5 pl-4 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary cursor-pointer">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}"    {{ request('sort','featured') == 'featured'     ? 'selected' : '' }}>Sort by: Featured</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"  {{ request('sort') == 'price_asc'   ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc'  ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"     {{ request('sort') == 'newest'      ? 'selected' : '' }}>Newest First</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'best_selling']) }}" {{ request('sort') == 'best_selling'? 'selected' : '' }}>Best Selling</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'top_rated']) }}"  {{ request('sort') == 'top_rated'   ? 'selected' : '' }}>Top Rated</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            {{-- Mobile Sort --}}
            <div class="lg:hidden flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500">Showing {{ $products->count() }} products</p>
                <select onchange="window.location.href=this.value" class="appearance-none bg-white border border-gray-200 rounded-lg py-2 pl-3 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}"    {{ request('sort','featured') == 'featured'  ? 'selected' : '' }}>Sort: Featured</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"     {{ request('sort') == 'newest'     ? 'selected' : '' }}>Newest First</option>
                </select>
            </div>

            {{-- ================================================================
                 ACTIVE FILTERS CHIPS
                 ================================================================ --}}
            @php
                $hasFilters = request()->hasAny(['categories','brands','sizes','colors','min_price','max_price','min_rating']);
                $selectedCategories = request('categories', []);
                if (!is_array($selectedCategories)) $selectedCategories = array_filter(explode(',', $selectedCategories));

                $selectedBrands = request('brands', []);
                if (!is_array($selectedBrands)) $selectedBrands = array_filter(explode(',', $selectedBrands));

                $selectedSizes = request('sizes', []);
                if (!is_array($selectedSizes)) $selectedSizes = array_filter(explode(',', $selectedSizes));

                $selectedColors = request('colors', []);
                if (!is_array($selectedColors)) $selectedColors = array_filter(explode(',', $selectedColors));
            @endphp

            @if($hasFilters)
            <div class="flex flex-wrap items-center gap-2 mb-5">
                <span class="text-sm text-gray-500 font-medium">Active:</span>

                {{-- Category chips --}}
                @foreach($selectedCategories as $catSlug)
                    @php $cat = $allCategories->firstWhere('slug', trim($catSlug)); @endphp
                    @if($cat)
                    <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-sm font-medium">
                        <i class="fas fa-tag text-xs opacity-70"></i>
                        {{ $cat->name }}
                        <button onclick="removeFilter('categories', '{{ trim($catSlug) }}')" class="hover:text-blue-700 ml-0.5">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                    @endif
                @endforeach

                {{-- Brand chips --}}
                @foreach($selectedBrands as $brandSlug)
                    @php $brand = $brands->firstWhere('slug', trim($brandSlug)); @endphp
                    @if($brand)
                    <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-sm font-medium">
                        {{ $brand->name }}
                        <button onclick="removeFilter('brands', '{{ trim($brandSlug) }}')" class="hover:text-blue-700 ml-0.5">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                    @endif
                @endforeach

                {{-- Size chips --}}
                @foreach($selectedSizes as $sizeId)
                    @php $size = $sizes->firstWhere('id', $sizeId); @endphp
                    @if($size)
                    <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-sm font-medium">
                        Size: {{ $size->name }}
                        <button onclick="removeFilter('sizes', '{{ $sizeId }}')" class="hover:text-blue-700 ml-0.5">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                    @endif
                @endforeach

                {{-- Color chips --}}
                @foreach($selectedColors as $colorId)
                    @php $color = $colors->firstWhere('id', $colorId); @endphp
                    @if($color)
                    <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-sm font-medium">
                        <span class="w-3 h-3 rounded-full border border-gray-300 flex-shrink-0" style="background-color: {{ $color->hex_code ?? '#CCCCCC' }}"></span>
                        {{ $color->name }}
                        <button onclick="removeFilter('colors', '{{ $colorId }}')" class="hover:text-blue-700 ml-0.5">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                    @endif
                @endforeach

                {{-- Price chip --}}
                @if(request('min_price') || request('max_price'))
                <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-sm font-medium">
                    Price:
                    @if(request('min_price') && request('max_price'))
                        ৳{{ number_format(request('min_price')) }} – ৳{{ number_format(request('max_price')) }}
                    @elseif(request('min_price'))
                        Above ৳{{ number_format(request('min_price')) }}
                    @else
                        Below ৳{{ number_format(request('max_price')) }}
                    @endif
                    <button onclick="removeFilter('price')" class="hover:text-blue-700 ml-0.5">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </span>
                @endif

                {{-- Rating chip --}}
                @if(request('min_rating'))
                <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-sm font-medium">
                    <span class="flex text-yellow-500">
                        @for($i = 1; $i <= (int)request('min_rating'); $i++)
                            <i class="fas fa-star text-xs"></i>
                        @endfor
                    </span>
                    & up
                    <button onclick="removeFilter('min_rating')" class="hover:text-blue-700 ml-0.5">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </span>
                @endif

                <button onclick="clearAllFilters()" class="text-sm text-red-500 hover:text-red-600 hover:underline font-medium ml-2 transition">
                    Clear All
                </button>
            </div>
            @endif

            {{-- ================================================================
                 PRODUCTS GRID
                 ================================================================ --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-full text-center py-16">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No products found</h3>
                        <p class="text-gray-500">Try adjusting your filters or search criteria.</p>
                        <button onclick="clearAllFilters()" class="mt-4 px-5 py-2 bg-primary text-white rounded-xl text-sm font-medium hover:bg-primary/90 transition">
                            Clear Filters
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- ================================================================
                 PAGINATION
                 ================================================================ --}}
            @if($products->hasPages())
            <div class="flex items-center justify-center gap-3 mt-8">

                @if($products->onFirstPage())
                    <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-xl cursor-not-allowed">
                        <i class="fas fa-arrow-left text-xs"></i> Previous
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-primary hover:text-white hover:border-primary transition-all duration-200 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i> Previous
                    </a>
                @endif

                <span class="text-sm text-gray-500">
                    Page {{ $products->currentPage() }}
                </span>

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-primary hover:text-white hover:border-primary transition-all duration-200 shadow-sm">
                        Next <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-xl cursor-not-allowed">
                        Next <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                @endif

            </div>
            @endif

        </div>
    </div>
</div>

{{-- Mobile Filter Overlay --}}
<div id="mobileFilterOverlay" class="fixed inset-0 bg-black/50 z-[60] hidden lg:hidden" onclick="toggleMobileFilter()"></div>

@endsection

@push('scripts')
<script>
// ============================================================
// HIDDEN CHECKBOXES STORE — for size/color buttons
// ============================================================
const hiddenCheckboxStore = {};

function getOrCreateHiddenCheckbox(name, value) {
    const key = `${name}::${value}`;
    if (hiddenCheckboxStore[key]) return hiddenCheckboxStore[key];

    let cb = document.querySelector(`input[type="checkbox"][name="${name}"][value="${value}"]`);
    if (!cb) {
        cb = document.createElement('input');
        cb.type = 'checkbox';
        cb.name = name;
        cb.value = value;
        cb.style.display = 'none';
        document.getElementById('filterSidebar').appendChild(cb);
    }
    hiddenCheckboxStore[key] = cb;
    return cb;
}

// ============================================================
// APPLY FILTERS — collect state → push URL → reload
// ============================================================
function applyFilters() {
    const url = new URL(window.location.href);
    const params = new URLSearchParams();

    // Preserve sort & search
    ['sort', 'search'].forEach(k => {
        if (url.searchParams.get(k)) params.set(k, url.searchParams.get(k));
    });

    // Categories (checkboxes)
    const categories = [...document.querySelectorAll('input[name="categories[]"]:checked')]
        .map(cb => cb.value.trim())
        .filter(Boolean);
    if (categories.length) params.set('categories', categories.join(','));

    // Brands
    const brands = [...document.querySelectorAll('input[name="brands[]"]:checked')]
        .map(cb => cb.value.trim())
        .filter(Boolean);
    if (brands.length) params.set('brands', brands.join(','));

    // Sizes (hidden checkboxes)
    const sizes = [...document.querySelectorAll('input[name="sizes[]"]:checked')]
        .map(cb => cb.value.trim())
        .filter(Boolean);
    if (sizes.length) params.set('sizes', sizes.join(','));

    // Colors (hidden checkboxes)
    const colors = [...document.querySelectorAll('input[name="colors[]"]:checked')]
        .map(cb => cb.value.trim())
        .filter(Boolean);
    if (colors.length) params.set('colors', colors.join(','));

    // Price
    const minPrice = document.getElementById('minPriceInput')?.value;
    const maxPrice = document.getElementById('maxPriceInput')?.value;
    if (minPrice) params.set('min_price', minPrice);
    if (maxPrice) params.set('max_price', maxPrice);

    // Rating
    const rating = document.querySelector('input[name="min_rating"]:checked')?.value;
    if (rating) params.set('min_rating', rating);

    window.location.href = `${url.pathname}?${params.toString()}`;
}

// ============================================================
// PRICE FILTER HELPERS
// ============================================================
function applyPriceFilter() {
    // Uncheck any preset radio
    document.querySelectorAll('.price-preset').forEach(r => r.checked = false);
    applyFilters();
}

function applyPricePreset(radio) {
    const min = radio.dataset.min;
    const max = radio.dataset.max;
    document.getElementById('minPriceInput').value = min;
    document.getElementById('maxPriceInput').value = max;
    applyFilters();
}

// ============================================================
// SIZE / COLOR BUTTON TOGGLE
// ============================================================
function toggleSizeColor(btn, name, value) {
    const cb = getOrCreateHiddenCheckbox(name, value);
    cb.checked = !cb.checked;

    // Update visual state of button
    if (name === 'colors[]') {
        if (cb.checked) {
            btn.classList.add('border-primary', 'ring-2', 'ring-primary/30');
            btn.classList.remove('border-gray-300');
        } else {
            btn.classList.remove('border-primary', 'ring-2', 'ring-primary/30');
            btn.classList.add('border-gray-300');
        }
    } else {
        if (cb.checked) {
            btn.classList.add('border-primary', 'bg-primary/5', 'text-primary');
            btn.classList.remove('border-gray-200', 'text-gray-600');
        } else {
            btn.classList.remove('border-primary', 'bg-primary/5', 'text-primary');
            btn.classList.add('border-gray-200', 'text-gray-600');
        }
    }

    applyFilters();
}

// ============================================================
// REMOVE A SPECIFIC FILTER CHIP
// ============================================================
function removeFilter(filterType, value = null) {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.searchParams);

    if (['categories', 'brands', 'sizes', 'colors'].includes(filterType)) {
        const current = params.get(filterType);
        if (current) {
            const updated = current.split(',').map(v => v.trim()).filter(v => v !== String(value).trim());
            updated.length ? params.set(filterType, updated.join(',')) : params.delete(filterType);
        }
    } else if (filterType === 'price') {
        params.delete('min_price');
        params.delete('max_price');
    } else {
        params.delete(filterType);
    }

    // Reset to page 1 when filters change
    params.delete('page');

    window.location.href = `${url.pathname}?${params.toString()}`;
}

// ============================================================
// CLEAR ALL FILTERS
// ============================================================
function clearAllFilters() {
    const url = new URL(window.location.href);
    const params = new URLSearchParams();
    ['sort', 'search'].forEach(k => {
        if (url.searchParams.get(k)) params.set(k, url.searchParams.get(k));
    });
    const qs = params.toString();
    window.location.href = qs ? `${url.pathname}?${qs}` : url.pathname;
}

// ============================================================
// MOBILE FILTER SIDEBAR TOGGLE
// ============================================================
function toggleMobileFilter() {
    const sidebar = document.getElementById('filterSidebar');
    const overlay = document.getElementById('mobileFilterOverlay');

    sidebar.classList.toggle('hidden');
    overlay.classList.toggle('hidden');

    const isOpen = !sidebar.classList.contains('hidden');
    if (isOpen) {
        sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-[70]', 'w-80', 'max-w-[85vw]', 'overflow-y-auto', 'bg-white', 'shadow-2xl');
        sidebar.classList.remove('lg:block');
        document.body.style.overflow = 'hidden';
    } else {
        sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-[70]', 'w-80', 'max-w-[85vw]', 'overflow-y-auto', 'bg-white', 'shadow-2xl');
        sidebar.classList.add('lg:block');
        document.body.style.overflow = '';
    }
}

// ============================================================
// COLLAPSIBLE FILTER SECTIONS
// ============================================================
function toggleFilterSection(sectionId) {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById(sectionId + 'Icon');
    section.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// ============================================================
// CATEGORY TREE — DOWNWARD + UPWARD PROPAGATION
// ============================================================
let filterTimer = null;

function handleTree(checkbox) {
    // Propagate checked state downward to all children
    setChildrenState(checkbox.dataset.id, checkbox.checked);

    // Propagate indeterminate/checked state upward to all ancestors
    updateAncestors(checkbox);

    // Debounce to avoid multiple rapid URL changes
    clearTimeout(filterTimer);
    filterTimer = setTimeout(() => applyFilters(), 250);
}

function setChildrenState(parentId, state) {
    document.querySelectorAll(`[data-parent="${parentId}"]`).forEach(cb => {
        cb.checked = state;
        cb.indeterminate = false;
        setChildrenState(cb.dataset.id, state); // recurse
    });
}

function updateAncestors(checkbox) {
    let current = checkbox;

    while (current.dataset.parent) {
        const parentSelector = `[data-id="${current.dataset.parent}"]`;
        const parent = document.querySelector(parentSelector);
        if (!parent) break;

        const siblings = document.querySelectorAll(`[data-parent="${current.dataset.parent}"]`);
        const checkedCount = [...siblings].filter(cb => cb.checked || cb.indeterminate).length;
        const allChecked  = [...siblings].every(cb => cb.checked);

        parent.checked       = allChecked;
        parent.indeterminate = !allChecked && checkedCount > 0;

        current = parent;
    }
}

// ============================================================
// ON PAGE LOAD — RESTORE TREE STATE FROM URL
// ============================================================
document.addEventListener('DOMContentLoaded', function () {

    // 1. Restore category tree indeterminate states based on pre-checked checkboxes
    document.querySelectorAll('.cat-checkbox:checked').forEach(cb => {
        updateAncestors(cb);
    });

    // 2. Initialise hidden checkboxes for size/color buttons that are pre-selected
    //    (they are rendered as buttons, not real checkboxes, so we seed the store)
    document.querySelectorAll('.size-color-btn').forEach(btn => {
        const name  = btn.dataset.filterType;
        const value = btn.dataset.filterValue;
        if (!name || !value) return;

        // If button already has active classes set by Blade, create a pre-checked hidden checkbox
        const isActive = btn.classList.contains('border-primary');
        if (isActive) {
            const cb = getOrCreateHiddenCheckbox(name, value);
            cb.checked = true;
        }
    });
});
</script>
@endpush