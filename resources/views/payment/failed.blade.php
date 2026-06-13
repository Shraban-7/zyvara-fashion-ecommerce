@extends('layouts.app')
@section('title', 'Payment Failed')
@section('content')

    <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-light">
        <div class="max-w-lg w-full">
            <!-- Error Icon -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-danger-100 mb-6 animate-pulse shadow-lg shadow-danger-100/50">
                    <svg class="h-16 w-16 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-black mb-2">Payment Failed</h1>
                <p class="text-secondary-400">We couldn't process your payment.</p>
            </div>

            <!-- Error Details Card -->
            <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-6 mb-6 border border-secondary-100">
                <div class="bg-danger-50 border border-danger-200 rounded-xl p-4 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-danger-500 mt-1 mr-3 text-sm"></i>
                        <div>
                            <p class="text-sm font-medium text-danger-700">Transaction Declined</p>
                            <p class="text-xs text-danger-500 mt-1">Your payment could not be processed at this time</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-medium text-black mb-2">Common reasons for payment failure:</p>
                    <div class="flex items-start">
                        <i class="fas fa-circle text-danger-500 text-xs mt-1.5 mr-3"></i>
                        <p class="text-sm text-secondary-600">Insufficient funds in account</p>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-circle text-danger-500 text-xs mt-1.5 mr-3"></i>
                        <p class="text-sm text-secondary-600">Incorrect payment details</p>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-circle text-danger-500 text-xs mt-1.5 mr-3"></i>
                        <p class="text-sm text-secondary-600">Card limit exceeded</p>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-circle text-danger-500 text-xs mt-1.5 mr-3"></i>
                        <p class="text-sm text-secondary-600">Bank security check failed</p>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-circle text-danger-500 text-xs mt-1.5 mr-3"></i>
                        <p class="text-sm text-secondary-600">Network or connection issues</p>
                    </div>
                </div>
            </div>

            <!-- What to do next -->
            <div class="bg-primary-50 rounded-xl p-4 mb-6 border border-primary-100">
                <p class="text-sm font-medium text-black mb-3">
                    <i class="fas fa-question-circle text-primary-500 mr-2"></i>What should you do?
                </p>
                <div class="space-y-2 text-xs text-secondary-500">
                    <p>✓ Verify your payment information</p>
                    <p>✓ Check your account balance</p>
                    <p>✓ Contact your bank if needed</p>
                    <p>✓ Try a different payment method</p>
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
                <a href="{{ route('checkout.index') }}"
                    class="flex-1 bg-primary text-white py-3 px-6 rounded-xl font-medium hover:bg-primary-700 transition text-center shadow-lg shadow-primary-200/50">
                    <i class="fas fa-redo mr-2"></i>Try Again
                </a>
                @auth
                    <a href="{{ route('orders.index') }}"
                        class="flex-1 border-2 border-primary text-primary py-3 px-6 rounded-xl font-medium hover:bg-primary hover:text-white transition text-center">
                        <i class="fas fa-shopping-bag mr-2"></i>View Orders
                    </a>
                @else
                    <a href="{{ route('home') }}"
                        class="flex-1 border-2 border-primary text-primary py-3 px-6 rounded-xl font-medium hover:bg-primary hover:text-white transition text-center">
                        <i class="fas fa-home mr-2"></i>Go to Home
                    </a>
                @endauth
            </div>

            <!-- Support Contact -->
            <div class="mt-6 text-center">
                <p class="text-sm text-secondary-500">
                    Need help? <a href="#" class="text-primary-500 font-medium hover:text-primary-700 hover:underline transition-colors">Contact Support</a>
                </p>
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