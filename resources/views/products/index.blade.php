@extends('layouts.app')

@section('title', 'Products - SmartFashion')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ url('/') }}" class="text-gray-500 hover:text-brand-blue transition">Home</a>
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <span class="text-gray-900 font-medium">Products</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Mobile Filter Toggle --}}
        <div class="lg:hidden flex items-center justify-between mb-2">
            <h1 class="text-xl font-bold text-brand-black">All Products</h1>
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
                    <h2 class="text-lg font-bold text-brand-black">Filters</h2>
                    <button class="text-sm text-brand-blue hover:underline font-medium">Clear All</button>
                </div>

                {{-- Category Filter --}}
                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('categoryFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Category
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="categoryFilterIcon"></i>
                    </button>
                    <div id="categoryFilter" class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900">Men's Wear</span>
                            <span class="ml-auto text-xs text-gray-400">(156)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900">Women's Wear</span>
                            <span class="ml-auto text-xs text-gray-400">(203)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900">T-Shirts</span>
                            <span class="ml-auto text-xs text-gray-400">(89)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900">Shirts</span>
                            <span class="ml-auto text-xs text-gray-400">(124)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900">Panjabi</span>
                            <span class="ml-auto text-xs text-gray-400">(67)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900">Saree</span>
                            <span class="ml-auto text-xs text-gray-400">(78)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900">3-Piece</span>
                            <span class="ml-auto text-xs text-gray-400">(45)</span>
                        </label>
                    </div>
                </div>

                {{-- Price Range Filter --}}
                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('priceFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Price Range
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="priceFilterIcon"></i>
                    </button>
                    <div id="priceFilter" class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <label class="text-xs text-gray-500 mb-1 block">Min</label>
                                <input type="number" placeholder="৳0" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                            </div>
                            <span class="text-gray-400 mt-5">-</span>
                            <div class="flex-1">
                                <label class="text-xs text-gray-500 mb-1 block">Max</label>
                                <input type="number" placeholder="৳10000" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="price" class="w-4 h-4 border-gray-300 text-brand-blue focus:ring-brand-blue">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900">Under ৳500</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="price" class="w-4 h-4 border-gray-300 text-brand-blue focus:ring-brand-blue">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900">৳500 - ৳1000</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="price" class="w-4 h-4 border-gray-300 text-brand-blue focus:ring-brand-blue">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900">৳1000 - ৳2000</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="price" class="w-4 h-4 border-gray-300 text-brand-blue focus:ring-brand-blue">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900">৳2000 - ৳5000</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="price" class="w-4 h-4 border-gray-300 text-brand-blue focus:ring-brand-blue">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900">Above ৳5000</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Size Filter --}}
                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('sizeFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Size
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="sizeFilterIcon"></i>
                    </button>
                    <div id="sizeFilter" class="flex flex-wrap gap-2">
                        <button class="size-btn px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">XS</button>
                        <button class="size-btn px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">S</button>
                        <button class="size-btn px-4 py-2 border border-brand-blue bg-brand-blue/5 rounded-lg text-sm font-medium text-brand-blue">M</button>
                        <button class="size-btn px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">L</button>
                        <button class="size-btn px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">XL</button>
                        <button class="size-btn px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">XXL</button>
                    </div>
                </div>

                {{-- Color Filter --}}
                <div class="border-b border-gray-100 pb-5 mb-5">
                    <button onclick="toggleFilterSection('colorFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Color
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="colorFilterIcon"></i>
                    </button>
                    <div id="colorFilter" class="flex flex-wrap gap-2">
                        <button class="w-8 h-8 rounded-full bg-black border-2 border-gray-300 hover:border-brand-blue transition relative">
                            <i class="fas fa-check text-white text-xs absolute inset-0 flex items-center justify-center"></i>
                        </button>
                        <button class="w-8 h-8 rounded-full bg-white border-2 border-gray-300 hover:border-brand-blue transition"></button>
                        <button class="w-8 h-8 rounded-full bg-gray-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                        <button class="w-8 h-8 rounded-full bg-red-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                        <button class="w-8 h-8 rounded-full bg-blue-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                        <button class="w-8 h-8 rounded-full bg-green-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                        <button class="w-8 h-8 rounded-full bg-yellow-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                        <button class="w-8 h-8 rounded-full bg-purple-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                        <button class="w-8 h-8 rounded-full bg-pink-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                        <button class="w-8 h-8 rounded-full bg-orange-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                    </div>
                </div>

                {{-- Rating Filter --}}
                <div class="pb-2">
                    <button onclick="toggleFilterSection('ratingFilter')" class="w-full flex items-center justify-between text-sm font-semibold text-gray-900 mb-3">
                        Rating
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="ratingFilterIcon"></i>
                    </button>
                    <div id="ratingFilter" class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                            </div>
                            <span class="text-xs text-gray-400">(45)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="far fa-star text-sm text-gray-300"></i>
                            </div>
                            <span class="text-xs text-gray-400">& up (120)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="far fa-star text-sm text-gray-300"></i>
                                <i class="far fa-star text-sm text-gray-300"></i>
                            </div>
                            <span class="text-xs text-gray-400">& up (89)</span>
                        </label>
                    </div>
                </div>

                {{-- Apply Filter Button (Mobile) --}}
                <button onclick="toggleMobileFilter()" class="lg:hidden w-full bg-brand-blue text-white py-3 rounded-xl font-semibold text-sm mt-4 tap-effect">
                    Apply Filters
                </button>

            </div>
        </aside>

        {{-- Products Grid Section --}}
        <div class="flex-1">

            {{-- Products Header --}}
            <div class="hidden lg:flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-brand-black">All Products</h1>
                    <p class="text-sm text-gray-500 mt-1">Showing 1-24 of 856 products</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- View Toggle --}}
                    <div class="flex items-center bg-gray-100 rounded-lg p-1">
                        <button class="p-2 rounded-md bg-white shadow-sm text-brand-blue">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button class="p-2 rounded-md text-gray-400 hover:text-gray-600">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                    {{-- Sort Dropdown --}}
                    <div class="relative">
                        <select class="appearance-none bg-white border border-gray-200 rounded-xl py-2.5 pl-4 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue cursor-pointer">
                            <option>Sort by: Featured</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Newest First</option>
                            <option>Best Selling</option>
                            <option>Top Rated</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            {{-- Mobile Sort --}}
            <div class="lg:hidden flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500">856 products</p>
                <select class="appearance-none bg-white border border-gray-200 rounded-lg py-2 pl-3 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-blue/30">
                    <option>Sort: Featured</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Newest First</option>
                </select>
            </div>

            {{-- Active Filters --}}
            <div class="flex flex-wrap items-center gap-2 mb-5">
                <span class="text-sm text-gray-500">Active Filters:</span>
                <span class="inline-flex items-center gap-1.5 bg-brand-blue/10 text-brand-blue px-3 py-1.5 rounded-full text-sm font-medium">
                    Size: M
                    <button class="hover:text-blue-700"><i class="fas fa-times text-xs"></i></button>
                </span>
                <span class="inline-flex items-center gap-1.5 bg-brand-blue/10 text-brand-blue px-3 py-1.5 rounded-full text-sm font-medium">
                    Color: Black
                    <button class="hover:text-blue-700"><i class="fas fa-times text-xs"></i></button>
                </span>
                <button class="text-sm text-red-500 hover:underline font-medium ml-2">Clear All</button>
            </div>

            {{-- Products Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-5">

                {{-- Product Card 1 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=400&h=500&fit=crop" alt="Premium Cotton Shirt" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">NEW</span>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Premium Cotton Formal Shirt</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="far fa-star text-xs text-gray-300"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(42)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳1,250</span>
                            <span class="text-gray-400 text-xs line-through">৳1,500</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 2 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=400&h=500&fit=crop" alt="Classic T-Shirt" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">-20%</span>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Classic Round Neck T-Shirt</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(128)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳650</span>
                            <span class="text-gray-400 text-xs line-through">৳800</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 3 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=400&h=500&fit=crop" alt="Designer Panjabi" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <span class="absolute top-2 left-2 bg-purple-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">TRENDING</span>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="fas fa-heart text-red-500"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Designer Embroidered Panjabi</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="far fa-star text-xs text-gray-300"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(89)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳2,450</span>
                            <span class="text-gray-400 text-xs line-through">৳2,900</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 4 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=400&h=500&fit=crop" alt="Slim Fit Jeans" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">HOT</span>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Slim Fit Stretch Jeans</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(234)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳1,450</span>
                            <span class="text-gray-400 text-xs line-through">৳1,800</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 5 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=400&h=500&fit=crop" alt="Casual Shirt" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Printed Casual Shirt</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="far fa-star text-xs text-gray-300"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(67)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳950</span>
                            <span class="text-gray-400 text-xs line-through">৳1,200</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 6 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?w=400&h=500&fit=crop" alt="Designer Saree" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <span class="absolute top-2 left-2 bg-pink-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">WOMEN</span>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Designer Silk Saree</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(156)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳3,850</span>
                            <span class="text-gray-400 text-xs line-through">৳4,500</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 7 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=400&h=500&fit=crop" alt="Formal Blazer" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Slim Fit Formal Blazer</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="far fa-star text-xs text-gray-300"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(34)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳4,500</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 8 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1617019114583-affb34d1b3cd?w=400&h=500&fit=crop" alt="3-Piece Set" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <span class="absolute top-2 left-2 bg-pink-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">WOMEN</span>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Embroidered 3-Piece Set</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(98)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳2,950</span>
                            <span class="text-gray-400 text-xs line-through">৳3,400</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 9 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=400&h=500&fit=crop" alt="Premium Polo" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Premium Striped Polo</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="far fa-star text-xs text-gray-300"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(56)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳990</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 10 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1609505848912-b7c3b8b4beda?w=400&h=500&fit=crop" alt="Chino Pants" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <span class="absolute top-2 left-2 bg-orange-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">-15%</span>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Comfortable Chino Pants</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="far fa-star text-xs text-gray-300"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(78)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳1,280</span>
                            <span class="text-gray-400 text-xs line-through">৳1,500</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 11 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1608234808654-2a8875faa7fd?w=400&h=500&fit=crop" alt="Panjabi" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Traditional Cotton Panjabi</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(112)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳2,100</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

                {{-- Product Card 12 --}}
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        <a href="#">
                            <img src="https://images.unsplash.com/photo-1591369822096-ffd140ec948f?w=400&h=500&fit=crop" alt="Polo T-Shirt" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                        </a>
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">NEW</span>
                        <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                    <div class="p-3 md:p-4">
                        <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Premium Polo T-Shirt</a>
                        <div class="flex items-center gap-1 my-1.5">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="far fa-star text-xs text-gray-300"></i>
                            </div>
                            <span class="text-[10px] text-gray-400">(45)</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-brand-blue font-bold text-base md:text-lg">৳890</span>
                            <span class="text-gray-400 text-xs line-through">৳1,100</span>
                        </div>
                        <button class="w-full bg-brand-blue text-white py-2 md:py-2.5 rounded-xl font-semibold text-xs md:text-sm hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                    </div>
                </div>

            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-center gap-2 mt-8">
                <button class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-400 hover:border-brand-blue hover:text-brand-blue transition disabled:opacity-50" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="w-10 h-10 rounded-xl bg-brand-blue text-white font-semibold">1</button>
                <button class="w-10 h-10 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-brand-blue hover:text-brand-blue transition">2</button>
                <button class="w-10 h-10 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-brand-blue hover:text-brand-blue transition">3</button>
                <span class="text-gray-400">...</span>
                <button class="w-10 h-10 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-brand-blue hover:text-brand-blue transition">36</button>
                <button class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Mobile Filter Overlay --}}
<div id="mobileFilterOverlay" class="fixed inset-0 bg-black/50 z-[60] hidden lg:hidden" onclick="toggleMobileFilter()"></div>
@endsection

@push('scripts')
<script>
    // Toggle mobile filter sidebar
    function toggleMobileFilter() {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('mobileFilterOverlay');

        sidebar.classList.toggle('hidden');
        overlay.classList.toggle('hidden');

        if (!sidebar.classList.contains('hidden')) {
            sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-[70]', 'w-80', 'max-w-[85vw]', 'overflow-y-auto', 'bg-white', 'shadow-2xl');
            sidebar.classList.remove('lg:block');
            document.body.style.overflow = 'hidden';
        } else {
            sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-[70]', 'w-80', 'max-w-[85vw]', 'overflow-y-auto', 'bg-white', 'shadow-2xl');
            sidebar.classList.add('lg:block');
            document.body.style.overflow = '';
        }
    }

    // Toggle filter section collapse
    function toggleFilterSection(sectionId) {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(sectionId + 'Icon');

        section.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    // Size button toggle
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('border-brand-blue', 'bg-brand-blue/5', 'text-brand-blue');
                b.classList.add('border-gray-200', 'text-gray-600');
            });
            this.classList.remove('border-gray-200', 'text-gray-600');
            this.classList.add('border-brand-blue', 'bg-brand-blue/5', 'text-brand-blue');
        });
    });

    // Color button toggle
    document.querySelectorAll('#colorFilter button').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#colorFilter button').forEach(b => {
                b.classList.remove('border-brand-blue');
                b.classList.add('border-transparent');
                const check = b.querySelector('i');
                if (check) check.remove();
            });
            this.classList.remove('border-transparent');
            this.classList.add('border-brand-blue');
            if (!this.querySelector('i')) {
                const check = document.createElement('i');
                check.className = 'fas fa-check text-white text-xs absolute inset-0 flex items-center justify-center';
                this.classList.add('relative');
                this.appendChild(check);
            }
        });
    });
</script>
@endpush