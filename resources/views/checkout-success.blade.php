@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="min-h-screen bg-light py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4">
        {{-- Success Message --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-accent rounded-full mb-4 shadow-lg shadow-green-500/30 animate-bounce">
                <i class="fas fa-check text-surface-elevated text-3xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-primary mb-2">Order Confirmed!</h1>
            <p class="text-secondary text-lg">Thank you for your purchase</p>
        </div>

        {{-- Order Details Card --}}
        <div class="bg-surface-elevated rounded-2xl shadow-lg shadow-primary/5 border border-primary-100 p-6 md:p-8 mb-6">
            {{-- Order Number & Status --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-primary-100">
                <div>
                    <p class="text-sm text-secondary mb-1">Order Number</p>
                    <h2 class="text-2xl font-black text-primary">{{ $order->order_number }}</h2>
                </div>
                <div class="flex items-center gap-2 bg-warning-50 px-4 py-2 rounded-full border border-warning-100">
                    <div class="w-2 h-2 bg-warning-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-bold text-warning-700">{{ $order->status->label() }}</span>
                </div>
            </div>

            {{-- Order Info --}}
            <div class="grid md:grid-cols-2 gap-6 py-6 border-b border-primary-100">
                <div>
                    <h3 class="text-sm font-bold text-primary mb-3 flex items-center gap-2">
                        <i class="fas fa-user text-primary"></i>
                        Customer Information
                    </h3>
                    <div class="space-y-2 text-sm text-secondary">
                        <p><span class="font-semibold text-primary">Name:</span> {{ $order->shipping_name }}</p>
                        <p><span class="font-semibold text-primary">Phone:</span> {{ $order->shipping_phone }}</p>
                        @if($order->shipping_email)
                        <p><span class="font-semibold text-primary">Email:</span> {{ $order->shipping_email }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-primary mb-3 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        Delivery Address
                    </h3>
                    <div class="text-sm text-secondary">
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_district }}</p>
                        <p class="mt-2">
                            <span class="inline-flex items-center gap-1 bg-primary-50 text-primary px-3 py-1 rounded-full text-xs font-bold border border-primary-100">
                                <i class="fas fa-truck text-[10px]"></i>
                                {{ $order->delivery_zone->label() }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="py-6 border-b border-primary-100">
                <h3 class="text-sm font-bold text-primary mb-4 flex items-center gap-2">
                    <i class="fas fa-box text-primary"></i>
                    Order Items ({{ $order->items->count() }})
                </h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4">
                        <div class="w-20 h-24 flex-shrink-0 rounded-lg overflow-hidden bg-light border border-primary-100">
                            <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-primary mb-1">{{ $item->product_name }}</h4>
                            @if($item->size_name || $item->color_name)
                            <p class="text-xs text-secondary mb-2">
                                @if($item->size_name)Size: {{ $item->size_name }}@endif
                                @if($item->size_name && $item->color_name) | @endif
                                @if($item->color_name)Color: {{ $item->color_name }}@endif
                            </p>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-secondary">Qty: {{ $item->quantity }}</span>
                                <span class="font-bold text-primary">{{ money($item->subtotal) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment & Price Summary --}}
            <div class="py-6">
                <div class="flex flex-col md:flex-row gap-6">
                    {{-- Payment Info --}}
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-primary mb-3 flex items-center gap-2">
                            <i class="fas fa-credit-card text-primary"></i>
                            Payment Information
                        </h3>
                        <div class="space-y-2 text-sm">
                            <p>
                                <span class="font-semibold text-primary">Method:</span>
                                <span class="ml-2 inline-flex items-center gap-1 bg-light text-primary px-3 py-1 rounded-full text-xs font-bold border border-primary-100">
                                    {{ $order->payment_method->label() }}
                                </span>
                            </p>
                            <p>
                                <span class="font-semibold text-primary">Status:</span>
                                <span class="ml-2 inline-flex items-center gap-1 {{ $order->payment_status->value === 'paid' ? 'bg-accent-50 text-accent-700 border border-accent-100' : 'bg-warning-50 text-warning-700 border border-warning-100' }} px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $order->payment_status->label() }}
                                </span>
                            </p>
                            @if($order->transaction_id)
                            <p><span class="font-semibold text-primary">Transaction ID:</span> <code class="ml-2 bg-light px-3 py-1 rounded text-xs text-primary border border-primary-100">{{ $order->transaction_id }}</code></p>
                            @endif
                        </div>
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="flex-1 bg-light rounded-xl p-5 border border-primary-100">
                        <h3 class="text-sm font-bold text-primary mb-3">Order Summary</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-secondary">Subtotal</span>
                                <span class="font-semibold text-primary">{{ money($order->subtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-secondary">Shipping</span>
                                <span class="font-semibold text-primary">{{ money($order->shipping_cost) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="flex justify-between text-accent-600">
                                <span class="font-semibold">Discount</span>
                                <span class="font-bold">-{{ money($order->discount_amount) }}</span>
                            </div>
                            @endif
                            <div class="h-px bg-primary-100 my-2"></div>
                            <div class="flex justify-between text-lg">
                                <span class="font-bold text-primary">Total</span>
                                <span class="font-black text-primary">{{ money($order->total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->notes)
            {{-- Order Notes --}}
            <div class="pt-6 border-t border-primary-100">
                <h3 class="text-sm font-bold text-primary mb-2 flex items-center gap-2">
                    <i class="fas fa-sticky-note text-primary"></i>
                    Order Notes
                </h3>
                <p class="text-sm text-secondary bg-light p-4 rounded-xl border border-primary-100">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        {{-- What's Next Section --}}
        <div class="bg-surface-elevated rounded-2xl shadow-lg shadow-primary/5 border border-primary-100 p-6 md:p-8 mb-6">
            <h2 class="text-xl font-bold text-primary mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-primary"></i>
                What Happens Next?
            </h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="flex gap-3">
                    <div class="w-10 h-10 bg-primary-50 text-primary rounded-full flex items-center justify-center flex-shrink-0 border border-primary-100">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary mb-1">Order Confirmation</h4>
                        <p class="text-sm text-secondary">We'll call you shortly to confirm your order details</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-10 h-10 bg-primary-50 text-primary rounded-full flex items-center justify-center flex-shrink-0 border border-primary-100">
                        <i class="fas fa-box text-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary mb-1">Order Processing</h4>
                        <p class="text-sm text-secondary">We'll prepare and pack your items carefully</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-10 h-10 bg-primary-50 text-primary rounded-full flex items-center justify-center flex-shrink-0 border border-primary-100">
                        <i class="fas fa-shipping-fast text-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary mb-1">Delivery</h4>
                        <p class="text-sm text-secondary">Your order will be delivered within {{ $order->delivery_zone->value === 'inside_dhaka' ? '1-2 days' : '3-5 days' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 px-8 py-3 bg-primary text-surface-elevated rounded-xl font-bold hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-xl shadow-primary/20">
                <i class="fas fa-home"></i>
                Continue Shopping
            </a>
            @auth
            <a href="#" class="flex items-center justify-center gap-2 px-8 py-3 bg-surface-elevated text-primary border-2 border-primary rounded-xl font-bold hover:bg-primary-50 transition-all duration-200">
                <i class="fas fa-receipt"></i>
                View My Orders
            </a>
            @endauth
        </div>

        {{-- Support --}}
        <div class="text-center mt-8 text-sm text-secondary">
            <p>Need help? Contact us at <a href="tel:+8801234567890" class="text-primary hover:text-secondary font-bold transition-colors duration-200">+880 1234-567890</a></p>
        </div>
    </div>
</div>
@endsection