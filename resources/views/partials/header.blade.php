{{-- Sticky Header --}}
<header id="mainHeader"
    class="sticky top-0 z-50 bg-white/95 backdrop-blur-xl border-b border-gray-100/60 transition-all duration-300">
    
    {{-- Top bar - Desktop only --}}
    <div class="hidden md:block bg-primary text-white">
        <div class="max-w-7xl mx-auto px-6 py-2 flex items-center justify-between text-xs">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Contact: {{ $settings['contact_phone'] ?? '' }}
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('track-order.index') }}" class="hover:text-white/80 transition-colors duration-200">Track Order</a>
                <span class="text-white/30">|</span>
                <a href="#" class="hover:text-white/80 transition-colors duration-200">Help & Support</a>
            </div>
        </div>
    </div>

    {{-- Main Navigation --}}
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="flex items-center justify-between h-16 md:h-20">
            
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-1 group">
                @if($settings['site_logo'])
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
                            <span class="text-2xl md:text-3xl font-black text-primary tracking-tight">{{ $siteNames[0] }}</span>
                            @if (isset($siteNames[1]))
                                <span class="text-2xl md:text-3xl font-black text-secondary-600 tracking-tight">{{ $siteNames[1] }}</span>
                            @endif
                            <div class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-300"></div>
                        </div>
                    @endif
                @endif
            </a>

            {{-- Desktop Search Bar --}}
            <div class="hidden md:flex flex-1 max-w-xl mx-8">
                <button onclick="openSearch()" class="relative w-full group text-left">
                    <div class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 pl-12 pr-6 text-sm text-gray-400 hover:border-gray-300 hover:bg-white hover:shadow-sm transition-all duration-300">
                        Search for products, brands and more...
                    </div>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-primary transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 bg-gray-100 text-gray-500 px-2 py-1 rounded-lg text-[11px] font-semibold border border-gray-200">
                        Ctrl+K
                    </span>
                </button>
            </div>

            {{-- Right Icons --}}
            <div class="flex items-center gap-1 md:gap-2">
                
                {{-- Search - Mobile --}}
                <button onclick="openSearch()" class="md:hidden p-2.5 rounded-xl hover:bg-gray-100 transition-colors" aria-label="Search">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                {{-- Cart --}}
                <button class="p-2.5 rounded-xl flex items-center gap-2 group hover:bg-gray-100 transition-colors" aria-label="Cart"
                    onclick="openCartDrawer()">
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="absolute -top-2 -right-2 bg-primary text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold"
                            id="headerCartCount"></span>
                    </div>
                    <div class="hidden lg:flex flex-col items-start">
                        <span class="text-sm font-bold text-gray-900" id="headerCartTotal"></span>
                    </div>
                </button>

                {{-- Divider --}}
                <div class="hidden md:block w-px h-8 bg-gray-200 mx-1"></div>

                {{-- Profile --}}
                @auth
                    <?php
                        $user = auth()->user();
                        $isAdmin = $user->role == \App\Enums\UserRole::CUSTOMER ? false : true;
                    ?>
                    <div class="relative group" id="profileDropdown">
                        <button onclick="toggleProfileDropdown(event)"
                            class="p-2 rounded-xl flex items-center gap-2 hover:bg-gray-100 transition-colors" aria-label="Profile">
                            <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-white font-bold text-sm shadow-md shadow-primary/20">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="hidden lg:flex flex-col items-start">
                                <span class="text-[10px] text-gray-400 font-medium">Hello,</span>
                                <span class="text-sm font-bold text-gray-900">{{ Str::limit($user->name, 12) }}</span>
                            </div>
                            <svg class="w-3 h-3 text-gray-400 hidden lg:block group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div id="profileDropdownMenu"
                            class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 py-2 z-[60]">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->phone }}</p>
                            </div>
                            @if($isAdmin)
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 font-medium">Dashboard</span>
                                </a>
                            @else
                                <a href="{{ route('customer.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 font-medium">Dashboard</span>
                                </a>
                                <a href="{{ route('customer.profile') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 font-medium">My Profile</span>
                                </a>
                                <a href="{{ route('orders.index') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 font-medium">Orders</span>
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 font-medium">Settings</span>
                                </a>
                            @endif
                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <form action="{{ route('auth.logout') }}" method="POST" id="logoutForm">
                                    @csrf
                                    <button type="button"
                                        class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 transition-colors w-full text-left"
                                        onclick="document.getElementById('logoutForm').submit()">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span class="text-sm text-red-600 font-semibold">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <button onclick="openAuthModal('login')" class="p-2 rounded-xl flex items-center gap-2 group hover:bg-gray-100 transition-colors" aria-label="Profile">
                        <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-white font-bold text-sm shadow-md shadow-primary/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="hidden lg:flex flex-col items-start">
                            <span class="text-[10px] text-gray-400 font-medium">Hello, Sign in</span>
                            <span class="text-sm font-bold text-gray-900">Account</span>
                        </div>
                        <svg class="w-3 h-3 text-gray-400 hidden lg:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                @endauth
            </div>
        </div>
    </div>

    {{-- Desktop Navigation Menu --}}
    @if(request()->routeIs('home'))
        <div class="hidden md:block border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <nav class="flex items-center gap-8 h-12">
                    
                    {{-- All Categories Mega Menu --}}
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-700 transition-colors h-12">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            All Categories
                            <svg class="w-3 h-3 text-gray-400 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Mega Menu Dropdown --}}
                        <div class="absolute top-full left-0 w-[800px] bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="p-6">
                                <div class="grid grid-cols-4 gap-6">
                                    @php
                                        $categoryIcons = [
                                            'gray' => ['bg' => 'bg-gray-100', 'text' => 'text-primary'],
                                            'slate' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600'],
                                            'zinc' => ['bg' => 'bg-zinc-100', 'text' => 'text-zinc-600'],
                                            'stone' => ['bg' => 'bg-stone-100', 'text' => 'text-stone-600'],
                                        ];
                                        $iconKeys = array_keys($categoryIcons);
                                        $iconIndex = 0;
                                    @endphp

                                    @foreach($allMenuCategories->take(4) as $category)
                                        @php
                                            $iconColor = $categoryIcons[$iconKeys[$iconIndex % count($iconKeys)]];
                                            $iconIndex++;
                                        @endphp
                                        <div>
                                            <a href="{{ route('products.index') }}?categories={{ $category->slug }}"
                                                class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-3 hover:text-primary transition-colors">
                                                <span class="w-8 h-8 {{ $iconColor['bg'] }} rounded-lg flex items-center justify-center">
                                                    @if($category->icon)
                                                        <svg class="w-4 h-4 {{ $iconColor['text'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 {{ $iconColor['text'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                    @endif
                                                </span>
                                                {{ $category->name }}
                                            </a>
                                            <ul class="space-y-2">
                                                @foreach($category->children->take(7) as $child)
                                                    <li>
                                                        <a href="{{ route('products.index') }}?categories={{ $child->slug }}"
                                                            class="text-sm text-gray-500 hover:text-primary hover:pl-1 transition-all duration-200">{{ $child->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <a href="{{ route('products.index') }}?categories={{ $category->slug }}"
                                                class="inline-flex items-center gap-1 text-xs text-primary font-semibold mt-3 hover:underline">
                                                View All 
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Featured Banner --}}
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <div class="grid grid-cols-2 gap-4">
                                        <a href="{{ route('products.index') }}?collection=eid"
                                            class="relative overflow-hidden rounded-xl bg-primary p-5 text-white group hover:bg-primary-700 transition-colors">
                                            <div class="relative z-10">
                                                <span class="text-xs font-medium opacity-70">Limited Time</span>
                                                <h4 class="text-lg font-bold mt-1">Eid Collection 2026</h4>
                                                <p class="text-sm opacity-70 mt-1">Up to 40% Off on Premium Panjabi</p>
                                                <span class="inline-flex items-center gap-1 text-sm font-bold mt-3 group-hover:gap-2 transition-all">
                                                    Shop Now 
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-white/5">
                                                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                                </svg>
                                            </div>
                                        </a>
                                        <a href="{{ route('products.index') }}?collection=new"
                                            class="relative overflow-hidden rounded-xl bg-secondary-800 p-5 text-white group hover:bg-secondary-900 transition-colors">
                                            <div class="relative z-10">
                                                <span class="text-xs font-medium opacity-70">Just Arrived</span>
                                                <h4 class="text-lg font-bold mt-1">New Arrivals</h4>
                                                <p class="text-sm opacity-70 mt-1">Fresh styles for the season</p>
                                                <span class="inline-flex items-center gap-1 text-sm font-bold mt-3 group-hover:gap-2 transition-all">
                                                    Explore 
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-white/5">
                                                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                                </svg>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('products.index') }}"
                        class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Shop</a>
                    <a href="{{ route('products.index') }}?sort=newest"
                        class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">New Arrivals</a>

                    <a href="{{ route('products.index') }}?sort=best_selling"
                        class="text-sm font-medium text-red-500 hover:text-red-600 transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 23a7.5 7.5 0 01-5.138-12.963C8.204 8.774 11.5 6.5 11 1.5c6 4 9 8 3 14 1 0 2.5 0 5-2.47.27.773.5 1.604.5 2.47A7.5 7.5 0 0112 23z"/>
                        </svg>
                        Sale
                    </a>
                </nav>
            </div>
        </div>
    @endif

    {{-- Search Bar (Mobile) --}}
    <div class="px-4 pb-3 md:hidden">
        <button onclick="openSearch()" class="relative w-full text-left">
            <div class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 pl-11 pr-4 text-sm text-gray-400">
                Search for products...
            </div>
            <svg class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
    </div>
</header>

<script>
    // Toggle profile dropdown on mobile
    function toggleProfileDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('profileDropdownMenu');

        if (dropdown) {
            const isVisible = !dropdown.classList.contains('opacity-0');

            if (isVisible) {
                dropdown.classList.add('opacity-0', 'invisible');
            } else {
                dropdown.classList.remove('opacity-0', 'invisible');
            }
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('profileDropdown');
        const menu = document.getElementById('profileDropdownMenu');

        if (dropdown && menu && !dropdown.contains(event.target)) {
            menu.classList.add('opacity-0', 'invisible');
        }
    });
</script>