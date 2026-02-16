<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ $siteName }}</title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Styles -->
    

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-brand-light min-h-screen pb-20 md:pb-0">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.mobile-nav')
    @include('partials.search-modal')
    @include('partials.cart-drawer')
    @include('components.product-quick-view-modal')

    {{-- Floating WhatsApp Button --}}
    <a href="https://wa.me/8801712345678" target="_blank" class="fixed bottom-40 md:bottom-24 right-4 md:right-8 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 transition tap-effect z-40 animate-bounce" style="animation-duration: 2s;">
        <i class="fab fa-whatsapp text-3xl text-white"></i>
    </a>

    @include('partials.auth-modal')
    @include('partials.scripts')

    @include('partials.toast')

    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/product-variant.js') }}"></script>

    @stack('scripts')

</body>

</html>