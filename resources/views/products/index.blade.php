@extends('layouts.app')

@section('title', $activeCategory ? $activeCategory->name . ' Products' : 'Products')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-surface-elevated border-b border-primary-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ url('/') }}" class="text-secondary hover:text-primary transition-colors duration-200">Home</a>
            <i class="fas fa-chevron-right text-[10px] text-secondary-300"></i>
            <a href="{{ route('products.index') }}" class="text-secondary hover:text-primary transition-colors duration-200">Products</a>
            @if($activeCategory)
                <i class="fas fa-chevron-right text-[10px] text-secondary-300"></i>
                <span class="text-primary font-medium">{{ $activeCategory->name }}</span>
            @else
                <i class="fas fa-chevron-right text-[10px] text-secondary-300"></i>
                <span class="text-primary font-medium">All Products</span>
            @endif
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Mobile Filter Toggle --}}
        <div class="lg:hidden flex items-center justify-between mb-2">
            <h1 class="text-xl font-bold text-primary">
                {{ $activeCategory ? $activeCategory->name : 'All Products' }}
            </h1>
            <button onclick="toggleMobileFilter()" class="flex items-center gap-2 bg-surface-elevated border border-primary-100 rounded-xl px-4 py-2.5 text-sm font-medium text-primary tap-effect shadow-sm">
                <i class="fas fa-sliders-h"></i>
                Filters
            </button>
        </div>

        {{-- Sidebar Filters --}}
        <aside id="filterSidebar" class="hidden lg:block w-full lg:w-72 flex-shrink-0">
            <div class="bg-surface-elevated rounded-2xl shadow-sm border border-primary-100 p-5 sticky top-28">

                {{-- Filter Header --}}
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-bold text-primary">Filters</h2>
                    <button onclick="clearAllFilters()" class="text-sm text-primary hover:text-secondary font-medium transition-colors duration-200">Clear All</button>
                </div>

                {{-- ============================================================
                     CATEGORY FILTER — TREE TYPE DROPDOWN
                     ============================================================ --}}
                <div class="border-b border-primary-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('categoryFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-primary mb-3 hover:text-secondary transition-colors duration-200">
                        Category
                        <i class="fas fa-chevron-down text-xs text-secondary-300 transition-transform duration-200" id="categoryFilterIcon"></i>
                    </button>

                    <div id="categoryFilter" class="space-y-1 max-h-80 overflow-y-auto pr-1 qv-scroll">
                        @foreach($categories as $category)
                            {{-- LEVEL 1 --}}
                            <div class="category-tree-item">
                                <div class="flex items-center justify-between group">
                                    <a href="{{ route('products.index', ['categorySlug' => $category->slug]) }}"
                                       class="flex-1 text-sm font-medium py-2 px-2 rounded-lg transition-all duration-200 {{ $activeCategorySlug == $category->slug ? 'bg-primary text-surface-elevated shadow-sm' : 'text-secondary hover:bg-primary-50 hover:text-primary' }}">
                                        {{ $category->name }}
                                        <span class="text-xs opacity-70 ml-1">({{ $categoryCounts[$category->id] ?? 0 }})</span>
                                    </a>
                                    @if($category->children->count() > 0)
                                        <button type="button"
                                                onclick="toggleCategoryTree('cat-{{ $category->id }}', this)"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-primary-50 text-secondary-300 hover:text-primary transition-all duration-200 ml-1">
                                            <i class="fas fa-chevron-right text-[10px] transition-transform duration-200" id="cat-icon-{{ $category->id }}"></i>
                                        </button>
                                    @endif
                                </div>

                                {{-- LEVEL 2 --}}
                                @if($category->children->count() > 0)
                                    <div id="cat-{{ $category->id }}" class="hidden ml-3 mt-1 space-y-1 border-l-2 border-primary-100 pl-3">
                                        @foreach($category->children as $child)
                                            <div class="category-tree-item">
                                                <div class="flex items-center justify-between group">
                                                    <a href="{{ route('products.index', ['categorySlug' => $child->slug]) }}"
                                                       class="flex-1 text-sm py-1.5 px-2 rounded-lg transition-all duration-200 {{ $activeCategorySlug == $child->slug ? 'bg-primary/10 text-primary font-medium' : 'text-secondary-400 hover:bg-primary-50 hover:text-primary' }}">
                                                        {{ $child->name }}
                                                        <span class="text-xs opacity-60 ml-1">({{ $categoryCounts[$child->id] ?? 0 }})</span>
                                                    </a>
                                                    @if($child->children->count() > 0)
                                                        <button type="button"
                                                                onclick="toggleCategoryTree('subcat-{{ $child->id }}', this)"
                                                                class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-primary-50 text-secondary-300 hover:text-primary transition-all duration-200 ml-1">
                                                            <i class="fas fa-chevron-right text-[10px] transition-transform duration-200" id="cat-icon-{{ $child->id }}"></i>
                                                        </button>
                                                    @endif
                                                </div>

                                                {{-- LEVEL 3 --}}
                                                @if($child->children->count() > 0)
                                                    <div id="subcat-{{ $child->id }}" class="hidden ml-3 mt-1 space-y-1 border-l-2 border-primary-50 pl-3">
                                                        @foreach($child->children as $subChild)
                                                            <a href="{{ route('products.index', ['categorySlug' => $subChild->slug]) }}"
                                                               class="block text-sm py-1.5 px-2 rounded-lg transition-all duration-200 {{ $activeCategorySlug == $subChild->slug ? 'bg-primary/10 text-primary font-medium' : 'text-secondary-300 hover:bg-primary-50 hover:text-primary' }}">
                                                                {{ $subChild->name }}
                                                                <span class="text-xs opacity-50 ml-1">({{ $categoryCounts[$subChild->id] ?? 0 }})</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     BRAND FILTER
                     ============================================================ --}}
                <div class="border-b border-primary-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('brandFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-primary mb-3 hover:text-secondary transition-colors duration-200">
                        Brand
                        <i class="fas fa-chevron-down text-xs text-secondary-300 transition-transform duration-200" id="brandFilterIcon"></i>
                    </button>

                    <div id="brandFilter" class="space-y-2 max-h-64 overflow-y-auto qv-scroll">
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
                                class="w-4 h-4 rounded border-secondary-200 text-primary focus:ring-primary/30 focus:ring-offset-0">
                            <span class="text-sm text-secondary group-hover:text-primary transition-colors duration-200">
                                {{ $brand->name }}
                            </span>
                            <span class="ml-auto text-xs text-secondary-300">
                                ({{ $brandCounts[$brand->id] ?? 0 }})
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     PRICE RANGE FILTER
                     ============================================================ --}}
                <div class="border-b border-primary-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('priceFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-primary mb-3 hover:text-secondary transition-colors duration-200">
                        Price Range
                        <i class="fas fa-chevron-down text-xs text-secondary-300 transition-transform duration-200" id="priceFilterIcon"></i>
                    </button>
                    <div id="priceFilter" class="space-y-3">

                        {{-- Custom min/max inputs --}}
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <label class="text-xs text-secondary-400 mb-1 block font-medium">Min</label>
                                <input type="number"
                                    id="minPriceInput"
                                    name="min_price"
                                    value="{{ request('min_price') }}"
                                    placeholder="৳0"
                                    class="w-full bg-light border border-primary-100 rounded-lg py-2 px-3 text-sm text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                            </div>
                            <span class="text-secondary-300 mt-5">-</span>
                            <div class="flex-1">
                                <label class="text-xs text-secondary-400 mb-1 block font-medium">Max</label>
                                <input type="number"
                                    id="maxPriceInput"
                                    name="max_price"
                                    value="{{ request('max_price') }}"
                                    placeholder="৳10000"
                                    class="w-full bg-light border border-primary-100 rounded-lg py-2 px-3 text-sm text-primary placeholder-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                            </div>
                        </div>

                        {{-- Apply price button --}}
                        <button onclick="applyPriceFilter()" class="w-full bg-primary text-surface-elevated text-sm font-semibold py-2.5 rounded-lg hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-sm hover:shadow-md">
                            Apply Price
                        </button>

                        {{-- Quick range presets --}}
                        <div class="space-y-1.5 pt-1">
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
                                    class="price-preset w-4 h-4 border-secondary-200 text-primary focus:ring-primary/30 focus:ring-offset-0"
                                    data-min="{{ $range['min'] }}"
                                    data-max="{{ $range['max'] }}"
                                    {{ $isActive ? 'checked' : '' }}
                                    onchange="applyPricePreset(this)">
                                <span class="text-sm text-secondary group-hover:text-primary transition-colors duration-200">{{ $range['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ============================================================
                     SIZE FILTER
                     ============================================================ --}}
                <div class="border-b border-primary-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('sizeFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-primary mb-3 hover:text-secondary transition-colors duration-200">
                        Size
                        <i class="fas fa-chevron-down text-xs text-secondary-300 transition-transform duration-200" id="sizeFilterIcon"></i>
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
                            class="size-color-btn px-4 py-2 border {{ in_array((string)$size->id, array_map('strval', $selectedSizes)) ? 'border-primary bg-primary text-surface-elevated shadow-sm' : 'border-primary-100 text-secondary hover:border-primary hover:text-primary' }} rounded-lg text-sm font-medium transition-all duration-200">
                            {{ $size->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     COLOR FILTER
                     ============================================================ --}}
                <div class="border-b border-primary-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('colorFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-primary mb-3 hover:text-secondary transition-colors duration-200">
                        Color
                        <i class="fas fa-chevron-down text-xs text-secondary-300 transition-transform duration-200" id="colorFilterIcon"></i>
                    </button>
                    <div id="colorFilter" class="flex flex-wrap gap-2.5">
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
                            class="size-color-btn w-9 h-9 rounded-full border-2 {{ $isColorSelected ? 'border-primary ring-2 ring-primary/30 scale-110' : 'border-primary-100 hover:border-primary' }} transition-all duration-200 relative flex items-center justify-center"
                            style="background-color: {{ $color->hex_code ?? '#CCCCCC' }}">
                            @if($isColorSelected)
                            <i class="fas fa-check text-xs" style="color: {{ in_array(strtolower($color->code ?? ''), ['white','cream','yellow','beige','lightpink','offwhite','ivory']) ? '#1c1c1e' : '#fff' }}"></i>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     RATING FILTER
                     ============================================================ --}}
                <div class="pb-2">
                    <button onclick="toggleFilterSection('ratingFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-primary mb-3 hover:text-secondary transition-colors duration-200">
                        Rating
                        <i class="fas fa-chevron-down text-xs text-secondary-300 transition-transform duration-200" id="ratingFilterIcon"></i>
                    </button>
                    <div id="ratingFilter" class="space-y-2.5">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio"
                                name="min_rating"
                                value="{{ $rating }}"
                                {{ request('min_rating') == $rating ? 'checked' : '' }}
                                onchange="applyFilters()"
                                class="w-4 h-4 border-secondary-200 text-primary focus:ring-primary/30 focus:ring-offset-0">
                            <div class="flex text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-sm {{ $i <= $rating ? '' : 'text-primary-100' }}"></i>
                                @endfor
                            </div>
                            <span class="text-xs text-secondary-300 font-medium">& up</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Apply Filter Button (Mobile) --}}
                <button onclick="toggleMobileFilter()" class="lg:hidden w-full bg-primary text-surface-elevated py-3 rounded-xl font-semibold text-sm mt-4 tap-effect shadow-lg shadow-primary/20">
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
                    <h1 class="text-2xl font-bold text-primary">
                        @if($activeCategory)
                            {{ $activeCategory->name }} Products
                        @elseif(request('search'))
                            Search Results for &ldquo;{{ request('search') }}&rdquo;
                        @else
                            All Products
                        @endif
                    </h1>
                    <p class="text-sm text-secondary mt-0.5">{{ $totalProducts }} products found</p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Sort Dropdown --}}
                    <div class="relative">
                        <select onchange="window.location.href=this.value" class="appearance-none bg-surface-elevated border border-primary-100 rounded-xl py-2.5 pl-4 pr-10 text-sm font-medium text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary cursor-pointer transition-all duration-200 shadow-sm">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}"    {{ request('sort','featured') == 'featured'     ? 'selected' : '' }}>Sort by: Featured</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"  {{ request('sort') == 'price_asc'   ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc'  ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"     {{ request('sort') == 'newest'      ? 'selected' : '' }}>Newest First</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'best_selling']) }}" {{ request('sort') == 'best_selling'? 'selected' : '' }}>Best Selling</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'top_rated']) }}"  {{ request('sort') == 'top_rated'   ? 'selected' : '' }}>Top Rated</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-secondary-300 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            {{-- Mobile Sort --}}
            <div class="lg:hidden flex items-center justify-between mb-4">
                <p class="text-sm text-secondary">Showing {{ $products->count() }} products</p>
                <select onchange="window.location.href=this.value" class="appearance-none bg-surface-elevated border border-primary-100 rounded-lg py-2 pl-3 pr-8 text-sm font-medium text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 shadow-sm">
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
                $hasFilters = request()->hasAny(['brands','sizes','colors','min_price','max_price','min_rating']) || $activeCategorySlug;
                $selectedBrands = request('brands', []);
                if (!is_array($selectedBrands)) $selectedBrands = array_filter(explode(',', $selectedBrands));

                $selectedSizes = request('sizes', []);
                if (!is_array($selectedSizes)) $selectedSizes = array_filter(explode(',', $selectedSizes));

                $selectedColors = request('colors', []);
                if (!is_array($selectedColors)) $selectedColors = array_filter(explode(',', $selectedColors));
            @endphp

            @if($hasFilters)
            <div class="flex flex-wrap items-center gap-2 mb-5">
                <span class="text-sm text-secondary font-medium">Active:</span>

                {{-- Category chip (if active from URL) --}}
                @if($activeCategory)
                    <span class="inline-flex items-center gap-1.5 bg-primary text-surface-elevated px-3 py-1.5 rounded-full text-sm font-medium shadow-sm">
                        <i class="fas fa-folder text-xs opacity-70"></i>
                        {{ $activeCategory->name }}
                        <button onclick="removeCategoryFilter()" class="hover:text-secondary-200 ml-0.5 transition-colors">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif

                {{-- Brand chips --}}
                @foreach($selectedBrands as $brandSlug)
                    @php $brand = $brands->firstWhere('slug', trim($brandSlug)); @endphp
                    @if($brand)
                    <span class="inline-flex items-center gap-1.5 bg-primary text-surface-elevated px-3 py-1.5 rounded-full text-sm font-medium shadow-sm">
                        {{ $brand->name }}
                        <button onclick="removeFilter('brands', '{{ trim($brandSlug) }}')" class="hover:text-secondary-200 ml-0.5 transition-colors">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                    @endif
                @endforeach

                {{-- Size chips --}}
                @foreach($selectedSizes as $sizeId)
                    @php $size = $sizes->firstWhere('id', $sizeId); @endphp
                    @if($size)
                    <span class="inline-flex items-center gap-1.5 bg-primary text-surface-elevated px-3 py-1.5 rounded-full text-sm font-medium shadow-sm">
                        Size: {{ $size->name }}
                        <button onclick="removeFilter('sizes', '{{ $sizeId }}')" class="hover:text-secondary-200 ml-0.5 transition-colors">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                    @endif
                @endforeach

                {{-- Color chips --}}
                @foreach($selectedColors as $colorId)
                    @php $color = $colors->firstWhere('id', $colorId); @endphp
                    @if($color)
                    <span class="inline-flex items-center gap-1.5 bg-primary text-surface-elevated px-3 py-1.5 rounded-full text-sm font-medium shadow-sm">
                        <span class="w-3 h-3 rounded-full border border-surface-elevated/30 flex-shrink-0" style="background-color: {{ $color->hex_code ?? '#CCCCCC' }}"></span>
                        {{ $color->name }}
                        <button onclick="removeFilter('colors', '{{ $colorId }}')" class="hover:text-secondary-200 ml-0.5 transition-colors">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                    @endif
                @endforeach

                {{-- Price chip --}}
                @if(request('min_price') || request('max_price'))
                <span class="inline-flex items-center gap-1.5 bg-primary text-surface-elevated px-3 py-1.5 rounded-full text-sm font-medium shadow-sm">
                    Price:
                    @if(request('min_price') && request('max_price'))
                        ৳{{ number_format(request('min_price')) }} – ৳{{ number_format(request('max_price')) }}
                    @elseif(request('min_price'))
                        Above ৳{{ number_format(request('min_price')) }}
                    @else
                        Below ৳{{ number_format(request('max_price')) }}
                    @endif
                    <button onclick="removeFilter('price')" class="hover:text-secondary-200 ml-0.5 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </span>
                @endif

                {{-- Rating chip --}}
                @if(request('min_rating'))
                <span class="inline-flex items-center gap-1.5 bg-primary text-surface-elevated px-3 py-1.5 rounded-full text-sm font-medium shadow-sm">
                    <span class="flex text-warning">
                        @for($i = 1; $i <= (int)request('min_rating'); $i++)
                            <i class="fas fa-star text-xs"></i>
                        @endfor
                    </span>
                    & up
                    <button onclick="removeFilter('min_rating')" class="hover:text-secondary-200 ml-0.5 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </span>
                @endif

                <button onclick="clearAllFilters()" class="text-sm text-danger hover:text-danger-600 font-medium ml-2 transition-colors">
                    Clear All
                </button>
            </div>
            @endif

            {{-- ================================================================
                 PRODUCTS GRID
                 ================================================================ --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="w-20 h-20 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <i class="fas fa-box-open text-3xl text-secondary-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-primary mb-2">No products found</h3>
                        <p class="text-secondary mb-6">Try adjusting your filters or search criteria.</p>
                        <button onclick="clearAllFilters()" class="px-6 py-2.5 bg-primary text-surface-elevated rounded-xl text-sm font-semibold hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-lg shadow-primary/20">
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
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-secondary-300 bg-light border border-primary-100 rounded-xl cursor-not-allowed">
                        <i class="fas fa-arrow-left text-xs"></i> Previous
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-primary bg-surface-elevated border border-primary-100 rounded-xl hover:bg-primary hover:text-surface-elevated hover:border-primary transition-all duration-200 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i> Previous
                    </a>
                @endif

                <span class="text-sm text-secondary font-medium">
                    Page {{ $products->currentPage() }}
                </span>

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-primary bg-surface-elevated border border-primary-100 rounded-xl hover:bg-primary hover:text-surface-elevated hover:border-primary transition-all duration-200 shadow-sm">
                        Next <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-secondary-300 bg-light border border-primary-100 rounded-xl cursor-not-allowed">
                        Next <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                @endif

            </div>
            @endif

        </div>
    </div>
</div>

{{-- Mobile Filter Overlay --}}
<div id="mobileFilterOverlay" class="fixed inset-0 bg-primary/50 z-[60] hidden lg:hidden backdrop-blur-sm" onclick="toggleMobileFilter()"></div>

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

    // Preserve category from URL path if present
    const pathParts = url.pathname.split('/');
    const lastPart = pathParts[pathParts.length - 1];
    const isCategorySlug = lastPart && lastPart !== 'products';
    const basePath = isCategorySlug ? '/products' : url.pathname;

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

    window.location.href = `${basePath}?${params.toString()}`;
}

// ============================================================
// CATEGORY TREE TOGGLE
// ============================================================
function toggleCategoryTree(elementId, btn) {
    const element = document.getElementById(elementId);
    const icon = document.getElementById('cat-icon-' + elementId.replace('cat-', '').replace('subcat-', ''));
    
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
        if (icon) icon.classList.add('rotate-90');
        if (btn) btn.classList.add('bg-primary-50', 'text-primary');
    } else {
        element.classList.add('hidden');
        if (icon) icon.classList.remove('rotate-90');
        if (btn) btn.classList.remove('bg-primary-50', 'text-primary');
    }
}

// ============================================================
// REMOVE CATEGORY FILTER (go back to /products)
// ============================================================
function removeCategoryFilter() {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.searchParams);

    // Remove category from path by going to base /products
    // Keep all other query params
    params.delete('page');
    window.location.href = `/products?${params.toString()}`;
}

// ============================================================
// PRICE FILTER HELPERS
// ============================================================
function applyPriceFilter() {
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

    if (name === 'colors[]') {
        if (cb.checked) {
            btn.classList.add('border-primary', 'ring-2', 'ring-primary/30', 'scale-110');
            btn.classList.remove('border-primary-100');
            if (!btn.querySelector('.fa-check')) {
                const checkIcon = document.createElement('i');
                checkIcon.className = 'fas fa-check text-xs';
                const isLight = ['white','cream','yellow','beige','lightpink','offwhite','ivory'].some(c => 
                    btn.title.toLowerCase().includes(c)
                );
                checkIcon.style.color = isLight ? '#1c1c1e' : '#fff';
                btn.appendChild(checkIcon);
            }
        } else {
            btn.classList.remove('border-primary', 'ring-2', 'ring-primary/30', 'scale-110');
            btn.classList.add('border-primary-100');
            const checkIcon = btn.querySelector('.fa-check');
            if (checkIcon) checkIcon.remove();
        }
    } else {
        if (cb.checked) {
            btn.classList.remove('border-primary-100', 'text-secondary', 'hover:border-primary', 'hover:text-primary');
            btn.classList.add('border-primary', 'bg-primary', 'text-surface-elevated', 'shadow-sm');
        } else {
            btn.classList.remove('border-primary', 'bg-primary', 'text-surface-elevated', 'shadow-sm');
            btn.classList.add('border-primary-100', 'text-secondary', 'hover:border-primary', 'hover:text-primary');
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

    if (['brands', 'sizes', 'colors'].includes(filterType)) {
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

    params.delete('page');
    window.location.href = `${url.pathname}?${params.toString()}`;
}

// ============================================================
// CLEAR ALL FILTERS
// ============================================================
function clearAllFilters() {
    const url = new URL(window.location.href);
    const params = new URLSearchParams();
    
    // Preserve sort & search only
    ['sort', 'search'].forEach(k => {
        if (url.searchParams.get(k)) params.set(k, url.searchParams.get(k));
    });
    
    // Always go to base /products path (remove category slug)
    const qs = params.toString();
    window.location.href = qs ? `/products?${qs}` : '/products';
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
        sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-[70]', 'w-80', 'max-w-[85vw]', 'overflow-y-auto', 'bg-surface-elevated', 'shadow-2xl');
        sidebar.classList.remove('lg:block');
        document.body.style.overflow = 'hidden';
    } else {
        sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-[70]', 'w-80', 'max-w-[85vw]', 'overflow-y-auto', 'bg-surface-elevated', 'shadow-2xl');
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
// ON PAGE LOAD — INITIALIZE HIDDEN CHECKBOXES FOR PRE-SELECTED
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.size-color-btn').forEach(btn => {
        const name  = btn.dataset.filterType;
        const value = btn.dataset.filterValue;
        if (!name || !value) return;

        const isActive = name === 'colors[]' 
            ? btn.classList.contains('border-primary') && btn.classList.contains('ring-2')
            : btn.classList.contains('border-primary') && btn.classList.contains('bg-primary');
        
        if (isActive) {
            const cb = getOrCreateHiddenCheckbox(name, value);
            cb.checked = true;
        }
    });

    // Auto-expand category tree if active category is nested
    @if($activeCategory)
        @if($activeCategory->parent_id)
            // Try to expand parent trees
            const parentIds = [];
            @php
                $parent = $activeCategory->parent;
                while($parent) {
                    echo "parentIds.push('cat-{$parent->id}');\n";
                    $parent = $parent->parent;
                }
            @endphp
            
            parentIds.forEach(id => {
                const el = document.getElementById(id);
                if (el && el.classList.contains('hidden')) {
                    el.classList.remove('hidden');
                    const iconId = 'cat-icon-' + id.replace('cat-', '').replace('subcat-', '');
                    const icon = document.getElementById(iconId);
                    if (icon) icon.classList.add('rotate-90');
                }
            });
        @endif
    @endif
});
</script>
@endpush