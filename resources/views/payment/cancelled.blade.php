@extends('layouts.app')
@section('title', 'Payment Cancelled')
@section('content')

    <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-light">
        <div class="max-w-lg w-full">
            <!-- Warning Icon -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-warning-100 mb-6 animate-pulse shadow-lg shadow-warning-100/50">
                    <svg class="h-16 w-16 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-primary mb-2">Payment Cancelled</h1>
                <p class="text-secondary-400">You have cancelled the payment process.</p>
            </div>

            <!-- Information Card -->
            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-6 mb-6 border border-secondary-100">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="fas fa-times-circle text-warning-500 mt-1 mr-3 text-sm"></i>
                        <div>
                            <p class="text-sm font-medium text-primary">Payment was not processed</p>
                            <p class="text-xs text-secondary-400">No charges were made to your account</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-shopping-cart text-warning-500 mt-1 mr-3 text-sm"></i>
                        <div>
                            <p class="text-sm font-medium text-primary">Your cart is still saved</p>
                            <p class="text-xs text-secondary-400">You can complete your purchase anytime</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-headset text-warning-500 mt-1 mr-3 text-sm"></i>
                        <div>
                            <p class="text-sm font-medium text-primary">Need help?</p>
                            <p class="text-xs text-secondary-400">Contact our support team for assistance</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Text -->
            <div class="bg-warning-50 rounded-xl p-4 mb-6 border border-warning-200">
                <p class="text-sm text-secondary-600 mb-2">
                    <i class="fas fa-lightbulb text-warning-500 mr-2"></i>
                    <span class="font-medium">Why did this happen?</span>
                </p>
                <ul class="text-xs text-secondary-500 space-y-1 ml-6">
                    <li>• You clicked the cancel or back button</li>
                    <li>• Payment window was closed</li>
                    <li>• Session timeout</li>
                </ul>
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
                <a href="{{ route('checkout.index') }}"
                    class="flex-1 bg-primary text-surface-elevated py-3 px-6 rounded-xl font-medium hover:bg-primary-700 transition text-center shadow-lg shadow-primary-200/50">
                    <i class="fas fa-redo mr-2"></i>Try Again
                </a>
                @auth
                    <a href="{{ route('orders.index') }}"
                        class="flex-1 border-2 border-primary text-primary py-3 px-6 rounded-xl font-medium hover:bg-primary hover:text-surface-elevated transition text-center">
                        <i class="fas fa-shopping-bag mr-2"></i>View Orders
                    </a>
                @else
                    <a href="{{ route('home') }}"
                        class="flex-1 border-2 border-primary text-primary py-3 px-6 rounded-xl font-medium hover:bg-primary hover:text-surface-elevated transition text-center">
                        <i class="fas fa-home mr-2"></i>Go to Home
                    </a>
                @endauth
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let timeLeft = 3.5;
            const countdownElement = document.getElementById('countdown');
            const redirectUrl = "<?php echo auth()->check() ? route('orders.index') : route('home'); ?>";

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