@extends('layouts.app')
@section('title', 'Payment Successful')
@section('content')

    <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-light">
        <div class="max-w-lg w-full">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-success-100 mb-6 animate-pulse shadow-lg shadow-success-100/50">
                    <svg class="h-16 w-16 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-primary mb-2">Payment Successful!</h1>
                <p class="text-secondary-400">Your order has been placed successfully.</p>
            </div>

            <!-- Order Details Card -->
            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-6 mb-6 border border-secondary-100">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-secondary-200">
                    <div>
                        <p class="text-sm text-secondary-400">Order Number</p>
                        <p class="text-lg font-semibold text-primary">#{{ $order->order_number }}</p>
                    </div>
                    <div class="bg-success-100 px-3 py-1 rounded-full border border-success-200">
                        <span class="text-sm font-medium text-success-700">Confirmed</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-success-500 mt-1 mr-3 text-sm"></i>
                        <div>
                            <p class="text-sm font-medium text-primary">Payment received</p>
                            <p class="text-xs text-secondary-400">We've received your payment successfully</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-envelope text-success-500 mt-1 mr-3 text-sm"></i>
                        <div>
                            <p class="text-sm font-medium text-primary">Confirmation email sent</p>
                            <p class="text-xs text-secondary-400">Check your email for order details</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-truck text-success-500 mt-1 mr-3 text-sm"></i>
                        <div>
                            <p class="text-sm font-medium text-primary">Order is being processed</p>
                            <p class="text-xs text-secondary-400">We'll notify you when it ships</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Redirect Message -->
            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-primary-500 mr-3"></i>
                    <p class="text-sm text-secondary-600">
                        Redirecting in <span id="countdown" class="font-bold text-primary-500">3.5</span> seconds...
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                @auth
                    <a href="{{ route('orders.index') }}"
                        class="flex-1 bg-primary text-surface-elevated py-3 px-6 rounded-xl font-medium hover:bg-primary-700 transition text-center shadow-lg shadow-primary-200/50">
                        <i class="fas fa-shopping-bag mr-2"></i>View Orders
                    </a>
                @else
                    <a href="{{ route('home') }}"
                        class="flex-1 bg-primary text-surface-elevated py-3 px-6 rounded-xl font-medium hover:bg-primary-700 transition text-center shadow-lg shadow-primary-200/50">
                        <i class="fas fa-home mr-2"></i>Go to Home
                    </a>
                @endauth
                <a href="{{ route('products.index') }}"
                    class="flex-1 border-2 border-primary text-primary py-3 px-6 rounded-xl font-medium hover:bg-primary hover:text-surface-elevated transition text-center">
                    <i class="fas fa-shopping-cart mr-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let timeLeft = 3.5;
            const countdownElement = document.getElementById('countdown');
            const redirectUrl = "<?php echo auth()->check() ? route('orders.index') : route('track-order.index', ['order_number' => $order->order_number]); ?>";

            const timer = setInterval(() => {
                timeLeft -= 0.1;
                countdownElement.textContent = Math.max(0, timeLeft).toFixed(1);

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    window.location.href = redirectUrl;
                }
            }, 100);
        </script>
    @endpush

@endsection