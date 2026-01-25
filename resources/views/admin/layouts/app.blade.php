<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SmartFashion Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Additional Styles --}}
    @stack('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .sidebar-link:not(.active):hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-menu {
            animation: slideDown 0.2s ease-out;
        }
    </style>
</head>

<?php
    $pendingOrdersCount = \App\Models\Order::where('status', \App\Enums\OrderStatus::PENDING)->count();
?>

<body class="bg-gray-50 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0">
            {{-- Logo --}}
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bolt text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">SmartFashion</h1>
                        <p class="text-xs text-gray-500">Admin Panel</p>
                    </div>
                </a>
                <button id="sidebarClose" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-6 px-4">
                <div class="space-y-1">
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home text-lg w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    {{-- Orders --}}
                    <div class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Sales</p>
                        <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-bag text-lg w-5"></i>
                            <span>Orders</span>
                            @if($pendingOrdersCount)
                            <span class="ml-auto bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-full">{{ $pendingOrdersCount }}</span>
                            @endif
                        </a>
                    </div>

                    {{-- Products --}}
                    <div class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Catalog</p>
                        <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="fas fa-box text-lg w-5"></i>
                            <span>Products</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags text-lg w-5"></i>
                            <span>Categories</span>
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                            <i class="fas fa-star text-lg w-5"></i>
                            <span>Reviews</span>
                        </a>
                    </div>

                    {{-- Customers --}}
                    <div class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Customers</p>
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users text-lg w-5"></i>
                            <span>Customers</span>
                        </a>
                    </div>

                    {{-- Marketing --}}
                    <div class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Marketing</p>
                        <a href="{{ route('admin.coupons.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt text-lg w-5"></i>
                            <span>Coupons</span>
                        </a>
                        <a href="{{ route('admin.banners.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                            <i class="fas fa-image text-lg w-5"></i>
                            <span>Banners</span>
                        </a>
                    </div>

                    {{-- Settings --}}
                    <div class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System</p>
                        <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="fas fa-cog text-lg w-5"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
            </nav>

            {{-- User Info --}}
            <div class="border-t border-gray-200 p-4">
                <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-gray-50">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Header --}}
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-40">
                {{-- Mobile Menu Button --}}
                <button id="sidebarToggle" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                {{-- Search Bar --}}
                <div class="flex-1 max-w-2xl mx-auto hidden md:block">
                    <div class="relative">
                        <input type="search" placeholder="Search orders, products, customers..." class="w-full h-10 pl-10 pr-4 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                {{-- Header Actions --}}
                <div class="flex items-center gap-3">
                    {{-- Notifications --}}
                    <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {{-- View Site --}}
                    <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-xl transition">
                        <i class="fas fa-external-link-alt"></i>
                        <span>View Site</span>
                    </a>

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-xl transition">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak class="dropdown-menu absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg py-2">
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-user-circle w-5"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-cog w-5"></i>
                                <span>Account Settings</span>
                            </a>
                            <div class="border-t border-gray-100 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left">
                                    <i class="fas fa-sign-out-alt w-5"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto">
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Overlay for mobile sidebar --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Sidebar Toggle Script --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }

        sidebarToggle?.addEventListener('click', openSidebar);
        sidebarClose?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);

        // Close sidebar on window resize if in desktop mode
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    </script>

    @stack('scripts')

    @include('partials.toast')
</body>

</html>