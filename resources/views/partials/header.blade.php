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
                {{-- All Categories Mega Menu --}}
                <div class="relative group">
                    <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-brand-blue transition-colors h-12">
                        <i class="fas fa-th-large text-brand-blue"></i>
                        All Categories
                        <i class="fas fa-chevron-down text-xs text-gray-400 group-hover:rotate-180 transition-transform duration-200"></i>
                    </button>

                    {{-- Mega Menu Dropdown --}}
                    <div class="absolute top-full left-0 w-[800px] bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50">
                        <div class="p-6">
                            <div class="grid grid-cols-4 gap-6">
                                {{-- Men's Wear --}}
                                <div>
                                    <a href="{{ route('products.index') }}?category=mens" class="flex items-center gap-2 text-sm font-bold text-brand-black mb-3 hover:text-brand-blue transition">
                                        <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-male text-brand-blue"></i>
                                        </span>
                                        Men's Wear
                                    </a>
                                    <ul class="space-y-2">
                                        <li><a href="{{ route('products.index') }}?category=mens&sub=shirts" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Formal Shirts</a></li>
                                        <li><a href="{{ route('products.index') }}?category=mens&sub=casual-shirts" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Casual Shirts</a></li>
                                        <li><a href="{{ route('products.index') }}?category=mens&sub=tshirts" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">T-Shirts</a></li>
                                        <li><a href="{{ route('products.index') }}?category=mens&sub=polo" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Polo Shirts</a></li>
                                        <li><a href="{{ route('products.index') }}?category=mens&sub=pants" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Pants & Chinos</a></li>
                                        <li><a href="{{ route('products.index') }}?category=mens&sub=jeans" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Jeans</a></li>
                                        <li><a href="{{ route('products.index') }}?category=mens&sub=jackets" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Jackets</a></li>
                                    </ul>
                                    <a href="{{ route('products.index') }}?category=mens" class="inline-flex items-center gap-1 text-xs text-brand-blue font-medium mt-3 hover:underline">
                                        View All <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>

                                {{-- Women's Wear --}}
                                <div>
                                    <a href="{{ route('products.index') }}?category=womens" class="flex items-center gap-2 text-sm font-bold text-brand-black mb-3 hover:text-brand-blue transition">
                                        <span class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-female text-pink-500"></i>
                                        </span>
                                        Women's Wear
                                    </a>
                                    <ul class="space-y-2">
                                        <li><a href="{{ route('products.index') }}?category=womens&sub=kameez" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Kameez</a></li>
                                        <li><a href="{{ route('products.index') }}?category=womens&sub=saree" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Saree</a></li>
                                        <li><a href="{{ route('products.index') }}?category=womens&sub=kurti" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Kurti</a></li>
                                        <li><a href="{{ route('products.index') }}?category=womens&sub=tops" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Tops & Blouses</a></li>
                                        <li><a href="{{ route('products.index') }}?category=womens&sub=palazzo" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Palazzo & Pants</a></li>
                                        <li><a href="{{ route('products.index') }}?category=womens&sub=dupatta" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Dupatta & Scarf</a></li>
                                    </ul>
                                    <a href="{{ route('products.index') }}?category=womens" class="inline-flex items-center gap-1 text-xs text-brand-blue font-medium mt-3 hover:underline">
                                        View All <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>

                                {{-- Traditional --}}
                                <div>
                                    <a href="{{ route('products.index') }}?category=traditional" class="flex items-center gap-2 text-sm font-bold text-brand-black mb-3 hover:text-brand-blue transition">
                                        <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-vest text-orange-500"></i>
                                        </span>
                                        Traditional
                                    </a>
                                    <ul class="space-y-2">
                                        <li><a href="{{ route('products.index') }}?category=traditional&sub=panjabi" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Panjabi</a></li>
                                        <li><a href="{{ route('products.index') }}?category=traditional&sub=fatua" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Fatua</a></li>
                                        <li><a href="{{ route('products.index') }}?category=traditional&sub=kurta" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Kurta</a></li>
                                        <li><a href="{{ route('products.index') }}?category=traditional&sub=sherwani" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Sherwani</a></li>
                                        <li><a href="{{ route('products.index') }}?category=traditional&sub=pajama" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Pajama</a></li>
                                        <li><a href="{{ route('products.index') }}?category=traditional&sub=eid-collection" class="text-sm text-orange-500 font-medium hover:text-orange-600 hover:pl-1 transition-all flex items-center gap-1"><i class="fas fa-star text-[10px]"></i> Eid Collection</a></li>
                                    </ul>
                                    <a href="{{ route('products.index') }}?category=traditional" class="inline-flex items-center gap-1 text-xs text-brand-blue font-medium mt-3 hover:underline">
                                        View All <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>

                                {{-- Kids & More --}}
                                <div>
                                    <a href="{{ route('products.index') }}?category=kids" class="flex items-center gap-2 text-sm font-bold text-brand-black mb-3 hover:text-brand-blue transition">
                                        <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-child text-green-500"></i>
                                        </span>
                                        Kids & More
                                    </a>
                                    <ul class="space-y-2">
                                        <li><a href="{{ route('products.index') }}?category=kids&sub=boys" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Boys Wear</a></li>
                                        <li><a href="{{ route('products.index') }}?category=kids&sub=girls" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Girls Wear</a></li>
                                        <li><a href="{{ route('products.index') }}?category=kids&sub=panjabi" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Kids Panjabi</a></li>
                                        <li><a href="{{ route('products.index') }}?category=kids&sub=tshirts" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Kids T-Shirts</a></li>
                                    </ul>

                                    <div class="mt-5 pt-4 border-t border-gray-100">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Accessories</span>
                                        <ul class="space-y-2 mt-2">
                                            <li><a href="{{ route('products.index') }}?category=accessories&sub=caps" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Caps & Hats</a></li>
                                            <li><a href="{{ route('products.index') }}?category=accessories&sub=belts" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Belts</a></li>
                                            <li><a href="{{ route('products.index') }}?category=accessories&sub=wallets" class="text-sm text-gray-600 hover:text-brand-blue hover:pl-1 transition-all">Wallets</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Featured Banner --}}
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="grid grid-cols-2 gap-4">
                                    <a href="{{ route('products.index') }}?collection=eid" class="relative overflow-hidden rounded-xl bg-gradient-to-r from-orange-500 to-red-500 p-5 text-white group">
                                        <div class="relative z-10">
                                            <span class="text-xs font-medium opacity-80">Limited Time</span>
                                            <h4 class="text-lg font-bold mt-1">Eid Collection 2026</h4>
                                            <p class="text-sm opacity-80 mt-1">Up to 40% Off on Premium Panjabi</p>
                                            <span class="inline-flex items-center gap-1 text-sm font-semibold mt-3 group-hover:gap-2 transition-all">
                                                Shop Now <i class="fas fa-arrow-right text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-white/10 text-8xl">
                                            <i class="fas fa-moon"></i>
                                        </div>
                                    </a>
                                    <a href="{{ route('products.index') }}?collection=new" class="relative overflow-hidden rounded-xl bg-gradient-to-r from-brand-blue to-blue-600 p-5 text-white group">
                                        <div class="relative z-10">
                                            <span class="text-xs font-medium opacity-80">Just Arrived</span>
                                            <h4 class="text-lg font-bold mt-1">New Arrivals</h4>
                                            <p class="text-sm opacity-80 mt-1">Fresh styles for the season</p>
                                            <span class="inline-flex items-center gap-1 text-sm font-semibold mt-3 group-hover:gap-2 transition-all">
                                                Explore <i class="fas fa-arrow-right text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-white/10 text-8xl">
                                            <i class="fas fa-sparkles"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Men Dropdown --}}
                <div class="relative group">
                    <a href="{{ route('products.index') }}?category=mens" class="flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors h-12">
                        Men
                        <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:rotate-180 transition-transform duration-200"></i>
                    </a>
                    <div class="absolute top-full left-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50 py-2">
                        <a href="{{ route('products.index') }}?category=mens&sub=shirts" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Formal Shirts</a>
                        <a href="{{ route('products.index') }}?category=mens&sub=casual-shirts" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Casual Shirts</a>
                        <a href="{{ route('products.index') }}?category=mens&sub=tshirts" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">T-Shirts</a>
                        <a href="{{ route('products.index') }}?category=mens&sub=polo" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Polo Shirts</a>
                        <a href="{{ route('products.index') }}?category=mens&sub=pants" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Pants & Chinos</a>
                        <a href="{{ route('products.index') }}?category=mens&sub=jackets" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Jackets</a>
                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <a href="{{ route('products.index') }}?category=mens" class="block px-4 py-2 text-sm text-brand-blue font-medium hover:bg-brand-blue/5 transition">View All Men's →</a>
                        </div>
                    </div>
                </div>

                {{-- Women Dropdown --}}
                <div class="relative group">
                    <a href="{{ route('products.index') }}?category=womens" class="flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors h-12">
                        Women
                        <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:rotate-180 transition-transform duration-200"></i>
                    </a>
                    <div class="absolute top-full left-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50 py-2">
                        <a href="{{ route('products.index') }}?category=womens&sub=kameez" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Kameez</a>
                        <a href="{{ route('products.index') }}?category=womens&sub=saree" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Saree</a>
                        <a href="{{ route('products.index') }}?category=womens&sub=kurti" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Kurti</a>
                        <a href="{{ route('products.index') }}?category=womens&sub=tops" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Tops & Blouses</a>
                        <a href="{{ route('products.index') }}?category=womens&sub=palazzo" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Palazzo & Pants</a>
                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <a href="{{ route('products.index') }}?category=womens" class="block px-4 py-2 text-sm text-brand-blue font-medium hover:bg-brand-blue/5 transition">View All Women's →</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('products.index') }}?collection=new" class="text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors">New Arrivals</a>

                {{-- Panjabi Dropdown --}}
                <div class="relative group">
                    <a href="{{ route('products.index') }}?category=panjabi" class="flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors h-12">
                        Panjabi
                        <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:rotate-180 transition-transform duration-200"></i>
                    </a>
                    <div class="absolute top-full left-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50 py-2">
                        <a href="{{ route('products.index') }}?category=panjabi&sub=premium" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Premium Panjabi</a>
                        <a href="{{ route('products.index') }}?category=panjabi&sub=cotton" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Cotton Panjabi</a>
                        <a href="{{ route('products.index') }}?category=panjabi&sub=silk" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Silk Panjabi</a>
                        <a href="{{ route('products.index') }}?category=panjabi&sub=embroidered" class="block px-4 py-2 text-sm text-gray-600 hover:bg-brand-blue/5 hover:text-brand-blue transition">Embroidered</a>
                        <a href="{{ route('products.index') }}?category=panjabi&sub=eid" class="block px-4 py-2 text-sm text-orange-500 font-medium hover:bg-orange-50 transition flex items-center gap-1"><i class="fas fa-star text-[10px]"></i> Eid Special</a>
                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <a href="{{ route('products.index') }}?category=panjabi" class="block px-4 py-2 text-sm text-brand-blue font-medium hover:bg-brand-blue/5 transition">View All Panjabi →</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('products.index') }}?category=saree" class="text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors">Saree</a>

                <a href="{{ route('products.index') }}?sale=1" class="text-sm font-medium text-red-500 hover:text-red-600 transition-colors flex items-center gap-1">
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