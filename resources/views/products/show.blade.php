@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)

@section('content')
    {{-- Breadcrumb --}}
    <div class="bg-surface-elevated border-b border-primary-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <nav class="flex items-center gap-2 text-sm overflow-x-auto hide-scrollbar whitespace-nowrap">
                <a href="{{ url('/') }}" class="text-secondary hover:text-primary transition-colors duration-200 flex-shrink-0">Home</a>
                <i class="fas fa-chevron-right text-[10px] text-secondary-300"></i>
                <a href="{{ route('products.index') }}"
                    class="text-secondary hover:text-primary transition-colors duration-200 flex-shrink-0">Products</a>
                <i class="fas fa-chevron-right text-[10px] text-secondary-300 flex-shrink-0"></i>
                @if($product->category)
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                        class="text-secondary hover:text-primary transition-colors duration-200 flex-shrink-0">{{ $product->category->name }}</a>
                    <i class="fas fa-chevron-right text-[10px] text-secondary-300 flex-shrink-0"></i>
                @endif
                @if($product->subcategory)
                    <a href="{{ route('products.index', ['category' => $product->subcategory->slug]) }}"
                        class="text-secondary hover:text-primary transition-colors duration-200 flex-shrink-0">{{ $product->subcategory->name }}</a>
                    <i class="fas fa-chevron-right text-[10px] text-secondary-300 flex-shrink-0"></i>
                @endif
                @if($product->subsubcategory)
                    <a href="{{ route('products.index', ['category' => $product->subsubcategory->slug]) }}"
                        class="text-secondary hover:text-primary transition-colors duration-200 flex-shrink-0">{{ $product->subsubcategory->name }}</a>
                    <i class="fas fa-chevron-right text-[10px] text-secondary-300 flex-shrink-0"></i>
                @endif

                <span class="text-primary font-medium flex-shrink-0">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <?php
    $brandName = $product->brand ? $product->brand->name : null;
    ?>

    <div>
        <div class="max-w-7xl mx-auto p-4">
            {{-- Product Main Section --}}
            <div class="bg-surface-elevated rounded-2xl shadow-sm border border-primary-100 p-4 md:p-8 mb-8">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">

                    {{-- Product Images --}}
                    <div class="space-y-4">
                        {{-- Main Image --}}
                        <div class="relative bg-light rounded-2xl overflow-hidden border border-primary-100">
                            <div class="flex justify-center w-full h-[400px] sm:h-[500px] lg:h-[600px]">
                                <img id="mainProductImage" src="{{ $product->thumbnail }}" alt="{{ $product->name }}"
                                    class="object-contain max-w-full max-h-full p-4">
                            </div>

                            {{-- Badges --}}
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($product->is_new_arrival)
                                    <span class="bg-accent text-surface-elevated text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm tracking-wide">NEW
                                        ARRIVAL</span>
                                @endif
                                @if($product->discount_percentage)
                                    <span
                                        class="bg-danger text-surface-elevated text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm tracking-wide">-{{ $product->discount_percentage }}%
                                        OFF</span>
                                @endif
                                @if($product->is_best_seller)
                                    <span class="bg-warning-500 text-surface-elevated text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm tracking-wide">BEST
                                        SELLER</span>
                                @endif
                            </div>

                            {{-- Zoom Button --}}
                            <button onclick="openImageModal()"
                                class="absolute bottom-4 right-4 w-10 h-10 bg-surface-elevated/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg hover:bg-surface-elevated transition-all duration-200 tap-effect border border-primary-100">
                                <i class="fas fa-search-plus text-primary text-sm"></i>
                            </button>
                        </div>

                        {{-- Thumbnail Images --}}
                        @if($product->images->count() >= 1)
                            <div class="w-full max-w-full overflow-x-auto hide-scrollbar pb-2">
                                <div class="flex gap-3 w-max">
                                    @foreach($product->images as $index => $image)
                                        <button onclick="changeMainImage(this)"
                                            class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border-2 {{ $loop->first ? 'border-primary' : 'border-transparent hover:border-primary' }} transition-all duration-200 bg-light">
                                            <img src="{{ storage_url($image->image_path) }}"
                                                alt="{{ $product->name }} - Image {{ $loop->iteration }}"
                                                class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Product Info --}}
                    <div class="space-y-4 min-w-0 overflow-hidden">
                        <div>
                            <p class="text-xs text-secondary font-bold uppercase tracking-wider mb-1.5">{{ $brandName ?? '' }}
                                @if($product->category)<span class="text-secondary-300 mx-1">•</span>{{ $product->category->name }}@endif</p>
                            <h1 class="text-xl md:text-2xl font-bold text-primary mb-2 leading-tight">{{ $product->name }}</h1>

                            <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex text-warning text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($product->average_rating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $product->average_rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star text-primary-100"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span
                                        class="text-xs sm:text-sm font-semibold text-primary">{{ number_format($product->average_rating, 1) }}</span>
                                    <span class="text-xs sm:text-sm text-secondary-300">({{ $product->review_count }}
                                        {{ Str::plural('review', $product->review_count) }})</span>
                                </div>
                                <span class="text-secondary-200 hidden sm:inline">|</span>
                                @if($product->totalStock > 0)
                                    <span class="text-xs sm:text-sm text-accent-600 font-semibold flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-accent"></span>In Stock
                                    </span>
                                @else
                                    <span class="text-xs sm:text-sm text-danger font-semibold flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-danger"></span>Out of Stock
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="flex flex-wrap items-end gap-2 mb-1.5">
                            <span id="productPrice" class="text-3xl font-black text-primary"
                                data-base-price="{{ $product->price }}">{{ money($product->price) }}</span>
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span id="productComparePrice" class="text-lg sm:text-xl text-secondary-300 line-through"
                                    data-base-compare="{{ $product->compare_price }}">{{ money($product->compare_price) }}</span>
                                <span id="productSavings"
                                    class="bg-danger-50 text-danger-600 text-xs sm:text-sm font-bold px-3 py-1.5 rounded-lg border border-danger-100">Save
                                    {{ money($product->compare_price - $product->price) }}</span>
                            @else
                                <span id="productComparePrice" class="text-lg sm:text-xl text-secondary-300 line-through hidden"
                                    data-base-compare="{{ $product->compare_price ?? 0 }}"></span>
                                <span id="productSavings"
                                    class="bg-danger-50 text-danger-600 text-xs sm:text-sm font-bold px-3 py-1.5 rounded-lg border border-danger-100 hidden"></span>
                            @endif
                        </div>

                        {{-- Color Selection --}}
                        @if($availableColors->count() > 0)
                            <div>
                                <div class="flex items-center justify-between mb-2.5">
                                    <span class="text-sm font-bold text-primary">Color: <span id="selectedColorName"
                                            class="font-normal text-secondary-400">Select a color</span></span>
                                </div>
                                <div class="flex gap-3 flex-wrap">
                                    @foreach($availableColors as $color)
                                        @php
                                            $variant = $product->variants->where('color_id', $color->id)->first();
                                            $isAvailableColor = $variant && $variant->currentStock > 0;
                                        @endphp
                                        @if($color->hex_code)
                                            <button onclick="selectColor(this, '{{ $color->name }}')" data-color-id="{{ $color->id }}"
                                                class="color-btn w-11 h-11 rounded-full {{ $isAvailableColor ? 'border-2 border-primary-100 hover:border-primary hover:scale-110 transition-all duration-200 p-1 shadow-sm' : 'border-2 border-primary-100 opacity-40 cursor-not-allowed' }}"
                                                style="background-color: {{ $color->hex_code }};" title="{{ $color->name }}">
                                            </button>
                                        @else
                                            <button onclick="selectColor(this, '{{ $color->name }}')" data-color-id="{{ $color->id }}"
                                                class="color-btn w-11 h-11 rounded-full text-xs {{ $isAvailableColor ? 'border-2 border-primary-100 hover:border-primary hover:scale-110 transition-all duration-200 p-1 shadow-sm flex items-center justify-center text-secondary font-semibold' : 'border-2 border-primary-100 text-secondary-300 opacity-40 cursor-not-allowed' }}"
                                                title="{{ $color->name }}">
                                                {{ strtoupper(substr($color->name, 0, 3)) }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Size Selection --}}
                        @if($availableSizes->count() > 0)
                            <div>
                                <div class="flex items-center justify-between mb-2.5">
                                    <span class="text-sm font-bold text-primary">Size: <span id="selectedSizeName"
                                            class="font-normal text-secondary-400">Select a size</span></span>
                                    <button class="text-xs text-primary hover:text-secondary font-semibold transition-colors duration-200">Size Guide</button>
                                </div>
                                <div class="flex flex-wrap gap-2.5">
                                    @foreach($availableSizes as $size)
                                        @php
                                            $variant = $product->variants->where('size_id', $size->id)->first();
                                            $isAvailable = $variant && $variant->currentStock > 0;
                                        @endphp
                                        <button onclick="selectSize(this, '{{ $size->name }}')" data-size-id="{{ $size->id }}"
                                            class="product-size-btn min-w-[56px] h-10 px-4 border rounded-lg text-sm font-semibold transition-all duration-200
                                                   {{ $isAvailable ? 'border-primary-100 text-secondary hover:border-primary hover:text-primary hover:bg-primary-50' : 'border-primary-100 text-secondary-200 cursor-not-allowed opacity-50' }}"
                                            {{ $isAvailable ? '' : 'disabled' }}>
                                            {{ $size->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Quantity --}}
                        <div>
                            <span class="text-sm font-bold text-primary mb-2.5 block">Quantity</span>
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="flex items-center border-2 border-primary-100 rounded-xl overflow-hidden w-fit bg-surface-elevated shadow-sm">
                                    <button onclick="updateQuantity(-1)"
                                        class="w-10 h-10 flex items-center justify-center text-secondary hover:bg-primary-50 hover:text-primary transition-colors duration-200">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number"
                                        id="productQuantity"
                                        value="1"
                                        min="1"
                                        max="{{ $product->variants->count() ? 1 : $product->currentStock }}"
                                        class="w-12 h-10 text-center text-sm font-bold text-primary border-x border-primary-100 focus:outline-none bg-transparent">
                                    <button onclick="updateQuantity(1)"
                                        class="w-10 h-10 flex items-center justify-center text-secondary hover:bg-primary-50 hover:text-primary transition-colors duration-200">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                @if($product->variants->count() == 0)
                                    @if($product->isLowStock())
                                        <span class="text-sm text-secondary">
                                            Only <span class="text-warning-500 font-bold">
                                                {{ $product->currentStock }} items
                                            </span> left!
                                        </span>

                                    @elseif($product->currentStock > 0)
                                        <span class="text-sm text-secondary">
                                            <span class="text-accent-600 font-bold">
                                                {{ $product->currentStock }} items
                                            </span>
                                            available
                                        </span>
                                    @endif

                                @else
                                    <span id="stockText" class="text-sm text-secondary-400">
                                      Please Select size or color
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-3 pt-2 w-full">
                            @if($product->totalStock > 0)
                                <button id="addToCartBtn"
                                    class="w-full sm:flex-1 bg-primary text-surface-elevated py-3.5 rounded-xl font-bold text-base hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 tap-effect shadow-xl shadow-primary/20 flex items-center justify-center gap-2 group">
                                    <i class="fas fa-shopping-cart text-lg group-hover:scale-110 transition-transform duration-200"></i>
                                    Add to Cart
                                </button>
                                <button id="buyNowBtn"
                                    class="w-full sm:flex-1 bg-primary-800 text-surface-elevated py-3.5 rounded-xl font-bold text-base hover:bg-primary active:bg-primary-900 transition-all duration-200 tap-effect flex items-center justify-center gap-2 group">
                                    <i class="fas fa-bolt text-lg group-hover:scale-110 transition-transform duration-200"></i>
                                    Buy Now
                                </button>
                            @else
                                <button disabled
                                    class="w-full bg-primary-100 text-secondary-300 py-3.5 rounded-xl font-bold text-base cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fas fa-times-circle text-lg"></i>
                                    Out of Stock
                                </button>
                            @endif
                        </div>

                        {{-- Delivery Info --}}
                        <div class="bg-light rounded-2xl p-5 space-y-4 border border-primary-100">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                    <i class="fas fa-truck text-primary text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-primary text-sm">Free Delivery</h4>
                                    <p class="text-xs text-secondary mt-0.5">Enter your postal code for delivery availability</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                    <i class="fas fa-undo text-primary text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-primary text-sm">7 Days Return</h4>
                                    <p class="text-xs text-secondary mt-0.5">Free returns within 7 days of delivery</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                    <i class="fas fa-shield-alt text-primary text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-primary text-sm">Secure Payment</h4>
                                    <p class="text-xs text-secondary mt-0.5">100% secure payment with bKash, Nagad, Card</p>
                                </div>
                            </div>
                        </div>

                        {{-- Share --}}
                        <div class="flex items-center gap-3 pt-2">
                            <span class="text-xs font-bold text-secondary uppercase tracking-wider">Share:</span>
                            <div class="flex gap-2">
                                <a href="#"
                                    class="w-8 h-8 bg-[#1877F2] rounded-full flex items-center justify-center text-surface-elevated hover:opacity-80 transition-opacity duration-200 shadow-sm">
                                    <i class="fab fa-facebook-f text-xs"></i>
                                </a>
                                <a href="#"
                                    class="w-8 h-8 bg-[#1DA1F2] rounded-full flex items-center justify-center text-surface-elevated hover:opacity-80 transition-opacity duration-200 shadow-sm">
                                    <i class="fab fa-twitter text-xs"></i>
                                </a>
                                <a href="#"
                                    class="w-8 h-8 bg-[#25D366] rounded-full flex items-center justify-center text-surface-elevated hover:opacity-80 transition-opacity duration-200 shadow-sm">
                                    <i class="fab fa-whatsapp text-xs"></i>
                                </a>
                                <button onclick="copyProductLink()"
                                    class="w-8 h-8 bg-light rounded-full flex items-center justify-center text-secondary hover:text-primary hover:bg-primary-50 transition-all duration-200 border border-primary-100 shadow-sm">
                                    <i class="fas fa-link text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Details Tabs --}}
            <div class="bg-surface-elevated rounded-2xl shadow-sm border border-primary-100 p-4 md:p-8 mb-8">
                <div>
                    {{-- Tab Navigation --}}
                    <div class="border-b border-primary-100">
                        <div class="flex gap-8 overflow-x-auto hide-scrollbar">
                            <button onclick="switchProductTab('description')"
                                class="product-tab pb-4 text-sm font-bold text-primary border-b-2 border-primary whitespace-nowrap transition-colors duration-200"
                                data-tab="description">
                                Description
                            </button>
                            <button onclick="switchProductTab('specifications')"
                                class="product-tab pb-4 text-sm font-medium text-secondary hover:text-primary border-b-2 border-transparent whitespace-nowrap transition-colors duration-200"
                                data-tab="specifications">
                                Specifications
                            </button>
                            <button onclick="switchProductTab('reviews')"
                                class="product-tab pb-4 text-sm font-medium text-secondary hover:text-primary border-b-2 border-transparent whitespace-nowrap transition-colors duration-200"
                                data-tab="reviews">
                                Reviews ({{ $product->review_count }})
                            </button>
                            <button onclick="switchProductTab('shipping')"
                                class="product-tab pb-4 text-sm font-medium text-secondary hover:text-primary border-b-2 border-transparent whitespace-nowrap transition-colors duration-200"
                                data-tab="shipping">
                                Shipping & Returns
                            </button>
                        </div>
                    </div>

                    {{-- Tab Content --}}
                    <div class="py-8">
                        {{-- Description Tab --}}
                        <div id="descriptionTab" class="product-tab-content">
                            <div class="prose max-w-none">
                                <h3 class="text-lg font-bold text-primary mb-4">Product Description</h3>
                                @if($product->short_description)
                                    <p class="text-secondary mb-4 leading-relaxed">
                                        {!! $product->short_description !!}
                                    </p>
                                @endif
                                @if($product->description)
                                    <div class="text-secondary leading-relaxed">
                                        {!! $product->description !!}
                                    </div>
                                @else
                                    <p class="text-secondary mb-4 leading-relaxed">
                                        Introducing our {{ $product->name }} - crafted with premium quality materials for
                                        exceptional comfort and style.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Specifications Tab --}}
                        <div id="specificationsTab" class="product-tab-content hidden">
                            <h3 class="text-lg font-bold text-primary mb-4">Product Specifications</h3>
                            <div class="bg-light rounded-2xl overflow-hidden border border-primary-100">
                                <table class="w-full">
                                    <tbody>
                                        @if($brandName)
                                            <tr class="border-b border-primary-100">
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated w-1/3">Brand
                                                </td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">{{ $brandName }}</td>
                                            </tr>
                                        @endif
                                        @if($product->sku)
                                            <tr class="border-b border-primary-100">
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated">SKU</td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">{{ $product->sku }}</td>
                                            </tr>
                                        @endif
                                        @if($product->material)
                                            <tr class="border-b border-primary-100">
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated">Material
                                                </td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">{{ $product->material }}</td>
                                            </tr>
                                        @endif
                                        @if($product->fit_type)
                                            <tr class="border-b border-primary-100">
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated">Fit Type
                                                </td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">{{ $product->fit_type->value }}</td>
                                            </tr>
                                        @endif
                                        @if($product->pattern)
                                            <tr class="border-b border-primary-100">
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated">Pattern</td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">{{ $product->pattern->value }}</td>
                                            </tr>
                                        @endif
                                        @if($product->occasion)
                                            <tr class="border-b border-primary-100">
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated">Occasion
                                                </td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">{{ $product->occasion->value }}</td>
                                            </tr>
                                        @endif
                                        @if($availableSizes->count() > 0)
                                            <tr class="border-b border-primary-100">
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated">Available
                                                    Sizes</td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">
                                                    {{ $availableSizes->pluck('name')->join(', ') }}</td>
                                            </tr>
                                        @endif
                                        @if($availableColors->count() > 0)
                                            <tr class="border-b border-primary-100">
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated">Available
                                                    Colors</td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">
                                                    {{ $availableColors->pluck('name')->join(', ') }}</td>
                                            </tr>
                                        @endif
                                        @if($product->weight)
                                            <tr>
                                                <td class="py-4 px-5 text-sm font-bold text-secondary bg-surface-elevated">Weight</td>
                                                <td class="py-4 px-5 text-sm text-primary font-medium">{{ $product->weight }} kg</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Reviews Tab --}}
                        <div id="reviewsTab" class="product-tab-content hidden">
                            <div class="grid lg:grid-cols-3 gap-8">
                                {{-- Rating Summary --}}
                                <div class="lg:col-span-1">
                                    <div class="bg-light rounded-2xl p-6 border border-primary-100">
                                        <h3 class="text-lg font-bold text-primary mb-4">Customer Reviews</h3>
                                        <div class="text-center mb-6">
                                            <div class="text-5xl font-black text-primary mb-2">
                                                {{ number_format($product->average_rating, 1) }}</div>
                                            <div class="flex justify-center text-warning mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($product->average_rating))
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $product->average_rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star text-primary-100"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-sm text-secondary">Based on {{ $product->review_count }}
                                                {{ Str::plural('review', $product->review_count) }}</p>
                                        </div>

                                        {{-- Rating Bars --}}
                                        <div class="space-y-3">
                                            @foreach([5, 4, 3, 2, 1] as $rating)
                                                @php
                                                    $count = $ratingDistribution[$rating] ?? 0;
                                                    $percentage = $product->review_count > 0 ? ($count / $product->review_count * 100) : 0;
                                                @endphp
                                                <div class="flex items-center gap-3">
                                                    <span class="text-sm text-secondary font-semibold w-8">{{ $rating }} <i class="fas fa-star text-[10px] text-warning"></i></span>
                                                    <div class="flex-1 h-2 bg-primary-100 rounded-full overflow-hidden">
                                                        <div class="h-full bg-warning rounded-full transition-all duration-500"
                                                            style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                    <span class="text-sm text-secondary-300 w-8 text-right">{{ $count }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button
                                            class="w-full mt-6 bg-primary text-surface-elevated py-3 rounded-xl font-bold text-sm hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 tap-effect shadow-lg shadow-primary/20">
                                            Write a Review
                                        </button>
                                    </div>
                                </div>

                                {{-- Reviews List --}}
                                <div class="lg:col-span-2 space-y-6">
                                    @forelse($product->approvedReviews->take(10) as $review)
                                        <div class="bg-surface-elevated border border-primary-100 rounded-2xl p-5 hover:shadow-md transition-shadow duration-200">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-surface-elevated font-bold text-sm">
                                                        {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-primary text-sm">
                                                            {{ $review->user->name ?? 'Anonymous' }}</h4>
                                                        <p class="text-xs text-secondary-300">Verified Purchase •
                                                            {{ $review->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="fas fa-star text-sm {{ $i <= $review->rating ? '' : 'text-primary-100' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-sm text-secondary mb-3 leading-relaxed">
                                                {{ $review->comment }}
                                            </p>
                                            @if($review->images && $review->images->count() > 0)
                                                <div class="flex gap-2 mb-3">
                                                    @foreach($review->images->take(4) as $image)
                                                        <img src="{{ $image->image_url }}" alt="Review image"
                                                            class="w-16 h-16 rounded-lg object-cover border border-primary-100">
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-4 text-sm">
                                                <button
                                                    class="text-secondary-300 hover:text-primary transition-colors duration-200 flex items-center gap-1 font-medium">
                                                    <i class="far fa-thumbs-up"></i> Helpful
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-12">
                                            <div class="w-16 h-16 bg-light rounded-2xl flex items-center justify-center mx-auto mb-4 border border-primary-100">
                                                <i class="fas fa-comments text-2xl text-secondary-300"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-primary mb-2">No Reviews Yet</h3>
                                            <p class="text-secondary">Be the first to review this product!</p>
                                        </div>
                                    @endforelse

                                    @if($product->approvedReviews->count() > 10)
                                        <button
                                            class="w-full border border-primary-100 text-secondary py-3 rounded-xl font-semibold text-sm hover:bg-light transition-all duration-200">
                                            Load More Reviews
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Tab --}}
                        <div id="shippingTab" class="product-tab-content hidden">
                            <div class="grid md:grid-cols-2 gap-8">
                                <div>
                                    <h3 class="text-lg font-bold text-primary mb-4">Shipping Information</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-start gap-4 p-4 bg-light rounded-xl border border-primary-100">
                                            <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                                <i class="fas fa-truck text-primary text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-primary text-sm">Standard Delivery</h4>
                                                <p class="text-sm text-secondary mt-0.5">3-5 business days • ৳60</p>
                                                <p class="text-xs text-secondary-300 mt-1">Free on orders over ৳2000</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-4 p-4 bg-light rounded-xl border border-primary-100">
                                            <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                                <i class="fas fa-shipping-fast text-primary text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-primary text-sm">Express Delivery</h4>
                                                <p class="text-sm text-secondary mt-0.5">1-2 business days • ৳120</p>
                                                <p class="text-xs text-secondary-300 mt-1">Available in Dhaka only</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-4 p-4 bg-light rounded-xl border border-primary-100">
                                            <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                                <i class="fas fa-store text-primary text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-primary text-sm">Store Pickup</h4>
                                                <p class="text-sm text-secondary mt-0.5">Same day • Free</p>
                                                <p class="text-xs text-secondary-300 mt-1">Bashundhara City, Dhaka</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-primary mb-4">Return Policy</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-start gap-4 p-4 bg-light rounded-xl border border-primary-100">
                                            <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                                <i class="fas fa-undo text-primary text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-primary text-sm">7 Days Easy Return</h4>
                                                <p class="text-sm text-secondary mt-0.5">Return within 7 days of delivery for a full
                                                    refund or exchange.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-4 p-4 bg-light rounded-xl border border-primary-100">
                                            <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                                <i class="fas fa-exchange-alt text-primary text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-primary text-sm">Free Exchange</h4>
                                                <p class="text-sm text-secondary mt-0.5">Exchange for a different size or color at
                                                    no extra cost.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-4 p-4 bg-light rounded-xl border border-primary-100">
                                            <div class="w-10 h-10 bg-surface-elevated rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                                                <i class="fas fa-info-circle text-primary text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-primary text-sm">Return Conditions</h4>
                                                <p class="text-sm text-secondary mt-0.5">Item must be unused, unwashed, and in
                                                    original packaging with tags attached.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Related Products --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-primary">You May Also Like</h2>
                    <a href="{{ route('products.index') }}"
                        class="text-primary text-sm font-bold flex items-center gap-1 tap-effect hover:text-secondary transition-colors duration-200 group">
                        View All
                        <i class="fas fa-chevron-right text-sm group-hover:translate-x-1 transition-transform duration-200"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-primary/90 backdrop-blur-sm" onclick="closeImageModal()"></div>
        <button onclick="closeImageModal()"
            class="absolute top-4 right-4 w-12 h-12 bg-surface-elevated/10 hover:bg-surface-elevated/20 rounded-full flex items-center justify-center text-surface-elevated z-10 transition-colors duration-200 backdrop-blur-sm border border-surface-elevated/10">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <img id="modalImage" src="" alt="Product Image" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const maxQuantity =  "{{ $product->stock_in ?? 0 }}";
        const productVariants = @json($product->variants);

        // Copy product link
        function copyProductLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                if (window.showSuccess) {
                    window.showSuccess('Link copied to clipboard!');
                }
            });
        }

        // Change main product image
        function changeMainImage(btn) {
            const img = btn.querySelector('img');
            const mainImage = document.getElementById('mainProductImage');

            // Update main image
            mainImage.src = img.src;

            // Update thumbnail borders
            document.querySelectorAll('.flex.gap-3 button, .w-max button').forEach(b => {
                b.classList.remove('border-primary');
                b.classList.add('border-transparent');
            });
            btn.classList.remove('border-transparent');
            btn.classList.add('border-primary');
        }

        // Open image modal
        function openImageModal() {
            const mainImage = document.getElementById('mainProductImage');
            const modalImage = document.getElementById('modalImage');
            const modal = document.getElementById('imageModal');

            modalImage.src = mainImage.src;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Close image modal
        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Update quantity
        function updateQuantity(change) {
            const input = document.getElementById('productQuantity');
            let val = parseInt(input.value || 1) + change;

            if (val < 1) val = 1;

            const max = parseInt(input.max || 999);
            if (val > max) val = max;

            input.value = val;
        }

        // Select color
        function selectColor(btn, colorName) {
            document.querySelectorAll('.color-btn').forEach(b => {
                b.classList.remove('border-primary', 'ring-2', 'ring-primary/30', 'scale-110');
                b.classList.add('border-primary-100');
            });
            btn.classList.remove('border-primary-100');
            btn.classList.add('border-primary', 'ring-2', 'ring-primary/30', 'scale-110');
            document.getElementById('selectedColorName').textContent = colorName;
            document.getElementById('selectedColorName').classList.add('text-primary', 'font-semibold');
            updateVariantPrice();
            updateVariantStock();
        }

        // Select size
        function selectSize(btn, sizeName) {
            document.querySelectorAll('.product-size-btn:not([disabled])').forEach(b => {
                b.classList.remove('border-primary', 'bg-primary', 'text-surface-elevated', 'shadow-sm');
                b.classList.add('border-primary-100', 'text-secondary');
            });
            btn.classList.remove('border-primary-100', 'text-secondary');
            btn.classList.add('border-primary', 'bg-primary', 'text-surface-elevated', 'shadow-sm');
            document.getElementById('selectedSizeName').textContent = sizeName;
            document.getElementById('selectedSizeName').classList.add('text-primary', 'font-semibold');
            updateVariantPrice();
            updateVariantStock();
        }

        // Switch product tabs
        function switchProductTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.product-tab').forEach(t => {
                t.classList.remove('text-primary', 'border-primary', 'font-bold');
                t.classList.add('text-secondary', 'border-transparent', 'font-medium');
            });
            document.querySelector(`[data-tab="${tab}"]`).classList.remove('text-secondary', 'border-transparent', 'font-medium');
            document.querySelector(`[data-tab="${tab}"]`).classList.add('text-primary', 'border-primary', 'font-bold');

            // Update tab content
            document.querySelectorAll('.product-tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById(tab + 'Tab').classList.remove('hidden');
        }

        // Close modal on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Update variant price
        function updateVariantPrice() {
            const selectedColorBtn = document.querySelector('.color-btn.border-primary');
            const selectedSizeBtn = document.querySelector('.product-size-btn.border-primary');

            if (!productVariants || productVariants.length === 0) {
                return;
            }

            // Get selected IDs
            const colorId = selectedColorBtn ? parseInt(selectedColorBtn.dataset.colorId) : null;
            const sizeId = selectedSizeBtn ? parseInt(selectedSizeBtn.dataset.sizeId) : null;

            // Get unique colors and sizes from variants
            const colors = [...new Map(productVariants.filter(v => v.color).map(v => [v.color.id, v.color])).values()];
            const sizes = [...new Map(productVariants.filter(v => v.size).map(v => [v.size.id, v.size])).values()];
            const hasColors = colors.length > 0;
            const hasSizes = sizes.length > 0;

            // Find matching variant
            const variant = productVariants.find(v => {
                const colorMatch = !hasColors || v.color_id === colorId;
                const sizeMatch = !hasSizes || v.size_id === sizeId;
                return colorMatch && sizeMatch;
            });

            const priceElement = document.getElementById('productPrice');
            const comparePriceElement = document.getElementById('productComparePrice');
            const savingsElement = document.getElementById('productSavings');
            const basePrice = parseFloat(priceElement.dataset.basePrice);
            const baseCompare = parseFloat(comparePriceElement.dataset.baseCompare || 0);

            // Use variant price if available and not zero
            if (variant && variant.price && variant.price > 0) {
                priceElement.textContent = `৳${Number(variant.price).toLocaleString()}`;

                if (variant.compare_price && variant.compare_price > variant.price) {
                    comparePriceElement.textContent = `৳${Number(variant.compare_price).toLocaleString()}`;
                    comparePriceElement.classList.remove('hidden');
                    const savings = variant.compare_price - variant.price;
                    savingsElement.textContent = `Save ৳${Number(savings).toLocaleString()}`;
                    savingsElement.classList.remove('hidden');
                } else {
                    comparePriceElement.classList.add('hidden');
                    savingsElement.classList.add('hidden');
                }
            } else {
                // Fallback to base price
                priceElement.textContent = `৳${Number(basePrice).toLocaleString()}`;
                if (baseCompare && baseCompare > basePrice) {
                    comparePriceElement.textContent = `৳${Number(baseCompare).toLocaleString()}`;
                    comparePriceElement.classList.remove('hidden');
                    const savings = baseCompare - basePrice;
                    savingsElement.textContent = `Save ৳${Number(savings).toLocaleString()}`;
                    savingsElement.classList.remove('hidden');
                } else {
                    comparePriceElement.classList.add('hidden');
                    savingsElement.classList.add('hidden');
                }
            }
        }

        function updateVariantStock() {

            const selectedColorBtn = document.querySelector('.color-btn.border-primary');
            const selectedSizeBtn = document.querySelector('.product-size-btn.border-primary');

            if (!productVariants || productVariants.length === 0) {
                return;
            }

            // Get selected IDs
            const colorId = selectedColorBtn ? parseInt(selectedColorBtn.dataset.colorId) : null;
            const sizeId = selectedSizeBtn ? parseInt(selectedSizeBtn.dataset.sizeId) : null;

            const hasColors = productVariants.some(v => v.color_id);
            const hasSizes = productVariants.some(v => v.size_id);

            const variant = productVariants.find(v => {
                const colorMatch = !hasColors || v.color_id === colorId;
                const sizeMatch = !hasSizes || v.size_id === sizeId;
                return colorMatch && sizeMatch;
            });

            const stockElement = document.getElementById('stockText');
            const quantityInput = document.getElementById('productQuantity');

            let stock = 0;

            if (variant) {
                stock = parseInt((variant.stock_in || 0) - (variant.stock_out || 0));
            }

            // No variant selected yet
            if (!variant) {
                quantityInput.disabled = true;
                quantityInput.value = 1;
                quantityInput.max = 1;

                stockElement.innerHTML = '<span class="text-secondary-400">Please select size or color</span>';
                return;
            }

            quantityInput.disabled = false;

            if (stock <= 0) {
                stockElement.innerHTML =
                    `<span class="text-danger font-bold flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-danger"></span>Out of stock</span>`;
            }
            else if (stock <= 5) {
                stockElement.innerHTML =
                    `<span class="text-warning-500 font-bold flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-warning-500"></span>Only ${stock} left!</span>`;
            }
            else {
                stockElement.innerHTML =
                    `<span class="text-accent-600 font-bold flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-accent"></span>${stock} items available</span>`;
            }

            // Update quantity max limit
            quantityInput.max = stock > 0 ? stock : 1;

            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock > 0 ? stock : 1;
            }
        }

        // Add to cart functionality
        document.addEventListener('DOMContentLoaded', function () {
            const addToCartBtn = document.getElementById('addToCartBtn');
            const buyNowBtn = document.getElementById('buyNowBtn');

            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function () {
                    const result = getSelectedVariant();

                    // Check for variant validation errors
                    if (result === 'error_color') {
                        if (window.showError) {
                            window.showError('Please select a color');
                        }
                        return;
                    }

                    if (result === 'error_size') {
                        if (window.showError) {
                            window.showError('Please select a size');
                        }
                        return;
                    }

                    if (result === 'error_not_found') {
                        if (window.showError) {
                            window.showError('Selected variant is not available');
                        }
                        return;
                    }

                    const quantity = parseInt(document.getElementById('productQuantity').value);

                    if (window.cartManager) {
                        window.cartManager.addToCart("{{$product->id}}", result, quantity).then(success => {
                            if (success && window.openCartDrawer) {
                                window.openCartDrawer();
                            }
                        });
                    }
                });
            }

            if (buyNowBtn) {
                buyNowBtn.addEventListener('click', function () {
                    const result = getSelectedVariant();

                    // Check for variant validation errors
                    if (result === 'error_color') {
                        if (window.showError) {
                            window.showError('Please select a color');
                        }
                        return;
                    }

                    if (result === 'error_size') {
                        if (window.showError) {
                            window.showError('Please select a size');
                        }
                        return;
                    }

                    if (result === 'error_not_found') {
                        if (window.showError) {
                            window.showError('Selected variant is not available');
                        }
                        return;
                    }

                    const quantity = parseInt(document.getElementById('productQuantity').value);

                    if (window.cartManager) {
                        window.cartManager.addToCart("{{$product->id}}", result, quantity).then(success => {
                            if (success) {
                                window.location.href = "{{ route('checkout.index') }}";
                            }
                        });
                    }
                });
            }

            // Get selected variant based on color and size
            function getSelectedVariant() {
                const selectedColorBtn = document.querySelector('.color-btn.border-primary');
                const selectedSizeBtn = document.querySelector('.product-size-btn.border-primary');

                // If no variants exist, return null
                if (!productVariants || productVariants.length === 0) {
                    return null;
                }

                // Get unique colors and sizes from variants
                const colors = [...new Map(productVariants.filter(v => v.color).map(v => [v.color.id, v.color])).values()];
                const sizes = [...new Map(productVariants.filter(v => v.size).map(v => [v.size.id, v.size])).values()];

                const hasColors = colors.length > 0;
                const hasSizes = sizes.length > 0;

                // Validate selections
                if (hasColors && !selectedColorBtn) {
                    return 'error_color';
                }

                if (hasSizes && !selectedSizeBtn) {
                    return 'error_size';
                }

                // Get selected IDs
                const colorId = selectedColorBtn ? parseInt(selectedColorBtn.dataset.colorId) : null;
                const sizeId = selectedSizeBtn ? parseInt(selectedSizeBtn.dataset.sizeId) : null;

                // Find matching variant
                const variant = productVariants.find(v => {
                    const colorMatch = !hasColors || v.color_id === colorId;
                    const sizeMatch = !hasSizes || v.size_id === sizeId;
                    return colorMatch && sizeMatch;
                });

                return variant ? variant.id : 'error_not_found';
            }
        });
    </script>
@endpush