@extends('layouts.app')

@section('title', 'Track Your Order')

@section('content')
    <div class="min-h-screen bg-light py-6 md:py-10">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-primary rounded-full mb-3 shadow-lg shadow-primary-200/50">
                    <i class="fas fa-map-marked-alt text-surface-elevated text-xl"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Track Your Order</h1>
                <p class="text-sm md:text-base text-secondary-400">Enter your order number or phone to check status</p>
            </div>

            <!-- Search Card -->
            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-4 md:p-6 mb-6 border border-secondary-100">
                <form>
                    <div class="grid md:grid-cols-3 gap-4">
                        <!-- Order Number -->
                        <div>
                            <label for="order_number" class="block text-sm font-semibold text-secondary-600 mb-2">
                                <i class="fas fa-hashtag text-primary-500 mr-1"></i>Order Number
                            </label>
                            <input type="text" name="order_number" id="order_number"
                                value="{{ old('order_number') ?? request()->get('order_number') }}"
                                placeholder="e.g., SF240221001"
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-300 focus:border-primary-300 transition text-sm bg-surface text-primary placeholder-secondary-300">
                            @error('order_number')
                                <p class="mt-1 text-xs text-danger-600"><i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-secondary-600 mb-2">
                                <i class="fas fa-phone text-primary-500 mr-1"></i>Phone Number
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') ?? request()->get('phone') }}"
                                placeholder="e.g., 017XXXXXXXX"
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-300 focus:border-primary-300 transition text-sm bg-surface text-primary placeholder-secondary-300">
                            @error('phone')
                                <p class="mt-1 text-xs text-danger-600"><i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-primary text-surface-elevated py-2 px-4 rounded-xl font-semibold hover:bg-primary-700 transition shadow-lg shadow-primary-200/50 flex items-center justify-center gap-2">
                                <i class="fas fa-search"></i>
                                Track Order
                            </button>
                        </div>
                    </div>

                    <!-- Info Note -->
                    <p class="mt-3 text-xs text-secondary-400 text-center md:text-left">
                        <i class="fas fa-info-circle text-primary-500"></i>
                        Provide order number and/or phone number to search
                    </p>
                </form>
            </div>

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-danger-50 border-l-4 border-danger-500 text-danger-700 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                    <i class="fas fa-exclamation-circle text-lg text-danger-500"></i>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Order Details (shown when order is found) -->
            @isset($order)
                <div class="space-y-4">
                    <!-- Status Header -->
                    <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-4 md:p-6 border border-secondary-100">

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                            <div>
                                <p class="text-xs text-secondary-400 mb-1">Order Number</p>
                                <h2 class="text-xl md:text-2xl font-bold text-primary">
                                    #{{ $order->order_number }}
                                </h2>
                                <p class="text-xs text-secondary-400 mt-1">
                                    {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 bg-success-100 px-4 py-2 rounded-full">
                                <div class="w-2 h-2 rounded-full animate-pulse bg-success-500"></div>
                                <span class="text-sm font-bold text-success-700">
                                    {{ $order->status->label() }}
                                </span>
                            </div>

                        </div>

                        {{-- NOTES SECTION --}}
                        @if($order->notes || $order->admin_notes)
                            <div class="mt-4 pt-4 border-t border-secondary-100 flex flex-col gap-2">

                                {{-- Customer Note --}}
                                @if($order->notes)
                                    <div class="flex items-start gap-2.5 bg-light border border-secondary-100 rounded-lg px-3 py-2.5">
                                        <i class="fas fa-user text-secondary-300 text-xs mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest text-secondary-300 mb-0.5">Customer Note</p>
                                            <p class="text-xs text-secondary-500">{{ $order->notes }}</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Admin Note --}}
                                @if($order->admin_notes)
                                    <div class="flex items-start gap-2.5 bg-warning-50 border border-warning-200 rounded-lg px-3 py-2.5">
                                        <i class="fas fa-exclamation-triangle text-warning-500 text-xs mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest text-warning-500 mb-0.5">Admin Note</p>
                                            <p class="text-xs font-medium text-warning-700">{{ $order->admin_notes }}</p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endif

                    </div>

                    <!-- Grid Layout for Details -->
                    <div class="grid lg:grid-cols-3 gap-4">
                        <!-- Left Column - Shipping & Items -->
                        <div class="lg:col-span-2 space-y-4">
                            <!-- Shipping Address -->
                            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-4 md:p-5 border border-secondary-100">
                                <h3 class="text-sm font-bold text-primary mb-3 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-primary-500"></i>
                                    Shipping Address
                                </h3>
                                <div class="grid sm:grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-xs text-secondary-400">Name</p>
                                        <p class="font-medium text-primary">{{ $order->shipping_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-secondary-400">Phone</p>
                                        <p class="font-medium text-primary">{{ $order->shipping_phone }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <p class="text-xs text-secondary-400">Address</p>
                                        <p class="font-medium text-primary">{{ $order->shipping_address }},
                                            {{ $order->shipping_city }}, {{ $order?->district?->name}}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-4 md:p-5 border border-secondary-100">
                                <h3 class="text-sm font-bold text-primary mb-3 flex items-center gap-2">
                                    <i class="fas fa-box text-primary-500"></i>
                                    Order Items ({{ $order->items->count() }})
                                </h3>
                                <div class="space-y-3">
                                    @foreach($order->items as $item)
                                        <div class="flex gap-3 p-3 bg-light rounded-lg border border-secondary-100">

                                            <!-- Product Image -->
                                            <div class="w-14 h-14 sm:w-16 sm:h-16 flex-shrink-0 rounded-lg overflow-hidden bg-secondary-100 border border-secondary-100">
                                                @if($item->product && $item->product->thumbnail)
                                                    <img src="{{ $item->product->thumbnail }}"
                                                        alt="{{ $item->product_name }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-secondary-400">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">

                                                <h4 class="font-semibold text-sm text-primary leading-5 break-words">
                                                    {{ $item->product_name }}
                                                </h4>

                                                @if($item->size || $item->color)
                                                    <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-secondary-500 mt-1">
                                                        @if($item->size)
                                                            <span>Size: {{ $item->size }}</span>
                                                        @endif

                                                        @if($item->color)
                                                            <span>Color: {{ $item->color }}</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                <!-- Qty & Price -->
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mt-2">
                                                    <span class="text-xs text-secondary-500">
                                                        Qty: {{ $item->quantity }}
                                                    </span>

                                                    <span class="text-sm font-bold text-primary-500">
                                                        ৳{{ number_format($item->subtotal, 2) }}
                                                    </span>
                                                </div>

                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Timeline & Summary -->
                        <div class="space-y-4">
                            <!-- Order Summary -->
                            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-4 md:p-5 border border-secondary-100">
                                <h3 class="text-sm font-bold text-primary mb-3">Order Summary</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-secondary-500">Subtotal</span>
                                        <span class="font-medium text-primary">৳{{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-secondary-500">Shipping</span>
                                        <span class="font-medium text-primary">৳{{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                                    </div>
                                    @if($order->discount_amount > 0)
                                        <div class="flex justify-between text-success-600">
                                            <span>Discount</span>
                                            <span class="font-medium">-৳{{ number_format($order->discount_amount, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="h-px bg-secondary-200 my-2"></div>
                                    <div class="flex justify-between text-base">
                                        <span class="font-bold text-primary">Total</span>
                                        <span class="font-bold text-primary-500">৳{{ number_format($order->total, 2) }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-secondary-200">
                                    <div class="text-xs space-y-1">
                                        <div class="flex justify-between">
                                            <span class="text-secondary-400">Payment</span>
                                            <span class="font-medium text-primary">{{ $order->payment_method->label() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-400">Status</span>
                                            <span class="font-medium {{ $order->payment_status->isPending() ? 'text-warning-600' : 'text-success-600' }}">
                                                {{ $order->payment_status->label() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Timeline -->
                            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-4 md:p-5 border border-secondary-100">
                                <h3 class="text-sm font-bold text-primary mb-3 flex items-center gap-2">
                                    <i class="fas fa-clock text-primary-500"></i>
                                    Order Timeline
                                </h3>
                                <div class="space-y-4">
                                    @forelse($order->statusHistories as $history)
                                        <div class="flex gap-4">
                                            <div class="flex flex-col items-center">
                                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center shrink-0">
                                                    <i class="fas fa-circle text-primary-500 text-xs"></i>
                                                </div>
                                                @if(!$loop->last)
                                                    <div class="flex-1 w-0.5 bg-secondary-200 my-1"></div>
                                                @endif
                                            </div>
                                            <div class="flex-1 pb-4">
                                                <div class="flex items-start justify-between gap-4 mb-1">
                                                    <span class="font-semibold text-primary">{{ $history->status->label() }}</span>
                                                    <span class="text-sm text-secondary-400">{{ $history->created_at->diffForHumans() }}</span>
                                                </div>
                                                @if($history->comment)
                                                    <p class="text-sm text-secondary-500 mb-1">{{ $history->comment }}</p>
                                                @endif
                                                @if($history->updater)
                                                    <p class="text-xs text-secondary-400">By: {{ $history->updater->name }}</p>
                                                @endif
                                                <p class="text-xs text-secondary-300">
                                                    {{ $history->created_at->format('M d, Y \a\t h:i A') }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-secondary-400 text-center py-4">No status history available</p>
                                    @endforelse
                                </div>
                            </div>

                            @if($order->tracking_number || $order->courier)
                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-3 text-sm">
                                    <p class="font-semibold text-primary mb-1">Tracking Info</p>
                                    @if($order->courier)
                                        <p class="text-xs text-secondary-500">Courier: <span class="font-medium text-primary">{{ $order->courier }}</span>
                                        </p>
                                    @endif
                                    @if($order->tracking_number)
                                        <p class="text-xs text-secondary-500">Tracking: <span
                                                class="font-mono font-medium text-primary">{{ $order->tracking_number }}</span></p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-surface-elevated text-primary border-2 border-primary rounded-xl font-semibold hover:bg-primary-50 transition text-sm">
                            <i class="fas fa-shopping-bag"></i>
                            Continue Shopping
                        </a>
                        <a href="mailto:support@spinnerfashion.com"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-surface-elevated text-secondary-600 border border-secondary-200 rounded-xl font-semibold hover:bg-light transition text-sm">
                            <i class="fas fa-envelope"></i>
                            Contact Support
                        </a>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-8 md:p-12 text-center border border-secondary-100">
                    <div class="w-20 h-20 bg-light rounded-full flex items-center justify-center mx-auto mb-4 border border-secondary-100">
                        <i class="fas fa-search text-secondary-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-2">Search for Your Order</h3>
                    <p class="text-secondary-400 mb-6">Enter your order number or phone number above to track your order</p>

                    <!-- Features -->
                    <div class="grid sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
                        <div class="p-4">
                            <div class="w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-shield-alt text-primary-500"></i>
                            </div>
                            <p class="text-xs font-semibold text-primary">Secure</p>
                        </div>
                        <div class="p-4">
                            <div class="w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-bolt text-primary-500"></i>
                            </div>
                            <p class="text-xs font-semibold text-primary">Real-time</p>
                        </div>
                        <div class="p-4">
                            <div class="w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-headset text-primary-500"></i>
                            </div>
                            <p class="text-xs font-semibold text-primary">24/7 Support</p>
                        </div>
                    </div>
                </div>
            @endisset
        </div>
    </div>
@endsection