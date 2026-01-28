<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartFashion - Premium Bangladeshi Clothing Brand')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-blue': '#228bcc',
                        'brand-black': '#000000',
                        'brand-gray': '#6b7280',
                        'brand-light': '#f5f7fa',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Hide scrollbar for category scroll */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Prevent horizontal overflow on mobile */
        body {
            overflow-x: hidden;
        }

        /* Slider animations */
        .slide {
            transition: opacity 0.7s ease-in-out, transform 0.7s ease-in-out;
        }

        /* Button tap effect */
        .tap-effect:active {
            transform: scale(0.97);
        }

        /* Product card hover */
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:active {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        @media (min-width: 768px) {
            .product-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            }
        }

        /* Glassmorphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Header scroll effect */
        .header-scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* Icon button hover */
        .icon-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .icon-btn:hover {
            background: linear-gradient(135deg, #228bcc15 0%, #228bcc25 100%);
            transform: translateY(-2px);
        }

        /* Logo animation */
        .logo-text {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-accent {
            background: linear-gradient(135deg, #228bcc 0%, #1a6fa8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero slide text animation */
        .slide-content {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Progress bar for slider */
        .slide-progress {
            animation: progress 5s linear;
        }

        @keyframes progress {
            from {
                width: 0%;
            }

            to {
                width: 100%;
            }
        }

        /* Floating badge animation */
        .floating-badge {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }
    </style>

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

    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/product-variant.js') }}"></script>

    @stack('scripts')

</body>

</html>