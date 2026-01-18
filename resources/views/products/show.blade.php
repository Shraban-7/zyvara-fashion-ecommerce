@extends('layouts.app')

@section('title', 'Premium Cotton Formal Shirt - SmartFashion')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-white border-b border-gray-100 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <nav class="flex items-center gap-2 text-sm overflow-x-auto hide-scrollbar whitespace-nowrap">
            <a href="{{ url('/') }}" class="text-gray-500 hover:text-brand-blue transition flex-shrink-0">Home</a>
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-brand-blue transition flex-shrink-0">Products</a>
            <i class="fas fa-chevron-right text-xs text-gray-400 flex-shrink-0"></i>
            <a href="#" class="text-gray-500 hover:text-brand-blue transition flex-shrink-0">Men's Wear</a>
            <i class="fas fa-chevron-right text-xs text-gray-400 flex-shrink-0"></i>
            <span class="text-gray-900 font-medium flex-shrink-0">Premium Cotton Formal Shirt</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
    {{-- Product Main Section --}}
    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">

        {{-- Product Images --}}
        <div class="space-y-4">
            {{-- Main Image --}}
            <div class="relative bg-white rounded-2xl overflow-hidden border border-gray-100">
                <img id="mainProductImage" src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800&h=1000&fit=crop" alt="Premium Cotton Formal Shirt" class="w-full h-[400px] sm:h-[500px] lg:h-[600px] object-cover">

                {{-- Badges --}}
                <div class="absolute top-4 left-4 flex flex-col gap-2">
                    <span class="bg-green-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full">NEW ARRIVAL</span>
                    <span class="bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full">-17% OFF</span>
                </div>

                {{-- Wishlist Button --}}
                <button class="absolute top-4 right-4 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-gray-50 transition tap-effect">
                    <i class="far fa-heart text-gray-600 text-lg"></i>
                </button>

                {{-- Zoom Button --}}
                <button onclick="openImageModal()" class="absolute bottom-4 right-4 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                    <i class="fas fa-search-plus text-gray-600"></i>
                </button>
            </div>

            {{-- Thumbnail Images --}}
            <div class="w-full max-w-full overflow-x-auto hide-scrollbar pb-2">
                <div class="flex gap-3 w-max">
                    <button onclick="changeMainImage(this)" class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border-2 border-brand-blue">
                        <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=200&h=200&fit=crop" alt="Thumbnail 1" class="w-full h-full object-cover">
                    </button>
                    <button onclick="changeMainImage(this)" class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border-2 border-transparent hover:border-brand-blue transition">
                        <img src="https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=200&h=200&fit=crop" alt="Thumbnail 2" class="w-full h-full object-cover">
                    </button>
                    <button onclick="changeMainImage(this)" class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border-2 border-transparent hover:border-brand-blue transition">
                        <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=200&h=200&fit=crop" alt="Thumbnail 3" class="w-full h-full object-cover">
                    </button>
                    <button onclick="changeMainImage(this)" class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border-2 border-transparent hover:border-brand-blue transition">
                        <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=200&h=200&fit=crop" alt="Thumbnail 4" class="w-full h-full object-cover">
                    </button>
                    <button onclick="changeMainImage(this)" class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border-2 border-transparent hover:border-brand-blue transition">
                        <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=200&h=200&fit=crop" alt="Thumbnail 5" class="w-full h-full object-cover">
                    </button>
                </div>
            </div>
        </div>

        {{-- Product Info --}}
        <div class="space-y-6 min-w-0 overflow-hidden">
            {{-- Title & Rating --}}
            <div>
                <p class="text-sm text-brand-blue font-medium mb-2">SmartFashion • Men's Wear</p>
                <h1 class="text-2xl md:text-3xl font-bold text-brand-black mb-3">Premium Cotton Formal Shirt</h1>

                <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                    <div class="flex items-center gap-2">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star text-gray-300"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">4.2</span>
                        <span class="text-xs sm:text-sm text-gray-400">(128 reviews)</span>
                    </div>
                    <span class="text-gray-300 hidden sm:inline">|</span>
                    <span class="text-xs sm:text-sm text-green-600 font-medium"><i class="fas fa-check-circle mr-1"></i>In Stock</span>
                </div>
            </div>

            {{-- Price --}}
            <div class="bg-gradient-to-r from-blue-50 to-brand-light rounded-2xl p-5 overflow-hidden">
                <div class="flex flex-wrap items-end gap-2 sm:gap-3 mb-2">
                    <span class="text-2xl sm:text-3xl md:text-4xl font-bold text-brand-blue">৳1,250</span>
                    <span class="text-lg sm:text-xl text-gray-400 line-through">৳1,500</span>
                    <span class="bg-red-100 text-red-600 text-xs sm:text-sm font-semibold px-2 py-1 rounded-lg">Save ৳250</span>
                </div>
                <p class="text-xs sm:text-sm text-gray-500">Inclusive of all taxes. Free shipping on orders over ৳2000.</p>
            </div>

            {{-- Color Selection --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-gray-900">Color: <span class="font-normal text-gray-600">White</span></span>
                </div>
                <div class="flex gap-3">
                    <button class="w-10 h-10 rounded-full bg-white border-2 border-brand-blue shadow-sm relative">
                        <i class="fas fa-check text-brand-blue text-xs absolute inset-0 flex items-center justify-center"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-blue-500 border-2 border-transparent hover:border-brand-blue transition"></button>
                    <button class="w-10 h-10 rounded-full bg-gray-800 border-2 border-transparent hover:border-brand-blue transition"></button>
                    <button class="w-10 h-10 rounded-full bg-pink-200 border-2 border-transparent hover:border-brand-blue transition"></button>
                </div>
            </div>

            {{-- Size Selection --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-gray-900">Size: <span class="font-normal text-gray-600">Select a size</span></span>
                    <button class="text-sm text-brand-blue hover:underline font-medium">Size Guide</button>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button class="product-size-btn w-14 h-11 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">S</button>
                    <button class="product-size-btn w-14 h-11 border border-brand-blue bg-brand-blue/5 rounded-xl text-sm font-medium text-brand-blue">M</button>
                    <button class="product-size-btn w-14 h-11 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">L</button>
                    <button class="product-size-btn w-14 h-11 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">XL</button>
                    <button class="product-size-btn w-14 h-11 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:border-brand-blue hover:text-brand-blue transition">XXL</button>
                    <button class="product-size-btn w-14 h-11 border border-gray-200 rounded-xl text-sm font-medium text-gray-300 cursor-not-allowed" disabled>3XL</button>
                </div>
            </div>

            {{-- Quantity --}}
            <div>
                <span class="text-sm font-semibold text-gray-900 mb-3 block">Quantity</span>
                <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button onclick="updateQuantity(-1)" class="w-10 sm:w-11 h-10 sm:h-11 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" id="productQuantity" value="1" min="1" max="10" class="w-12 sm:w-14 h-10 sm:h-11 text-center text-sm font-semibold border-x border-gray-200 focus:outline-none">
                        <button onclick="updateQuantity(1)" class="w-10 sm:w-11 h-10 sm:h-11 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>
                    <span class="text-xs sm:text-sm text-gray-500">Only <span class="text-orange-500 font-semibold">12 items</span> left!</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2 w-full">
                <button class="w-full sm:flex-1 bg-brand-blue text-white py-3.5 sm:py-4 rounded-xl font-semibold text-sm sm:text-base hover:bg-blue-600 transition tap-effect shadow-lg shadow-brand-blue/25 flex items-center justify-center gap-2">
                    <i class="fas fa-shopping-cart"></i>
                    Add to Cart
                </button>
                <button class="w-full sm:flex-1 bg-brand-black text-white py-3.5 sm:py-4 rounded-xl font-semibold text-sm sm:text-base hover:bg-gray-800 transition tap-effect flex items-center justify-center gap-2">
                    <i class="fas fa-bolt"></i>
                    Buy Now
                </button>
            </div>

            {{-- Delivery Info --}}
            <div class="bg-gray-50 rounded-2xl p-5 space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-truck text-brand-blue"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm">Free Delivery</h4>
                        <p class="text-xs text-gray-500">Enter your postal code for delivery availability</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-undo text-brand-blue"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm">7 Days Return</h4>
                        <p class="text-xs text-gray-500">Free returns within 7 days of delivery</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shield-alt text-brand-blue"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm">Secure Payment</h4>
                        <p class="text-xs text-gray-500">100% secure payment with bKash, Nagad, Card</p>
                    </div>
                </div>
            </div>

            {{-- Share --}}
            <div class="flex items-center gap-4 pt-2">
                <span class="text-sm font-medium text-gray-600">Share:</span>
                <div class="flex gap-2">
                    <a href="#" class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 bg-sky-500 rounded-full flex items-center justify-center text-white hover:bg-sky-600 transition">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 bg-green-500 rounded-full flex items-center justify-center text-white hover:bg-green-600 transition">
                        <i class="fab fa-whatsapp text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                        <i class="fas fa-link text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Details Tabs --}}
    <div class="mt-12 md:mt-16">
        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200">
            <div class="flex gap-8 overflow-x-auto hide-scrollbar">
                <button onclick="switchProductTab('description')" class="product-tab pb-4 text-sm font-semibold text-brand-blue border-b-2 border-brand-blue whitespace-nowrap" data-tab="description">
                    Description
                </button>
                <button onclick="switchProductTab('specifications')" class="product-tab pb-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="specifications">
                    Specifications
                </button>
                <button onclick="switchProductTab('reviews')" class="product-tab pb-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="reviews">
                    Reviews (128)
                </button>
                <button onclick="switchProductTab('shipping')" class="product-tab pb-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="shipping">
                    Shipping & Returns
                </button>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="py-8">
            {{-- Description Tab --}}
            <div id="descriptionTab" class="product-tab-content">
                <div class="prose max-w-none">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Product Description</h3>
                    <p class="text-gray-600 mb-4">
                        Introducing our Premium Cotton Formal Shirt - the perfect blend of comfort, style, and sophistication. Crafted from 100% premium Egyptian cotton, this shirt offers exceptional breathability and a luxuriously soft feel against your skin.
                    </p>
                    <p class="text-gray-600 mb-4">
                        Designed for the modern professional, this formal shirt features a classic spread collar, adjustable button cuffs, and a tailored slim fit that flatters your silhouette without compromising on comfort. The reinforced stitching ensures durability, while the easy-iron fabric makes maintenance a breeze.
                    </p>

                    <h4 class="text-base font-semibold text-gray-900 mt-6 mb-3">Key Features:</h4>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>100% Premium Egyptian Cotton</li>
                        <li>Breathable and lightweight fabric</li>
                        <li>Classic spread collar design</li>
                        <li>Slim fit tailored cut</li>
                        <li>Easy-iron wrinkle-resistant fabric</li>
                        <li>Reinforced button holes</li>
                        <li>Available in multiple colors</li>
                    </ul>

                    <h4 class="text-base font-semibold text-gray-900 mt-6 mb-3">Care Instructions:</h4>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Machine wash cold with similar colors</li>
                        <li>Tumble dry low or hang to dry</li>
                        <li>Warm iron if needed</li>
                        <li>Do not bleach</li>
                    </ul>
                </div>
            </div>

            {{-- Specifications Tab --}}
            <div id="specificationsTab" class="product-tab-content hidden">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Product Specifications</h3>
                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <table class="w-full">
                        <tbody>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100 w-1/3">Brand</td>
                                <td class="py-4 px-5 text-sm text-gray-900">SmartFashion</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100">Material</td>
                                <td class="py-4 px-5 text-sm text-gray-900">100% Egyptian Cotton</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100">Fit Type</td>
                                <td class="py-4 px-5 text-sm text-gray-900">Slim Fit</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100">Collar Style</td>
                                <td class="py-4 px-5 text-sm text-gray-900">Spread Collar</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100">Sleeve Type</td>
                                <td class="py-4 px-5 text-sm text-gray-900">Full Sleeve</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100">Pattern</td>
                                <td class="py-4 px-5 text-sm text-gray-900">Solid</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100">Occasion</td>
                                <td class="py-4 px-5 text-sm text-gray-900">Formal, Office Wear, Business Meetings</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100">Available Sizes</td>
                                <td class="py-4 px-5 text-sm text-gray-900">S, M, L, XL, XXL</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-5 text-sm font-medium text-gray-600 bg-gray-100">Country of Origin</td>
                                <td class="py-4 px-5 text-sm text-gray-900">Bangladesh</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Reviews Tab --}}
            <div id="reviewsTab" class="product-tab-content hidden">
                <div class="grid lg:grid-cols-3 gap-8">
                    {{-- Rating Summary --}}
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Customer Reviews</h3>
                            <div class="text-center mb-6">
                                <div class="text-5xl font-bold text-gray-900 mb-2">4.2</div>
                                <div class="flex justify-center text-yellow-400 mb-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star text-gray-300"></i>
                                </div>
                                <p class="text-sm text-gray-500">Based on 128 reviews</p>
                            </div>

                            {{-- Rating Bars --}}
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-600 w-8">5 ★</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full" style="width: 60%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-10">77</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-600 w-8">4 ★</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full" style="width: 25%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-10">32</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-600 w-8">3 ★</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full" style="width: 10%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-10">12</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-600 w-8">2 ★</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full" style="width: 3%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-10">4</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-600 w-8">1 ★</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full" style="width: 2%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-10">3</span>
                                </div>
                            </div>

                            <button class="w-full mt-6 bg-brand-blue text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-600 transition tap-effect">
                                Write a Review
                            </button>
                        </div>
                    </div>

                    {{-- Reviews List --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Review 1 --}}
                        <div class="bg-white border border-gray-100 rounded-2xl p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-brand-blue rounded-full flex items-center justify-center text-white font-semibold">
                                        R
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm">Rahim Ahmed</h4>
                                        <p class="text-xs text-gray-400">Verified Purchase • 2 days ago</p>
                                    </div>
                                </div>
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">
                                Excellent quality shirt! The fabric is very soft and comfortable. Perfect fit for my body type. The color is exactly as shown in the picture. Highly recommended!
                            </p>
                            <div class="flex items-center gap-4 text-sm">
                                <button class="text-gray-500 hover:text-brand-blue transition flex items-center gap-1">
                                    <i class="far fa-thumbs-up"></i> Helpful (12)
                                </button>
                            </div>
                        </div>

                        {{-- Review 2 --}}
                        <div class="bg-white border border-gray-100 rounded-2xl p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        F
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm">Fatima Khan</h4>
                                        <p class="text-xs text-gray-400">Verified Purchase • 1 week ago</p>
                                    </div>
                                </div>
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="far fa-star text-sm text-gray-300"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">
                                Bought this for my husband. Great quality and he loves it! The only minor issue is that it runs slightly large, so consider ordering one size down if you prefer a tighter fit.
                            </p>
                            <div class="flex items-center gap-4 text-sm">
                                <button class="text-gray-500 hover:text-brand-blue transition flex items-center gap-1">
                                    <i class="far fa-thumbs-up"></i> Helpful (8)
                                </button>
                            </div>
                        </div>

                        {{-- Review 3 --}}
                        <div class="bg-white border border-gray-100 rounded-2xl p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        K
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm">Kamal Hossain</h4>
                                        <p class="text-xs text-gray-400">Verified Purchase • 2 weeks ago</p>
                                    </div>
                                </div>
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                    <i class="fas fa-star text-sm"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">
                                This is my third shirt from SmartFashion and they never disappoint. Premium quality, fast delivery, and great customer service. Will definitely buy again!
                            </p>
                            <div class="flex items-center gap-4 text-sm">
                                <button class="text-gray-500 hover:text-brand-blue transition flex items-center gap-1">
                                    <i class="far fa-thumbs-up"></i> Helpful (15)
                                </button>
                            </div>
                        </div>

                        {{-- Load More --}}
                        <button class="w-full border border-gray-200 text-gray-600 py-3 rounded-xl font-medium text-sm hover:bg-gray-50 transition">
                            Load More Reviews
                        </button>
                    </div>
                </div>
            </div>

            {{-- Shipping Tab --}}
            <div id="shippingTab" class="product-tab-content hidden">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Shipping Information</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                                <i class="fas fa-truck text-brand-blue text-lg mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">Standard Delivery</h4>
                                    <p class="text-sm text-gray-600">3-5 business days • ৳60</p>
                                    <p class="text-xs text-gray-500 mt-1">Free on orders over ৳2000</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                                <i class="fas fa-shipping-fast text-brand-blue text-lg mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">Express Delivery</h4>
                                    <p class="text-sm text-gray-600">1-2 business days • ৳120</p>
                                    <p class="text-xs text-gray-500 mt-1">Available in Dhaka only</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                                <i class="fas fa-store text-brand-blue text-lg mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">Store Pickup</h4>
                                    <p class="text-sm text-gray-600">Same day • Free</p>
                                    <p class="text-xs text-gray-500 mt-1">Bashundhara City, Dhaka</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Return Policy</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                                <i class="fas fa-undo text-brand-blue text-lg mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">7 Days Easy Return</h4>
                                    <p class="text-sm text-gray-600">Return within 7 days of delivery for a full refund or exchange.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                                <i class="fas fa-exchange-alt text-brand-blue text-lg mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">Free Exchange</h4>
                                    <p class="text-sm text-gray-600">Exchange for a different size or color at no extra cost.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                                <i class="fas fa-info-circle text-brand-blue text-lg mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">Return Conditions</h4>
                                    <p class="text-sm text-gray-600">Item must be unused, unwashed, and in original packaging with tags attached.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    <div class="mt-12 md:mt-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-brand-black">You May Also Like</h2>
            <a href="{{ route('products.index') }}" class="text-brand-blue text-sm font-semibold flex items-center gap-1 tap-effect">
                View All
                <i class="fas fa-chevron-right text-sm"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5">
            {{-- Product Card 1 --}}
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
                    <div class="flex items-center gap-2 my-2">
                        <span class="text-brand-blue font-bold text-base">৳950</span>
                        <span class="text-gray-400 text-xs line-through">৳1,200</span>
                    </div>
                    <button class="w-full bg-brand-blue text-white py-2 rounded-xl font-semibold text-xs hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                </div>
            </div>

            {{-- Product Card 2 --}}
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                <div class="relative">
                    <a href="#">
                        <img src="https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=400&h=500&fit=crop" alt="Denim Shirt" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                    </a>
                    <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                        <i class="far fa-heart text-gray-600"></i>
                    </button>
                </div>
                <div class="p-3 md:p-4">
                    <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Denim Casual Shirt</a>
                    <div class="flex items-center gap-2 my-2">
                        <span class="text-brand-blue font-bold text-base">৳1,350</span>
                    </div>
                    <button class="w-full bg-brand-blue text-white py-2 rounded-xl font-semibold text-xs hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                </div>
            </div>

            {{-- Product Card 3 --}}
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                <div class="relative">
                    <a href="#">
                        <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=400&h=500&fit=crop" alt="Classic T-Shirt" class="w-full h-40 sm:h-48 md:h-56 object-cover">
                    </a>
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-semibold px-2 py-1 rounded-full">-20%</span>
                    <button class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-white transition tap-effect">
                        <i class="far fa-heart text-gray-600"></i>
                    </button>
                </div>
                <div class="p-3 md:p-4">
                    <a href="#" class="text-sm md:text-base font-medium text-brand-black mb-1 line-clamp-2 hover:text-brand-blue transition">Classic Round Neck T-Shirt</a>
                    <div class="flex items-center gap-2 my-2">
                        <span class="text-brand-blue font-bold text-base">৳650</span>
                        <span class="text-gray-400 text-xs line-through">৳800</span>
                    </div>
                    <button class="w-full bg-brand-blue text-white py-2 rounded-xl font-semibold text-xs hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                </div>
            </div>

            {{-- Product Card 4 --}}
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
                    <div class="flex items-center gap-2 my-2">
                        <span class="text-brand-blue font-bold text-base">৳990</span>
                    </div>
                    <button class="w-full bg-brand-blue text-white py-2 rounded-xl font-semibold text-xs hover:bg-blue-600 transition tap-effect">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/90" onclick="closeImageModal()"></div>
    <button onclick="closeImageModal()" class="absolute top-4 right-4 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white z-10 transition">
        <i class="fas fa-times text-xl"></i>
    </button>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <img id="modalImage" src="" alt="Product Image" class="max-w-full max-h-full object-contain">
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Change main product image
    function changeMainImage(btn) {
        const img = btn.querySelector('img');
        const mainImage = document.getElementById('mainProductImage');

        // Update main image
        mainImage.src = img.src.replace('200&h=200', '800&h=1000');

        // Update thumbnail borders
        document.querySelectorAll('.flex.gap-3 button').forEach(b => {
            b.classList.remove('border-brand-blue');
            b.classList.add('border-transparent');
        });
        btn.classList.remove('border-transparent');
        btn.classList.add('border-brand-blue');
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
        let value = parseInt(input.value) + change;
        if (value < 1) value = 1;
        if (value > 10) value = 10;
        input.value = value;
    }

    // Switch product tabs
    function switchProductTab(tab) {
        // Update tab buttons
        document.querySelectorAll('.product-tab').forEach(t => {
            t.classList.remove('text-brand-blue', 'border-brand-blue', 'font-semibold');
            t.classList.add('text-gray-500', 'border-transparent', 'font-medium');
        });
        document.querySelector(`[data-tab="${tab}"]`).classList.remove('text-gray-500', 'border-transparent', 'font-medium');
        document.querySelector(`[data-tab="${tab}"]`).classList.add('text-brand-blue', 'border-brand-blue', 'font-semibold');

        // Update tab content
        document.querySelectorAll('.product-tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(tab + 'Tab').classList.remove('hidden');
    }

    // Size button toggle
    document.querySelectorAll('.product-size-btn:not([disabled])').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.product-size-btn').forEach(b => {
                b.classList.remove('border-brand-blue', 'bg-brand-blue/5', 'text-brand-blue');
                if (!b.disabled) {
                    b.classList.add('border-gray-200', 'text-gray-600');
                }
            });
            this.classList.remove('border-gray-200', 'text-gray-600');
            this.classList.add('border-brand-blue', 'bg-brand-blue/5', 'text-brand-blue');
        });
    });

    // Close modal on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endpush