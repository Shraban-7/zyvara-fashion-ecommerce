{{-- Sticky Header --}}
<header id="mainHeader"
    class="sticky top-0 z-50 bg-white/95 backdrop-blur-xl border-b border-gray-100/60 transition-all duration-300">

    {{-- ============================================
         MAIN NAVIGATION
         ============================================ --}}
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="flex items-center justify-between h-16 md:h-20">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-1 group">
                @if ($settings['site_logo'])
                    <img src="{{ storage_url($settings['site_logo']) }}" alt="logo" class="h-12 md:h-14">
                @else
                    <?php
                    $siteNames = null;
                    $siteName = $settings['site_name'] ?? null;
                    if ($siteName) {
                        $siteNames = explode(' ', $siteName);
                    }
                    ?>
                    @if ($siteNames)
                        <div class="relative">
                            <span
                                class="text-2xl md:text-3xl font-black text-primary tracking-tight">{{ $siteNames[0] }}</span>
                            @if (isset($siteNames[1]))
                                <span
                                    class="text-2xl md:text-3xl font-black text-secondary-600 tracking-tight">{{ $siteNames[1] }}</span>
                            @endif
                            <div
                                class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-300">
                            </div>
                        </div>
                    @endif
                @endif
            </a>

            {{-- Desktop Search Bar --}}
            <div class="hidden md:flex flex-1 max-w-xl mx-8">
                <button onclick="openSearch()" class="relative w-full group text-left">
                    <div
                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 pl-12 pr-6 text-sm text-gray-400 hover:border-gray-300 hover:bg-white hover:shadow-sm transition-all duration-300">
                        Search for products, brands and more...
                    </div>
                    <div
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-primary transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <span
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-gray-100 text-gray-500 px-2 py-1 rounded-lg text-[11px] font-semibold border border-gray-200">
                        Ctrl+K
                    </span>
                </button>
            </div>

            {{-- Right Icons --}}
            <div class="flex items-center gap-1 md:gap-2">

                {{-- Search - Mobile --}}
                <button onclick="openSearch()" class="md:hidden p-2.5 rounded-xl hover:bg-gray-100 transition-colors"
                    aria-label="Search">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                {{-- Wishlist --}}
                <button onclick="openWishlist()"
                    class="p-2.5 rounded-xl flex items-center gap-2 group hover:bg-gray-100 transition-colors relative"
                    aria-label="Wishlist">
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-red-500 transition-colors" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold"
                            id="headerWishlistCount">0</span>
                    </div>
                </button>

                {{-- Cart --}}
                <button class="p-2.5 rounded-xl flex items-center gap-2 group hover:bg-gray-100 transition-colors"
                    aria-label="Cart" onclick="openCartDrawer()">
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary transition-colors" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span
                            class="absolute -top-2 -right-2 bg-primary text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold"
                            id="headerCartCount"></span>
                    </div>
                    <div class="hidden lg:flex flex-col items-start">
                        <span class="text-sm font-bold text-gray-900" id="headerCartTotal"></span>
                    </div>
                </button>

                {{-- Divider --}}
                <div class="hidden md:block w-px h-8 bg-gray-200 mx-1"></div>

                {{-- ============================================
                     PROFILE SECTION - UPDATED DESIGN
                     ============================================ --}}
                @auth
                    <?php
                    $user = auth()->user();
                    $isAdmin = $user->role == \App\Enums\UserRole::CUSTOMER ? false : true;
                    ?>
                    <div class="relative" id="profileDropdown">
                        <button onclick="toggleProfileDropdown(event)"
                            class="flex items-center gap-2 px-2 py-1.5 rounded-full bg-gray-50 hover:bg-gray-100 border border-gray-200 hover:border-gray-300 transition-all duration-200"
                            aria-label="Profile">

                            <div
                                class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            {{-- Desktop --}}
                            <div class="hidden lg:flex items-center gap-1.5">
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ Str::limit($user->name, 10) }}
                                </span>

                                <svg id="profileArrow" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            {{-- Mobile --}}
                            <svg class="w-4 h-4 text-gray-400 lg:hidden" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="profileDropdownMenu"
                            class="absolute right-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible scale-95 origin-top-right transition-all duration-200 z-[60] overflow-hidden">
                            <div class="bg-white px-5 py-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->phone ?? $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                @if ($isAdmin)
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Dashboard</span>
                                    </a>
                                @else
                                    <a href="{{ route('customer.dashboard') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Dashboard</span>
                                    </a>
                                    <a href="{{ route('customer.profile') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition-colors">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">My Profile</span>
                                    </a>
                                    <a href="{{ route('orders.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">My Orders</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center group-hover:bg-orange-100 transition-colors">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Settings</span>
                                    </a>
                                @endif
                            </div>
                            <div class="border-t border-gray-100 p-2">
                                <form action="{{ route('auth.logout') }}" method="POST" id="logoutForm">
                                    @csrf
                                    <button type="button"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 transition-colors w-full text-left group"
                                        onclick="document.getElementById('logoutForm').submit()">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center group-hover:bg-red-100 transition-colors">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-red-600">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <button onclick="openAuthModal('login')"
                            class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-primary text-white text-sm font-semibold hover:bg-primary-700 shadow-sm shadow-primary/20 hover:shadow-md transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login
                        </button>
                        <button onclick="openAuthModal('login')"
                            class="flex sm:hidden items-center justify-center w-10 h-10 rounded-xl bg-primary text-white"
                            aria-label="Login">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </div>
                @endauth

            </div>
        </div>
    </div>

    {{-- ============================================
         DESKTOP NAVIGATION MENU - Bottom Bar
         Max 8 Categories + Sale + Shop
         ============================================ --}}
    <div class="hidden md:block border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <nav class="flex items-center justify-center gap-1 h-12">
                {{-- Shop Link --}}
                <a href="{{ route('products.index') }}"
                    class="text-sm font-medium text-gray-600 hover:text-primary transition-colors h-full flex items-center px-3 rounded-lg hover:bg-gray-50">
                    Shop
                </a>

                {{-- Max 8 Category Links --}}
                @foreach ($allMenuCategories->take(8) as $category)
                    <a href="{{ route('products.index', $category->slug) }}"
                        class="text-sm font-medium text-gray-600 hover:text-primary transition-colors h-full flex items-center px-3 rounded-lg hover:bg-gray-50 whitespace-nowrap">
                        {{ $category->name }}
                    </a>
                @endforeach

                {{-- Divider --}}
                <div class="w-px h-5 bg-gray-200 mx-2"></div>

                {{-- New Arrivals --}}
                <a href="{{ route('products.index') }}?sort=newest"
                    class="text-sm font-medium text-gray-600 hover:text-primary transition-colors h-full flex items-center px-3 rounded-lg hover:bg-gray-50">
                    New Arrivals
                </a>

                {{-- Sale (Highlighted) --}}
                <a href="{{ route('products.index') }}?filter=on-sale"
                    class="text-sm font-bold text-red-500 hover:text-red-600 transition-colors h-full flex items-center px-3 rounded-lg hover:bg-red-50 gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 23a7.5 7.5 0 01-5.138-12.963C8.204 8.774 11.5 6.5 11 1.5c6 4 9 8 3 14 1 0 2.5 0 5-2.47.27.773.5 1.604.5 2.47A7.5 7.5 0 0112 23z" />
                    </svg>
                    Sale
                </a>
            </nav>
        </div>
    </div>

    {{-- Search Bar (Mobile) --}}
    <div class="px-4 pb-3 md:hidden">
        <button onclick="openSearch()" class="relative w-full text-left">
            <div class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 pl-11 pr-4 text-sm text-gray-400">
                Search for products...
            </div>
            <svg class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>
