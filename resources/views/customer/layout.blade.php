@extends('layouts.app')

@section('content')
    <div class="bg-light min-h-screen py-6 md:py-6">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Dashboard Header -->
            <!-- <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-black">My Account</h1>
                <p class="text-secondary-500 mt-1">Welcome back, {{auth()->user()->name }}!</p>
            </div> -->

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar Navigation -->
                <aside class="lg:w-64 flex-shrink-0">
                    <!-- Mobile Dashboard Menu Toggle -->
                    <button onclick="toggleProfileMobileMenu()"
                        class="lg:hidden w-full bg-surface rounded-xl shadow-lg shadow-secondary-200/50 p-4 mb-4 flex items-center justify-between hover:bg-light transition border border-secondary-100">
                        <span class="flex items-center gap-2 font-semibold text-black">
                            <i class="fas fa-bars text-primary-500"></i>
                            Dashboard Menu
                        </span>
                        <i class="fas fa-chevron-down text-secondary-400" id="menu-chevron"></i>
                    </button>

                    <!-- Navigation Menu -->
                    <nav id="dashboard-nav" class="hidden lg:block bg-surface rounded-xl shadow-lg shadow-secondary-200/50 overflow-hidden border border-secondary-100">
                        <div class="p-4 bg-primary text-white">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary font-bold text-lg shadow-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="font-semibold truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-primary-100 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <ul class="py-2">
                            <li>
                                <a href="{{ route('customer.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-primary-50 transition-colors {{ request()->routeIs('customer.dashboard') ? 'bg-primary-50 text-primary-500 border-r-4 border-primary-500' : 'text-secondary-600' }}">
                                    <i class="fas fa-home w-5 text-center"></i>
                                    <span class="font-medium">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('orders.index') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-primary-50 transition-colors {{ request()->routeIs('orders.*') ? 'bg-primary-50 text-primary-500 border-r-4 border-primary-500' : 'text-secondary-600' }}">
                                    <i class="fas fa-shopping-bag w-5 text-center"></i>
                                    <span class="font-medium">My Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.profile') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-primary-50 transition-colors {{ request()->routeIs('customer.profile') ? 'bg-primary-50 text-primary-500 border-r-4 border-primary-500' : 'text-secondary-600' }}">
                                    <i class="fas fa-user w-5 text-center"></i>
                                    <span class="font-medium">Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.reviews') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-primary-50 transition-colors {{ request()->routeIs('customer.reviews') ? 'bg-primary-50 text-primary-500 border-r-4 border-primary-500' : 'text-secondary-600' }}">
                                    <i class="fas fa-star w-5 text-center"></i>
                                    <span class="font-medium">My Reviews</span>
                                </a>
                            </li>
                            <li class="border-t border-secondary-200 mt-2 pt-2">
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 hover:bg-danger-50 text-danger-600 transition-colors">
                                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                        <span class="font-medium">Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <!-- Main Content Area -->
                <main class="flex-1">
                    @if(session('success'))
                        <div
                            class="bg-success-100 border-l-4 border-success-500 text-success-700 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                            <i class="fas fa-check-circle text-xl text-success-500"></i>
                            <div>
                                <p class="font-semibold">Success!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div
                            class="bg-danger-100 border-l-4 border-danger-500 text-danger-700 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                            <i class="fas fa-exclamation-circle text-xl text-danger-500"></i>
                            <div>
                                <p class="font-semibold">Error!</p>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @yield('dashboard-content')
                </main>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleProfileMobileMenu() {
                const nav = document.getElementById('dashboard-nav');
                const chevron = document.getElementById('menu-chevron');

                if (nav.classList.contains('hidden')) {
                    nav.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                } else {
                    nav.classList.add('hidden');
                    chevron.style.transform = 'rotate(0deg)';
                }
            }
        </script>
    @endpush
@endsection