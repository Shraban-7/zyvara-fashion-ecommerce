{{-- Sticky / Transparent-over-hero Header --}}
@php
    $isHome = request()->routeIs('home');
    $siteName = $settings['site_name'] ?? 'ATELIER';
    $navCategories = $allMenuCategories->take(4);
@endphp
<header id="mainHeader"
    class="site-header {{ $isHome ? 'site-header--over-hero' : '' }} top-0 z-50 bg-surface-elevated/95 backdrop-blur-xl border-b border-border/70 transition-all duration-300">

    <div class="max-w-[1400px] mx-auto px-4 lg:px-8">
        <div class="site-header-inner flex items-center justify-between gap-4 h-16 lg:h-24 transition-all duration-300">

            {{-- ========== LEFT: nav links (desktop) / hamburger (mobile) ========== --}}
            <div class="flex items-center gap-6 flex-1 min-w-0">
                {{-- Mobile hamburger --}}
                <button onclick="toggleMobileMenu(true)"
                    class="site-icon-btn lg:hidden -ml-2 p-2 rounded-lg transition-colors shrink-0"
                    aria-label="Open menu" aria-expanded="false" aria-controls="mobileCategoryDrawer">
                    <svg class="w-6 h-6 site-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                    </svg>
                </button>

                {{-- Desktop nav links --}}
                <nav class="hidden lg:flex items-stretch gap-7 h-full" aria-label="Primary">
                    @foreach ($navCategories as $category)
                        @php $hasChildren = $category->children && $category->children->count() > 0; @endphp
                        <div class="site-nav-item group/nav flex items-center"
                            @if($hasChildren) onmouseenter="openMega(this)" onmouseleave="closeMega(this)" @endif>
                            <a href="{{ route('products.index', $category->slug) }}"
                                class="site-nav-link relative text-xs font-medium uppercase tracking-[0.14em] py-2 transition-colors"
                                @if($hasChildren) aria-haspopup="true" aria-expanded="false"
                                    onfocus="openMega(this.closest('.site-nav-item'))" @endif>
                                {{ $category->name }}
                                <span class="site-nav-underline absolute left-0 -bottom-0.5 h-px w-0 bg-accent transition-all duration-300 group-hover/nav:w-full"></span>
                            </a>

                            @if ($hasChildren)
                                {{-- Mega menu --}}
                                <div class="site-mega absolute left-0 right-0 top-full invisible opacity-0 translate-y-1 transition-all duration-200 z-40"
                                    role="menu" aria-label="{{ $category->name }} submenu">
                                    <div class="bg-surface-elevated border-t border-border shadow-xl">
                                        <div class="max-w-[1400px] mx-auto px-8 py-10 grid grid-cols-4 gap-10">
                                            <div class="col-span-3 grid grid-cols-3 gap-x-8 gap-y-3">
                                                @foreach ($category->children->take(15) as $child)
                                                    <a href="{{ route('products.index', $child->slug) }}"
                                                        class="text-sm text-secondary hover:text-primary transition-colors py-1"
                                                        role="menuitem">{{ $child->name }}</a>
                                                @endforeach
                                            </div>
                                            <a href="{{ route('products.index', $category->slug) }}" class="col-span-1 group/promo block">
                                                <div class="aspect-4/5 overflow-hidden bg-light">
                                                    <img src="https://placehold.co/400x500/1A1A1A/FAF8F5?text={{ urlencode($category->name) }}"
                                                        alt="{{ $category->name }}"
                                                        class="w-full h-full object-cover transition-transform duration-500 group-hover/promo:scale-105">
                                                </div>
                                                <span class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium uppercase tracking-[0.14em] text-primary">
                                                    Shop {{ $category->name }}
                                                    <svg class="w-3.5 h-3.5 transition-transform group-hover/promo:translate-x-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    {{-- New In --}}
                    <div class="site-nav-item group/nav flex items-center">
                        <a href="{{ route('products.index') }}?sort=newest"
                            class="site-nav-link relative text-xs font-medium uppercase tracking-[0.14em] py-2 transition-colors">
                            New In
                            <span class="site-nav-underline absolute left-0 -bottom-0.5 h-px w-0 bg-accent transition-all duration-300 group-hover/nav:w-full"></span>
                        </a>
                    </div>

                    {{-- Sale (accent gold) --}}
                    <div class="site-nav-item group/nav flex items-center">
                        <a href="{{ route('products.index') }}?filter=on-sale"
                            class="site-nav-sale relative text-xs font-semibold uppercase tracking-[0.14em] py-2 transition-colors">
                            Sale
                            <span class="site-nav-underline absolute left-0 -bottom-0.5 h-px w-0 bg-accent transition-all duration-300 group-hover/nav:w-full"></span>
                        </a>
                    </div>
                </nav>
            </div>

            {{-- ========== CENTER: logo ========== --}}
            <a href="{{ url('/') }}" class="flex items-center justify-center shrink-0 group flex-none" aria-label="{{ $siteName }} home">
                @if ($settings['site_logo'])
                    <img src="{{ storage_url($settings['site_logo']) }}" alt="{{ $siteName }}" class="site-logo-img h-8 sm:h-10 lg:h-12 transition-all duration-300 max-w-full">
                @else
                    <span class="site-logo-text font-heading text-lg sm:text-2xl lg:text-3xl font-semibold uppercase transition-all duration-300 truncate">
                        {{ $siteName }}
                    </span>
                @endif
            </a>

            {{-- ========== RIGHT: icon group ========== --}}
            <div class="flex items-center justify-end gap-1 lg:gap-2 flex-1">

                {{-- Search --}}
                <button onclick="openSearch()" class="site-icon-btn p-2.5 rounded-lg transition-colors" aria-label="Search">
                    <svg class="w-5 h-5 site-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m2.2-5.3a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" />
                    </svg>
                </button>

                {{-- Account --}}
                <div class="relative" id="profileDropdown">
                    <button id="profileTrigger" onclick="toggleProfileDropdown(event)"
                        class="site-icon-btn p-2.5 rounded-lg transition-colors"
                        aria-label="Account" aria-haspopup="true" aria-expanded="false" aria-controls="profileDropdownMenu">
                        <svg class="w-5 h-5 site-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                    </button>

                    {{-- Mobile/tablet bottom-sheet backdrop --}}
                    <div id="profileBackdrop" onclick="closeProfileDropdown()"
                        class="fixed inset-0 bg-primary/40 backdrop-blur-sm opacity-0 invisible transition-opacity duration-200 z-[59] lg:hidden"></div>

                    {{-- Dropdown / bottom-sheet --}}
                    <div id="profileDropdownMenu" role="menu" aria-label="Account menu"
                        class="site-profile-panel
                               fixed inset-x-0 bottom-0 translate-y-full rounded-t-3xl
                               lg:absolute lg:inset-x-auto lg:right-0 lg:bottom-auto lg:top-full lg:mt-3 lg:w-72 lg:rounded-2xl lg:translate-y-0 lg:scale-95 lg:origin-top-right
                               bg-surface-elevated border border-border shadow-2xl
                               opacity-0 invisible transition-all duration-200 z-[60] overflow-hidden">

                        @auth
                            <?php
                            $user = auth()->user();
                            $isAdmin = $user->role == \App\Enums\UserRole::CUSTOMER ? false : true;
                            ?>
                            {{-- Mobile/tablet grabber --}}
                            <div class="lg:hidden flex justify-center pt-3 pb-1">
                                <span class="h-1 w-10 rounded-full bg-border"></span>
                            </div>
                            {{-- Greeting --}}
                            <div class="px-5 py-4">
                                <p class="text-xs uppercase tracking-[0.14em] text-secondary">Welcome back</p>
                                <p class="font-heading text-lg text-primary truncate">Hi, {{ Str::before($user->name, ' ') ?: $user->name }}</p>
                            </div>
                            <div class="h-px bg-border"></div>

                            <nav class="p-2 pb-3 lg:pb-2" aria-label="Account links">
                                @if ($isAdmin)
                                    <a href="{{ route('admin.dashboard') }}" role="menuitem" class="site-menu-link">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                                        <span>Dashboard</span>
                                    </a>
                                @else
                                    <a href="{{ route('orders.index') }}" role="menuitem" class="site-menu-link">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12A1.125 1.125 0 0 1 19.75 21H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 6.75h12.974c.576 0 1.059.435 1.119 1.007Z" /></svg>
                                        <span>My Orders</span>
                                    </a>
                                    <button type="button" onclick="closeProfileDropdown(); openWishlist();" role="menuitem" class="site-menu-link w-full text-left">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                        <span>Wishlist</span>
                                    </button>
                                    <a href="{{ route('customer.profile') }}" role="menuitem" class="site-menu-link">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        <span>Account Settings</span>
                                    </a>
                                    <a href="{{ route('customer.dashboard') }}" role="menuitem" class="site-menu-link">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                        <span>Address Book</span>
                                    </a>
                                @endif
                            </nav>
                            <div class="h-px bg-border"></div>
                            <div class="p-2 pb-3 lg:pb-2">
                                <form action="{{ route('auth.logout') }}" method="POST" id="logoutForm">
                                    @csrf
                                    <button type="submit" role="menuitem" class="site-menu-link w-full text-left">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            {{-- Mobile/tablet grabber --}}
                            <div class="lg:hidden flex justify-center pt-3 pb-1">
                                <span class="h-1 w-10 rounded-full bg-border"></span>
                            </div>
                            <div class="px-5 py-5 pb-7 lg:pb-5">
                                <h3 class="font-heading text-lg text-primary">My Account</h3>
                                <p class="mt-1 text-xs leading-relaxed text-secondary">New here? Join for early access to sales.</p>
                                <div class="mt-4 space-y-2.5">
                                    <button onclick="closeProfileDropdown(); openAuthModal('login');"
                                        class="w-full bg-primary text-surface-elevated py-3 text-xs font-medium uppercase tracking-[0.15em] transition-colors hover:bg-accent hover:text-primary">
                                        Sign In
                                    </button>
                                    <button onclick="closeProfileDropdown(); openAuthModal('register');"
                                        class="w-full border border-primary text-primary py-3 text-xs font-medium uppercase tracking-[0.15em] transition-colors hover:bg-primary hover:text-surface-elevated">
                                        Create Account
                                    </button>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                {{-- Wishlist --}}
                <button onclick="openWishlist()" class="site-icon-btn relative p-2.5 rounded-lg transition-colors" aria-label="Wishlist">
                    <svg class="w-5 h-5 site-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    <span id="headerWishlistCount"
                        class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 bg-accent text-primary text-[10px] rounded-full flex items-center justify-center font-semibold">0</span>
                </button>

                {{-- Cart --}}
                <button onclick="openCartDrawer()" class="site-icon-btn relative p-2.5 rounded-lg transition-colors" aria-label="Cart">
                    <svg class="w-5 h-5 site-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <span id="headerCartCount"
                        class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 bg-accent text-primary text-[10px] rounded-full flex items-center justify-center font-semibold"></span>
                    <span class="sr-only site-cart-total" id="headerCartTotal"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<style>
    /* ================= condense on scroll ================= */
    .site-header.scrolled .site-header-inner { height: 4rem; }
    .site-header.scrolled .site-logo-text { font-size: 1.5rem; }
    .site-header.scrolled .site-logo-img { height: 2.25rem; }

    /* ── Logo responsiveness: avoid overflow / crowding on small screens ── */
    .site-logo-text {
        letter-spacing: 0.18em;
        white-space: nowrap;
        max-width: 38vw;
    }
    @media (min-width: 640px)  { .site-logo-text { letter-spacing: 0.3em; max-width: none; } }
    .site-logo-img { max-width: 120px; object-fit: contain; }
    @media (min-width: 768px)  { .site-logo-img { max-width: 180px; } }

    /* Prevent hamburger + logo overlap on small phones */
    @media (max-width: 479px) {
        .site-header-inner { gap: 2px; }
        .site-logo-img { max-width: 100px; }
    }

    /* ── Tighter gaps on small tablets (between mobile + desktop nav) ── */
    @media (min-width: 768px) and (max-width: 1023px) {
        .site-header-inner { gap: 2px; }
        .site-nav-link,
        .site-nav-sale { letter-spacing: 0.10em; }
        .site-header .site-icon-btn { padding: 0.5rem; }
    }

    /* ── Very small phones: shrink icon buttons to avoid overflow ── */
    @media (max-width: 359px) {
        .site-header-inner { gap: 2px; }
        .site-icon-btn { padding: 0.375rem; }
        .site-logo-text { letter-spacing: 0.2em; }
    }

    /* ================= TRANSPARENT OVER-HERO ================= */
    .site-header--over-hero:not(.scrolled) {
        background-color: transparent !important;
        backdrop-filter: none !important;
        border-bottom-color: transparent !important;
    }
    .site-header--over-hero { position: fixed; left: 0; right: 0; }
    .site-header:not(.site-header--over-hero) { position: sticky; }

    .site-header--over-hero:not(.scrolled) .site-logo-text,
    .site-header--over-hero:not(.scrolled) .site-nav-link,
    .site-header--over-hero:not(.scrolled) .site-icon { color: #FAF8F5 !important; }
    .site-header--over-hero:not(.scrolled) .site-nav-sale { color: #C9A87C !important; }
    .site-header--over-hero:not(.scrolled) .site-icon-btn:hover { background-color: rgba(250, 248, 245, 0.15); }

    /* Solid / non-home theme */
    .site-header .site-nav-link { color: #1A1A1A; }
    .site-header .site-nav-link:hover { color: #1A1A1A; }
    .site-header .site-nav-sale { color: #9c7949; }
    .site-header .site-icon { color: #1A1A1A; }
    .site-header .site-icon-btn:hover { background-color: rgba(26, 26, 26, 0.05); }

    /* ================= mega-menu open state ================= */
    .site-nav-item.mega-open .site-mega { visibility: visible; opacity: 1; transform: translateY(0); }

    /* ================= profile menu link ================= */
    .site-menu-link {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.75rem 0.75rem; border-radius: 0.5rem;
        font-size: 0.875rem; color: #1A1A1A;
        transition: background-color 0.15s ease;
    }
    .site-menu-link:hover { background-color: #FAF8F5; }
    .site-menu-link svg { width: 1.05rem; height: 1.05rem; color: #8A8A8A; flex-shrink: 0; }

    /* ================= profile panel open state ================= */
    .site-profile-panel.is-open { opacity: 1; visibility: visible; }
    @media (min-width: 1024px) { .site-profile-panel.is-open { transform: scale(1); } }
    @media (max-width: 1023px) { .site-profile-panel.is-open { transform: translateY(0); } }
    #profileBackdrop.is-open { opacity: 1; visibility: visible; }
</style>

<script>
    // ---- Mega menu ----
    function openMega(item) {
        document.querySelectorAll('.site-nav-item.mega-open').forEach(el => { if (el !== item) el.classList.remove('mega-open'); });
        item.classList.add('mega-open');
        const link = item.querySelector('[aria-haspopup]');
        if (link) link.setAttribute('aria-expanded', 'true');
    }
    function closeMega(item) {
        item.classList.remove('mega-open');
        const link = item.querySelector('[aria-haspopup]');
        if (link) link.setAttribute('aria-expanded', 'false');
    }

    // ---- Profile dropdown / bottom-sheet ----
    function openProfileDropdown() {
        const menu = document.getElementById('profileDropdownMenu');
        const backdrop = document.getElementById('profileBackdrop');
        const trigger = document.getElementById('profileTrigger');
        if (!menu) return;
        menu.classList.add('is-open');
        if (backdrop) backdrop.classList.add('is-open');
        if (trigger) trigger.setAttribute('aria-expanded', 'true');
        const first = menu.querySelector('a,button');
        if (first) first.focus({ preventScroll: true });
    }
    function closeProfileDropdown() {
        const menu = document.getElementById('profileDropdownMenu');
        const backdrop = document.getElementById('profileBackdrop');
        const trigger = document.getElementById('profileTrigger');
        if (!menu) return;
        menu.classList.remove('is-open');
        if (backdrop) backdrop.classList.remove('is-open');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
    }
    function toggleProfileDropdown(event) {
        event.stopPropagation();
        const menu = document.getElementById('profileDropdownMenu');
        if (!menu) return;
        menu.classList.contains('is-open') ? closeProfileDropdown() : openProfileDropdown();
    }
    document.addEventListener('click', function (e) {
        const dd = document.getElementById('profileDropdown');
        if (dd && !dd.contains(e.target)) closeProfileDropdown();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeProfileDropdown();
            document.querySelectorAll('.site-nav-item.mega-open').forEach(el => closeMega(el));
        }
    });

    // ---- Wishlist ----
    function openWishlist() {
        if (typeof window.toggleWishlistDrawer === 'function') window.toggleWishlistDrawer();
        else window.location.href = '/wishlist';
    }

    // ---- Transparent-over-hero → solid on scroll (+condense) ----
    (function () {
        const header = document.getElementById('mainHeader');
        if (!header) return;
        const overHero = header.classList.contains('site-header--over-hero');
        let ticking = false;
        function update() {
            if (overHero) {
                const threshold = window.innerHeight * 0.8;
                header.classList.toggle('scrolled', window.scrollY > threshold);
            } else {
                header.classList.toggle('scrolled', window.scrollY > 40);
            }
            ticking = false;
        }
        window.addEventListener('scroll', function () {
            if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
        }, { passive: true });
        update();
    })();
</script>
