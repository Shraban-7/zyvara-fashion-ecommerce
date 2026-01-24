@extends('layouts.app')

@section('title', 'Checkout - SmartFashion')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ url('/') }}" class="text-gray-500 hover:text-brand-blue transition">Home</a>
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <a href="#" onclick="openCartDrawer(); return false;" class="text-gray-500 hover:text-brand-blue transition">Cart</a>
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <span class="text-gray-900 font-medium">Checkout</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
    {{-- Checkout Header --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-brand-black mb-2">Checkout</h1>
        <p class="text-gray-500 text-sm">Complete your order by filling in the details below</p>
    </div>

    <form id="checkoutForm" class="grid lg:grid-cols-3 gap-6 lg:gap-8">
        {{-- Left Column - Customer Details --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Customer Information --}}
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-brand-blue text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                    <h2 class="text-lg font-bold text-brand-black">Customer Information</h2>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Enter your full name" class="w-full h-12 px-4 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-brand-blue transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                        <div class="flex">
                            <span class="h-12 px-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl flex items-center text-sm text-gray-600">+880</span>
                            <input type="tel" name="phone" required placeholder="1XXXXXXXXX" pattern="1[3-9][0-9]{8}" maxlength="10" class="flex-1 h-12 px-4 border border-gray-200 rounded-r-xl text-sm focus:outline-none focus:border-brand-blue transition">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">We'll contact you on this number</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-gray-400">(Optional)</span></label>
                        <input type="email" name="email" placeholder="your@email.com" class="w-full h-12 px-4 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-brand-blue transition">
                    </div>
                </div>
            </div>

            {{-- Delivery Address --}}
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-brand-blue text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                    <h2 class="text-lg font-bold text-brand-black">Delivery Address</h2>
                </div>

                {{-- Delivery Zone Selection --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Delivery Zone <span class="text-red-500">*</span></label>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <label class="delivery-zone-option relative cursor-pointer">
                            <input type="radio" name="delivery_zone" value="inside_dhaka" class="sr-only peer" checked>
                            <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-gray-900">Inside Dhaka</span>
                                    <span class="text-brand-blue font-bold">৳60</span>
                                </div>
                                <p class="text-xs text-gray-500">Delivery within 1-2 business days</p>
                            </div>
                            <div class="absolute top-3 right-3 w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-brand-blue peer-checked:bg-brand-blue flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                            </div>
                        </label>
                        <label class="delivery-zone-option relative cursor-pointer">
                            <input type="radio" name="delivery_zone" value="outside_dhaka" class="sr-only peer">
                            <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-gray-900">Outside Dhaka</span>
                                    <span class="text-brand-blue font-bold">৳120</span>
                                </div>
                                <p class="text-xs text-gray-500">Delivery within 3-5 business days</p>
                            </div>
                            <div class="absolute top-3 right-3 w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-brand-blue peer-checked:bg-brand-blue flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">District <span class="text-red-500">*</span></label>
                        <select name="district" required class="w-full h-12 px-4 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-brand-blue transition bg-white">
                            <option value="">Select District</option>
                            <option value="dhaka">Dhaka</option>
                            <option value="chittagong">Chittagong</option>
                            <option value="sylhet">Sylhet</option>
                            <option value="rajshahi">Rajshahi</option>
                            <option value="khulna">Khulna</option>
                            <option value="barishal">Barishal</option>
                            <option value="rangpur">Rangpur</option>
                            <option value="mymensingh">Mymensingh</option>
                            <option value="comilla">Comilla</option>
                            <option value="gazipur">Gazipur</option>
                            <option value="narayanganj">Narayanganj</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City/Area <span class="text-red-500">*</span></label>
                        <input type="text" name="city" required placeholder="e.g., Uttara, Mirpur, Dhanmondi" class="w-full h-12 px-4 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-brand-blue transition">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Address <span class="text-red-500">*</span></label>
                        <textarea name="address" required rows="3" placeholder="House/Flat No, Road, Block, Area (Be specific for faster delivery)" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-brand-blue transition resize-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-brand-blue text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                    <h2 class="text-lg font-bold text-brand-black">Payment Method</h2>
                </div>

                <div class="space-y-3">
                    {{-- Cash on Delivery --}}
                    <label class="payment-option block relative cursor-pointer">
                        <input type="radio" name="payment_method" value="cod" class="sr-only peer" checked>
                        <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Cash on Delivery</h4>
                                    <p class="text-xs text-gray-500">Pay when you receive your order</p>
                                </div>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-brand-blue peer-checked:bg-brand-blue flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </label>

                    {{-- bKash Payment --}}
                    <label class="payment-option block relative cursor-pointer">
                        <input type="radio" name="payment_method" value="bkash" class="sr-only peer">
                        <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <img src="{{ asset('assets/images/bkash.png') }}" alt="bKash" class="h-8">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">bKash Payment</h4>
                                    <p class="text-xs text-gray-500">Send money to our bKash merchant number</p>
                                </div>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-brand-blue peer-checked:bg-brand-blue flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </label>

                    {{-- Nagad Payment --}}
                    <label class="payment-option block relative cursor-pointer">
                        <input type="radio" name="payment_method" value="nagad" class="sr-only peer">
                        <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <img src="{{ asset('assets/images/nagad.png') }}" alt="Nagad" class="h-8">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Nagad Payment</h4>
                                    <p class="text-xs text-gray-500">Send money to our Nagad number</p>
                                </div>
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-brand-blue peer-checked:bg-brand-blue flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- bKash Payment Instructions --}}
                <div id="bkashInstructions" class="hidden mt-5 p-5 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl border border-pink-200">
                    <h4 class="font-bold text-pink-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        bKash Payment Instructions
                    </h4>
                    <ol class="space-y-3 text-sm text-pink-900">
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>
                            <span>Open your <strong>bKash App</strong> or dial <strong>*247#</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                            <span>Select <strong>"Send Money"</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>
                            <div>
                                <span>Enter our bKash Merchant Number:</span>
                                <div class="mt-2 flex items-center gap-2">
                                    <code class="bg-white px-4 py-2 rounded-lg font-bold text-pink-700 text-lg">01712-345678</code>
                                    <button type="button" onclick="copyToClipboard('01712345678', this)" class="p-2 hover:bg-pink-200 rounded-lg transition">
                                        <i class="far fa-copy text-pink-600"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span>
                            <span>Enter the <strong>Total Amount</strong> shown in order summary</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">5</span>
                            <span>Add <strong>Reference:</strong> Your Phone Number</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">6</span>
                            <span>Enter your bKash <strong>PIN</strong> to confirm</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">7</span>
                            <span>Enter the <strong>Transaction ID (TrxID)</strong> below</span>
                        </li>
                    </ol>

                    {{-- Quick Pay Button --}}
                    <a href="https://shop.bkash.com/smart-fashion01712345678/pay" target="_blank" class="mt-4 w-full bg-pink-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-pink-700 transition flex items-center justify-center gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/BKash-bKash-Logo.wine.svg/200px-BKash-bKash-Logo.wine.svg.png" alt="bKash" class="h-5 brightness-0 invert">
                        Pay with bKash App
                    </a>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-pink-800 mb-2">Transaction ID (TrxID) <span class="text-red-500">*</span></label>
                        <input type="text" name="bkash_trx_id" placeholder="e.g., 8N72KS92JD" class="w-full h-12 px-4 border border-pink-300 rounded-xl text-sm focus:outline-none focus:border-pink-500 bg-white transition uppercase" style="letter-spacing: 1px;">
                        <p class="text-xs text-pink-600 mt-1">Enter the TrxID from your bKash confirmation SMS</p>
                    </div>
                </div>

                {{-- Nagad Payment Instructions --}}
                <div id="nagadInstructions" class="hidden mt-5 p-5 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                    <h4 class="font-bold text-orange-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Nagad Payment Instructions
                    </h4>
                    <ol class="space-y-3 text-sm text-orange-900">
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>
                            <span>Open your <strong>Nagad App</strong> or dial <strong>*167#</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                            <span>Select <strong>"Send Money"</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>
                            <div>
                                <span>Enter our Nagad Number:</span>
                                <div class="mt-2 flex items-center gap-2">
                                    <code class="bg-white px-4 py-2 rounded-lg font-bold text-orange-700 text-lg">01812-345678</code>
                                    <button type="button" onclick="copyToClipboard('01812345678', this)" class="p-2 hover:bg-orange-200 rounded-lg transition">
                                        <i class="far fa-copy text-orange-600"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span>
                            <span>Enter the <strong>Total Amount</strong> shown in order summary</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">5</span>
                            <span>Add <strong>Reference:</strong> Your Phone Number</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">6</span>
                            <span>Enter your Nagad <strong>PIN</strong> to confirm</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-orange-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">7</span>
                            <span>Enter the <strong>Transaction ID (TrxID)</strong> below</span>
                        </li>
                    </ol>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-orange-800 mb-2">Transaction ID (TrxID) <span class="text-red-500">*</span></label>
                        <input type="text" name="nagad_trx_id" placeholder="e.g., N8K72J92" class="w-full h-12 px-4 border border-orange-300 rounded-xl text-sm focus:outline-none focus:border-orange-500 bg-white transition uppercase" style="letter-spacing: 1px;">
                        <p class="text-xs text-orange-600 mt-1">Enter the TrxID from your Nagad confirmation SMS</p>
                    </div>
                </div>
            </div>

            {{-- Order Notes --}}
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-sm">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <h2 class="text-lg font-bold text-brand-black">Order Notes <span class="text-gray-400 font-normal text-sm">(Optional)</span></h2>
                </div>
                <textarea name="notes" rows="3" placeholder="Any special instructions for your order? e.g., Gift wrapping, specific delivery time, etc." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-brand-blue transition resize-none"></textarea>
            </div>
        </div>

        {{-- Right Column - Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-100 shadow-sm sticky top-24">
                <h2 class="text-lg font-bold text-brand-black mb-5">Order Summary</h2>

                {{-- Cart Items --}}
                <div class="space-y-4 max-h-64 overflow-y-auto mb-5 pr-2">
                    <div class="flex gap-3">
                        <div class="w-16 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=150&h=200&fit=crop" alt="Product" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">Premium Cotton Formal Shirt</h4>
                            <p class="text-xs text-gray-500 mt-1">Size: M | Color: White</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">Qty: 1</span>
                                <span class="text-sm font-semibold text-gray-900">৳1,250</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-16 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=150&h=200&fit=crop" alt="Product" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">Printed Casual Shirt</h4>
                            <p class="text-xs text-gray-500 mt-1">Size: L | Color: Blue</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">Qty: 2</span>
                                <span class="text-sm font-semibold text-gray-900">৳1,900</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-16 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=150&h=200&fit=crop" alt="Product" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">Classic Round Neck T-Shirt</h4>
                            <p class="text-xs text-gray-500 mt-1">Size: M | Color: Black</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">Qty: 1</span>
                                <span class="text-sm font-semibold text-gray-900">৳650</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Cart Link --}}
                <a href="#" onclick="openCartDrawer(); return false;" class="text-brand-blue text-sm font-medium hover:underline mb-5 inline-block">
                    <i class="fas fa-edit mr-1"></i> Edit Cart
                </a>

                {{-- Coupon Code --}}
                <div class="mb-5">
                    <div class="flex gap-2">
                        <input type="text" name="coupon" placeholder="Coupon code" class="flex-1 h-11 px-4 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-brand-blue transition">
                        <button type="button" class="h-11 px-4 bg-gray-100 text-gray-700 rounded-xl font-medium text-sm hover:bg-gray-200 transition">Apply</button>
                    </div>
                </div>

                {{-- Price Breakdown --}}
                <div class="space-y-3 border-t border-gray-100 pt-5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Subtotal (4 items)</span>
                        <span class="font-medium text-gray-900">৳3,800</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Shipping</span>
                        <span class="font-medium text-gray-900" id="shippingCost">৳60</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-green-600">
                        <span>Discount</span>
                        <span class="font-medium">-৳0</span>
                    </div>
                    <div class="h-px bg-gray-200"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-brand-blue" id="totalAmount">৳3,860</span>
                    </div>
                </div>

                {{-- Place Order Button --}}
                <button type="submit" class="w-full mt-6 bg-brand-blue text-white py-4 rounded-xl font-semibold text-base hover:bg-blue-600 transition tap-effect shadow-lg shadow-brand-blue/25 flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    Place Order
                </button>

                {{-- Terms --}}
                <p class="text-xs text-gray-400 text-center mt-4">
                    By placing this order, you agree to our
                    <a href="#" class="text-brand-blue hover:underline">Terms & Conditions</a> and
                    <a href="#" class="text-brand-blue hover:underline">Privacy Policy</a>
                </p>

                {{-- Trust Badges --}}
                <div class="flex items-center justify-center gap-4 mt-5 pt-5 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-gray-400">
                        <i class="fas fa-shield-alt text-green-500"></i>
                        <span class="text-xs">Secure</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-400">
                        <i class="fas fa-undo text-brand-blue"></i>
                        <span class="text-xs">7 Days Return</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-400">
                        <i class="fas fa-truck text-orange-500"></i>
                        <span class="text-xs">Fast Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Payment method toggle
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('bkashInstructions').classList.add('hidden');
            document.getElementById('nagadInstructions').classList.add('hidden');

            if (this.value === 'bkash') {
                document.getElementById('bkashInstructions').classList.remove('hidden');
            } else if (this.value === 'nagad') {
                document.getElementById('nagadInstructions').classList.remove('hidden');
            }
        });
    });

    // Delivery zone shipping cost update
    document.querySelectorAll('input[name="delivery_zone"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const shippingCost = this.value === 'inside_dhaka' ? 60 : 120;
            document.getElementById('shippingCost').textContent = '৳' + shippingCost;

            // Update total
            const subtotal = 3800; // Static for now
            const total = subtotal + shippingCost;
            document.getElementById('totalAmount').textContent = '৳' + total.toLocaleString();
        });
    });

    // Copy to clipboard
    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const icon = btn.querySelector('i');
            icon.classList.remove('far', 'fa-copy');
            icon.classList.add('fas', 'fa-check');

            setTimeout(() => {
                icon.classList.remove('fas', 'fa-check');
                icon.classList.add('far', 'fa-copy');
            }, 2000);
        });
    }

    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (paymentMethod === 'bkash') {
            const trxId = document.querySelector('input[name="bkash_trx_id"]').value;
            if (!trxId) {
                alert('Please enter your bKash Transaction ID');
                return;
            }
        } else if (paymentMethod === 'nagad') {
            const trxId = document.querySelector('input[name="nagad_trx_id"]').value;
            if (!trxId) {
                alert('Please enter your Nagad Transaction ID');
                return;
            }
        }

        // Show success (in real app, submit to server)
        alert('Order placed successfully! You will receive a confirmation SMS shortly.');
    });

    // Update radio button checkmarks visibility
    document.querySelectorAll('.delivery-zone-option input, .payment-option input').forEach(input => {
        const updateCheckmark = () => {
            document.querySelectorAll('.delivery-zone-option, .payment-option').forEach(label => {
                const check = label.querySelector('.fa-check');
                const radio = label.querySelector('input[type="radio"]');
                if (check && radio) {
                    check.parentElement.classList.toggle('bg-brand-blue', radio.checked);
                    check.parentElement.classList.toggle('border-brand-blue', radio.checked);
                    check.classList.toggle('hidden', !radio.checked);
                }
            });
        };
        input.addEventListener('change', updateCheckmark);
        updateCheckmark();
    });
</script>
@endpush