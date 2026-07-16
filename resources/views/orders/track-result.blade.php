@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
    <div class="min-h-screen bg-light py-8 md:py-12">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Back Button & Header -->
            <div class="mb-8">
                <a href="{{ route('track-order.index') }}"
                    class="inline-flex items-center gap-2 text-primary-500 hover:text-primary-700 font-medium mb-4 transition-colors">
                    <i class="fas fa-arrow-left"></i> Track Another Order
                </a>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-secondary-400 mb-1">Order Number</p>
                        <h1 class="text-3xl md:text-4xl font-bold text-primary">#{{ $order->order_number }}</h1>
                    </div>
                    <div class="flex items-center gap-2 bg-success-100 px-4 py-3 rounded-full">
                        <div class="w-3 h-3 rounded-full animate-pulse bg-success-500"></div>
                        <span class="text-sm font-bold text-success-700">{{ $order->status->label() }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-6 md:p-8 mb-6 border border-secondary-100">
                <!-- Order Date & Info -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-secondary-200">
                    <div>
                        <p class="text-sm text-secondary-400 mb-1">Order Date</p>
                        <p class="text-lg font-semibold text-primary">{{ $order->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-secondary-400 mb-1">Estimated Delivery</p>
                        <p class="text-lg font-semibold text-primary">
                            @if($order->status->label() === 'Delivered')
                                {{ $order->delivered_at ? $order->delivered_at->format('F d, Y') : 'Delivered' }}
                            @else
                                {{ $order->created_at->addDays(5)->format('F d, Y') }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="py-6 border-b border-secondary-200">
                    <h3 class="text-sm font-bold text-primary mb-4 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fas fa-map-marker-alt text-primary-500 text-lg"></i>
                        Delivery Address
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-secondary-400 mb-1">Recipient Name</p>
                                <p class="font-medium text-primary">{{ $order->shipping_name }}</p>
                            </div>
                            <div>
                                <p class="text-secondary-400 mb-1">Phone Number</p>
                                <p class="font-medium text-primary">{{ $order->shipping_phone }}</p>
                            </div>
                            @if($order->shipping_email)
                                <div>
                                    <p class="text-secondary-400 mb-1">Email</p>
                                    <p class="font-medium text-primary">{{ $order->shipping_email }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-secondary-400 mb-1">Address</p>
                                <p class="font-medium text-primary">{{ $order->shipping_address }}</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="font-medium text-primary">{{ $order->shipping_city }},</span>
                                <span class="font-medium text-primary">{{ $order->shipping_district }}</span>
                            </div>
                            @if($order->shipping_postal_code)
                                <div>
                                    <p class="text-secondary-400 mb-1">Postal Code</p>
                                    <p class="font-medium text-primary">{{ $order->shipping_postal_code }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="py-6 border-b border-secondary-200">
                    <h3 class="text-sm font-bold text-primary mb-4 flex items-center gap-2 uppercase tracking-wide">
                        <i class="fas fa-box text-primary-500 text-lg"></i>
                        Order Items ({{ $order->items ? count($order->items) : 0 }})
                    </h3>
                    <div class="space-y-4">
                        @if($order->items && count($order->items) > 0)
                            @foreach($order->items as $item)
                                <div class="flex gap-4 p-4 bg-light rounded-xl border border-secondary-100 hover:bg-secondary-50 transition">
                                    <div class="w-24 h-28 flex-shrink-0 rounded-lg overflow-hidden bg-secondary-100 border border-secondary-100">
                                        @if($item->product && $item->product->thumbnail)
                                            <img src="{{ $item->product->thumbnail }}" alt="{{ $item->product->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-secondary-400">
                                                <i class="fas fa-image text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-primary mb-2">{{ $item->product_name }}</h4>
                                        <div class="space-y-1 text-xs text-secondary-500 mb-3">
                                            @if($item->size)
                                                <p><span class="font-medium">Size:</span> {{ $item->size }}</p>
                                            @endif
                                            @if($item->color)
                                                <p><span class="font-medium">Color:</span> {{ $item->color }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-secondary-500">Qty:
                                                <strong class="text-primary">{{ $item->quantity }}</strong></span>
                                            <span class="text-lg font-bold text-primary">৳{{ number_format($item->subtotal, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-secondary-400 text-center py-4">No items found for this order</p>
                        @endif
                    </div>
                </div>

                <!-- Payment & Price Summary -->
                <div class="py-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Payment Info -->
                        <div>
                            <h3 class="text-sm font-bold text-primary mb-4 flex items-center gap-2 uppercase tracking-wide">
                                <i class="fas fa-credit-card text-primary-500 text-lg"></i>
                                Payment Information
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-secondary-400 mb-1">Payment Method</p>
                                    <span class="inline-block bg-light text-secondary-600 px-3 py-1 rounded-lg text-xs font-semibold border border-secondary-200">
                                        {{ $order->payment_method->label() }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-secondary-400 mb-1">Payment Status</p>
                                    @php
                                        $paymentStatusColor = $order->payment_status->isPending() 
                                            ? 'bg-warning-100 text-warning-700 border-warning-200' 
                                            : 'bg-success-100 text-success-700 border-success-200';
                                    @endphp
                                    <span class="inline-block {{ $paymentStatusColor }} px-3 py-1 rounded-lg text-xs font-semibold border">
                                        {{ $order->payment_status->label() }}
                                    </span>
                                </div>
                                @if($order->tracking_number)
                                    <div>
                                        <p class="text-secondary-400 mb-1">Tracking Number</p>
                                        <p class="font-mono font-medium text-primary">{{ $order->tracking_number }}</p>
                                    </div>
                                @endif
                                @if($order->courier)
                                    <div>
                                        <p class="text-secondary-400 mb-1">Courier</p>
                                        <p class="font-medium text-primary">{{ $order->courier }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="bg-primary-50 rounded-xl p-4 border border-primary-100">
                            <h3 class="text-sm font-bold text-primary mb-4">Order Summary</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-secondary-500">Subtotal</span>
                                    <span class="font-medium text-primary">৳{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-secondary-500">Shipping</span>
                                    <span class="font-medium text-primary">৳{{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                                </div>
                                @if($order->discount_amount && $order->discount_amount > 0)
                                    <div class="flex justify-between text-success-600">
                                        <span>Discount</span>
                                        <span class="font-medium">-৳{{ number_format($order->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                @if($order->tax_amount && $order->tax_amount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-secondary-500">Tax</span>
                                        <span class="font-medium text-primary">৳{{ number_format($order->tax_amount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="h-px bg-secondary-200 my-3"></div>
                                <div class="flex justify-between text-lg">
                                    <span class="font-bold text-primary">Total</span>
                                    <span class="font-bold text-primary-500">৳{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-6 md:p-8 mb-6 border border-secondary-100">
                <h2 class="text-xl font-bold text-primary mb-6 flex items-center gap-2">
                    <i class="fas fa-clock text-primary-500"></i>
                    Order Timeline
                </h2>
                <div class="space-y-4">
                    <!-- Order Placed -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-success-500 rounded-full border-4 border-success-100"></div>
                            <div class="w-1 h-12 bg-secondary-200 mt-2"></div>
                        </div>
                        <div class="pb-8">
                            <p class="font-semibold text-primary">Order Placed</p>
                            <p class="text-sm text-secondary-400">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @php
                        $statusSteps = [
                            'confirmed' => ['label' => 'Order Confirmed', 'date' => $order->confirmed_at, 'icon' => 'check-circle'],
                            'processing' => ['label' => 'Processing', 'date' => null, 'icon' => 'cog'],
                            'shipped' => ['label' => 'Shipped', 'date' => $order->shipped_at, 'icon' => 'shipping-fast'],
                            'delivered' => ['label' => 'Delivered', 'date' => $order->delivered_at, 'icon' => 'box-check']
                        ];

                        $currentStatusValue = $order->status->value;
                        $statusOrder = ['pending' => 0, 'confirmed' => 1, 'processing' => 2, 'shipped' => 3, 'delivered' => 4, 'cancelled' => -1];
                        $currentIndex = $statusOrder[$currentStatusValue] ?? 0;
                    @endphp

                    @foreach($statusSteps as $key => $step)
                        @php
                            $stepIndex = $statusOrder[$key] ?? 0;
                            $isCompleted = $currentIndex >= $stepIndex;
                            $isCurrent = $currentStatusValue === $key;
                            $isLast = $loop->last;
                        @endphp
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-4 h-4 rounded-full border-4 {{ $isCompleted ? 'bg-success-500 border-success-100' : ($isCurrent ? 'bg-warning-500 border-warning-100' : 'bg-secondary-300 border-secondary-200') }}">
                                </div>
                                @if(!$isLast)
                                    <div class="w-1 h-12 {{ $isCompleted ? 'bg-success-300' : 'bg-secondary-200' }} mt-2"></div>
                                @endif
                            </div>
                            <div class="{{ !$isLast ? 'pb-8' : '' }}">
                                <p class="font-semibold {{ $isCompleted || $isCurrent ? 'text-primary' : 'text-secondary-400' }}">
                                    {{ $step['label'] }}
                                    @if($isCurrent)
                                        <span class="ml-2 text-xs bg-warning-100 text-warning-700 px-2 py-1 rounded-full border border-warning-200">In Progress</span>
                                    @endif
                                </p>
                                @if($isCompleted && $step['date'])
                                    <p class="text-sm text-secondary-400">{{ $step['date']->format('M d, Y \a\t g:i A') }}</p>
                                @elseif($isCompleted)
                                    <p class="text-sm text-secondary-400">Completed</p>
                                @else
                                    <p class="text-sm text-secondary-300">Pending</p>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Cancelled Status (if applicable) -->
                    @if($order->status->value === 'cancelled')
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-4 h-4 bg-danger-500 rounded-full border-4 border-danger-100"></div>
                            </div>
                            <div>
                                <p class="font-semibold text-danger-600">Order Cancelled</p>
                                @if($order->cancelled_at)
                                    <p class="text-sm text-secondary-400">{{ $order->cancelled_at->format('M d, Y \a\t g:i A') }}</p>
                                @endif
                                @if($order->cancellation_reason)
                                    <p class="text-sm text-secondary-500 mt-1">Reason: {{ $order->cancellation_reason }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notes (if any) -->
            @if($order->notes)
                <div class="bg-warning-50 border-l-4 border-warning-400 rounded-xl p-6 mb-6 shadow-sm">
                    <h3 class="font-bold text-primary mb-2 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-warning-500"></i>
                        Order Notes
                    </h3>
                    <p class="text-secondary-600">{{ $order->notes }}</p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('track-order.index') }}"
                    class="flex items-center justify-center gap-2 px-8 py-3 bg-primary text-surface-elevated rounded-xl font-semibold hover:bg-primary-700 transition shadow-lg shadow-primary-200/50">
                    <i class="fas fa-search"></i>
                    Track Another Order
                </a>
                <a href="{{ route('home') }}"
                    class="flex items-center justify-center gap-2 px-8 py-3 bg-surface-elevated text-primary border-2 border-primary rounded-xl font-semibold hover:bg-primary-50 transition">
                    <i class="fas fa-shopping-bag"></i>
                    Continue Shopping
                </a>
            </div>

            <!-- Support Section -->
            <div class="text-center mt-8 text-sm bg-primary-50 rounded-xl p-4 border border-primary-100">
                <p class="text-secondary-500 mb-2">Have questions about your order?</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="mailto:support@spinnerfashion.com" class="text-primary-500 hover:text-primary-700 hover:underline font-semibold transition-colors">
                        <i class="fas fa-envelope mr-1"></i>
                        Email Support
                    </a>
                    <span class="hidden sm:inline text-secondary-300">|</span>
                    <a href="tel:+8801712345678" class="text-primary-500 hover:text-primary-700 hover:underline font-semibold transition-colors">
                        <i class="fas fa-phone mr-1"></i>
                        Call Us
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection