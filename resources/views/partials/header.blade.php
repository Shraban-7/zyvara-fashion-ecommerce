{{-- Sticky Header --}}
<header id="mainHeader" class="sticky top-0 z-50 bg-white/95 backdrop-blur-lg border-b border-gray-100/50 transition-all duration-300">
    {{-- Top bar - Desktop only --}}
    <div class="hidden md:block bg-gradient-to-r from-gray-900 to-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-6 py-2 flex items-center justify-between text-xs">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2">
                    <i class="fas fa-truck text-brand-blue"></i>
                    Free Delivery on orders over ৳2000
                </span>
                <span class="flex items-center gap-2">
                    <i class="fas fa-phone text-brand-blue"></i>
                    +880 1712-345678
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-brand-blue transition">Track Order</a>
                <span class="text-gray-500">|</span>
                <a href="#" class="hover:text-brand-blue transition">Help & Support</a>
            </div>
        </div>
    </div>

    {{-- Main Navigation --}}
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="flex items-center justify-between h-16 md:h-20">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-1 group">
                <div class="relative">
                    <span class="text-2xl md:text-3xl font-extrabold logo-text tracking-tight">Smart</span>
                    <span class="text-2xl md:text-3xl font-extrabold logo-accent tracking-tight">Fashion</span>
                    <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-brand-blue to-blue-400 group-hover:w-full transition-all duration-300"></div>
                </div>
            </a>

            {{-- Desktop Search Bar --}}
            <div class="hidden md:flex flex-1 max-w-xl mx-8">
                <button onclick="openSearch()" class="relative w-full group text-left">
                    <div class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 pl-12 pr-6 text-sm text-gray-400 hover:border-gray-300 hover:bg-white transition-all duration-300">
                        Search for products, brands and more...
                    </div>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 bg-gray-200 text-gray-500 px-2 py-1 rounded-lg text-xs font-medium">
                        Ctrl+K
                    </span>
                </button>
            </div>

            {{-- Right Icons --}}
            <div class="flex items-center gap-1 md:gap-2">
                {{-- Search - Mobile --}}
                <button onclick="openSearch()" class="md:hidden icon-btn p-2.5 rounded-xl" aria-label="Search">
                    <i class="fas fa-search text-lg text-gray-600"></i>
                </button>

                {{-- Wishlist --}}
                <button class="hidden md:flex icon-btn p-2.5 rounded-xl items-center gap-2 group" aria-label="Wishlist">
                    <div class="relative">
                        <i class="far fa-heart text-lg text-gray-600 group-hover:text-brand-blue transition-colors"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span>
                    </div>
                    <span class="text-sm text-gray-600 group-hover:text-brand-blue transition-colors hidden lg:block">Wishlist</span>
                </button>

                {{-- Cart --}}
                <button class="icon-btn p-2.5 rounded-xl flex items-center gap-2 group" aria-label="Cart">
                    <div class="relative">
                        <i class="fas fa-shopping-bag text-lg text-gray-600 group-hover:text-brand-blue transition-colors"></i>
                        <span class="absolute -top-2 -right-2 bg-brand-blue text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold floating-badge">3</span>
                    </div>
                    <div class="hidden lg:flex flex-col items-start">
                        <span class="text-[10px] text-gray-400">Your Cart</span>
                        <span class="text-sm font-semibold text-gray-700">৳4,250</span>
                    </div>
                </button>

                {{-- Divider --}}
                <div class="hidden md:block w-px h-8 bg-gray-200 mx-2"></div>

                {{-- Profile --}}
                <button onclick="openAuthModal('login')" class="icon-btn p-2 rounded-xl flex items-center gap-2 group" aria-label="Profile">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-blue to-blue-600 flex items-center justify-center text-white font-semibold text-sm shadow-lg shadow-brand-blue/25">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="hidden lg:flex flex-col items-start">
                        <span class="text-[10px] text-gray-400">Hello, Sign in</span>
                        <span class="text-sm font-semibold text-gray-700">Account</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 hidden lg:block"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Desktop Navigation Menu --}}
    <div class="hidden md:block border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <nav class="flex items-center gap-8 h-12">
                <a href="#" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-brand-blue transition-colors">
                    <i class="fas fa-th-large text-brand-blue"></i>
                    All Categories
                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                </a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors">Men</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors">Women</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors">New Arrivals</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors">Panjabi</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors">Saree</a>
                <a href="#" class="text-sm font-medium text-red-500 hover:text-red-600 transition-colors flex items-center gap-1">
                    <i class="fas fa-fire text-xs"></i>
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
            <i class="fas fa-search text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
        </button>
    </div>
</header>