</header>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    // Toggle profile dropdown
    function toggleProfileDropdown(event) {
        event.stopPropagation();
        const menu = document.getElementById('profileDropdownMenu');
        const arrow = document.getElementById('profileArrow');

        if (!menu) return;

        const isVisible = !menu.classList.contains('opacity-0');

        if (isVisible) {
            menu.classList.add('opacity-0', 'invisible', 'scale-95');
            menu.classList.remove('opacity-100', 'visible', 'scale-100');
            if (arrow) arrow.classList.remove('rotate-180');
        } else {
            menu.classList.remove('opacity-0', 'invisible', 'scale-95');
            menu.classList.add('opacity-100', 'visible', 'scale-100');
            if (arrow) arrow.classList.add('rotate-180');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const menu = document.getElementById('profileDropdownMenu');
        const arrow = document.getElementById('profileArrow');

        if (dropdown && menu && !dropdown.contains(event.target)) {
            menu.classList.add('opacity-0', 'invisible', 'scale-95');
            menu.classList.remove('opacity-100', 'visible', 'scale-100');
            if (arrow) arrow.classList.remove('rotate-180');
        }
    });

    // Open wishlist drawer/modal
    function openWishlist() {
        if (typeof window.toggleWishlistDrawer === 'function') {
            window.toggleWishlistDrawer();
        } else {
            window.location.href = '/wishlist';
        }
    }
</script>
