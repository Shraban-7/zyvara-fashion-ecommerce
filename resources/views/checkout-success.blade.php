@extends('layouts.app')

@section('title', 'Order Placed Successfully - SmartFashion')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10 md:py-16">
    <div class="bg-white rounded-2xl p-6 md:p-10 border border-gray-100 shadow-sm text-center">
        {{-- Success Animation --}}
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce" style="animation-duration: 1s; animation-iteration-count: 2;">
            <i class="fas fa-check text-green-500 text-5xl"></i>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-brand-black mb-3">Order Placed Successfully!</h1>
        <p class="text-gray-500 mb-6">Thank you for shopping with us. Your order has been received and is being processed.</p>

        {{-- Order Details --}}
        <div class="bg-gray-50 rounded-xl p-5 mb-6 text-left">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Order Number</p>
                    <p class="font-bold text-brand-blue text-lg">#SF2026011901</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Order Date</p>
                    <p class="font-semibold text-gray-900">{{ date('d M, Y - h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Payment Method</p>
                    <p class="font-semibold text-gray-900">Cash on Delivery</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Total Amount</p>
                    <p class="font-bold text-green-600 text-lg">৳3,860</p>
                </div>
            </div>
        </div>

        {{-- What's Next --}}
        <div class="bg-blue-50 rounded-xl p-5 mb-6 text-left">
            <h3 class="font-semibold text-brand-blue mb-3 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                What happens next?
            </h3>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-start gap-3">
                    <i class="fas fa-sms text-brand-blue mt-0.5"></i>
                    <span>You will receive an <strong>SMS confirmation</strong> on your phone shortly.</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-phone-alt text-brand-blue mt-0.5"></i>
                    <span>Our team will <strong>call you</strong> to confirm the order within 24 hours.</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-truck text-brand-blue mt-0.5"></i>
                    <span>Your order will be <strong>delivered within 1-5 business days</strong> depending on your location.</span>
                </li>
            </ul>
        </div>

        {{-- Contact Support --}}
        <div class="bg-gray-50 rounded-xl p-4 mb-6 flex flex-col sm:flex-row items-center justify-center gap-4">
            <span class="text-sm text-gray-600">Need help with your order?</span>
            <div class="flex items-center gap-3">
                <a href="tel:+8801712345678" class="flex items-center gap-2 text-sm text-brand-blue font-medium hover:underline">
                    <i class="fas fa-phone-alt"></i>
                    01712-345678
                </a>
                <a href="https://wa.me/8801712345678" target="_blank" class="flex items-center gap-2 text-sm text-green-600 font-medium hover:underline">
                    <i class="fab fa-whatsapp"></i>
                    WhatsApp
                </a>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('home') }}" class="flex-1 bg-brand-blue text-white py-3.5 rounded-xl font-semibold text-sm hover:bg-blue-600 transition tap-effect flex items-center justify-center gap-2">
                <i class="fas fa-home"></i>
                Back to Home
            </a>
            <a href="{{ route('products.index') }}" class="flex-1 border border-gray-200 text-gray-700 py-3.5 rounded-xl font-semibold text-sm hover:bg-gray-50 transition tap-effect flex items-center justify-center gap-2">
                <i class="fas fa-shopping-bag"></i>
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection