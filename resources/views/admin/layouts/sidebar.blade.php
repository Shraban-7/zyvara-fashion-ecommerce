<?php
 $pendingOrdersCount = \App\Models\Order::where('status', \App\Enums\OrderStatus::PENDING)->count();
 $user = auth()->user();
?>

<aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-50 flex flex-col w-[264px] bg-admin-sidebar border-r border-black/30 shadow-2xl shadow-black/20 lg:translate-x-0 lg:static lg:inset-0 -translate-x-full">

    {{-- Logo / Brand --}}
    <div class="sidebar-header flex items-center h-[72px] px-6 shrink-0 border-b border-white/[0.05]">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0 overflow-hidden">
            @if($settings['site_logo'])
            <img src="{{ storage_url($settings['site_logo']) }}" alt="logo" class="h-8 w-auto shrink-0">
            @else
            <div class="sidebar-logo-icon shrink-0 w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-br from-accent to-accent/70 shadow-lg shadow-accent/25 ring-1 ring-white/10">
                <i data-lucide="store" class="w-[18px] h-[18px] text-white"></i>
            </div>
            <div class="sidebar-text overflow-hidden">
                <h1 class="text-[15px] font-semibold text-white truncate leading-tight tracking-tight">{{ $siteName }}</h1>
                <p class="text-[10px] text-accent/75 font-medium uppercase tracking-[0.2em] mt-0.5">Admin Panel</p>
            </div>
            @endif
        </a>
    </div>

    {{-- Navigation --}}
    <nav id="sidebarNav" class="sidebar-nav flex-1 overflow-y-auto overflow-x-hidden py-5 px-3.5 space-y-0.5">

        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.dashboard') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Dashboard">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.dashboard') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="layout-dashboard" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Dashboard</span>
        </a>

        {{-- CATALOG --}}
        <div class="sidebar-section-label flex items-center gap-3 px-3 pt-5 pb-1.5">
            <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-accent/45 whitespace-nowrap">Catalog</span>
            <span class="h-px flex-1 bg-gradient-to-r from-white/[0.10] to-transparent"></span>
        </div>

        <div class="sidebar-group {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn group relative w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') ? 'active text-white' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Products">
                <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="package" class="w-[18px] h-[18px]"></i></span>
                <span class="sidebar-label truncate">Products</span>
                <span class="sidebar-chevron sidebar-label ml-auto shrink-0 text-white/35 transition-transform duration-200 group-hover:text-white/60"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.products.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.products.index') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.products.index') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="boxes" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">All Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="layout-grid" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Categories</span>
                </a>
                <a href="{{ route('admin.brands.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.brands.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.brands.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="award" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Brands</span>
                </a>
                <a href="{{ route('admin.products.printBarcode') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.products.printBarcode') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.products.printBarcode') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="scan-barcode" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Print Barcode</span>
                </a>
            </div>
        </div>

        {{-- MARKETING --}}
        <div class="sidebar-section-label flex items-center gap-3 px-3 pt-5 pb-1.5">
            <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-accent/45 whitespace-nowrap">Marketing</span>
            <span class="h-px flex-1 bg-gradient-to-r from-white/[0.10] to-transparent"></span>
        </div>

        <div class="sidebar-group {{ request()->routeIs('admin.flash-sales.*') || request()->routeIs('admin.banners.*') || request()->routeIs('admin.events.*') || request()->routeIs('admin.home-sections.*') || request()->routeIs('admin.testimonials.*') || request()->routeIs('admin.social-posts.*') || request()->routeIs('admin.coupons.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn group relative w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('admin.flash-sales.*') || request()->routeIs('admin.banners.*') || request()->routeIs('admin.events.*') || request()->routeIs('admin.home-sections.*') || request()->routeIs('admin.coupons.*') ? 'active text-white' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Promotions">
                <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.flash-sales.*') || request()->routeIs('admin.banners.*') || request()->routeIs('admin.events.*') || request()->routeIs('admin.home-sections.*') || request()->routeIs('admin.coupons.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="megaphone" class="w-[18px] h-[18px]"></i></span>
                <span class="sidebar-label truncate">Promotions</span>
                <span class="sidebar-chevron sidebar-label ml-auto shrink-0 text-white/35 transition-transform duration-200 group-hover:text-white/60"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.flash-sales.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.flash-sales.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.flash-sales.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="zap" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Flash Sales</span>
                </a>
                <a href="{{ route('admin.coupons.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.coupons.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.coupons.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="ticket" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Coupons</span>
                </a>
                <a href="{{ route('admin.banners.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.banners.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.banners.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="image" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Banners</span>
                </a>
                <a href="{{ route('admin.events.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.events.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.events.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="calendar-star" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Events</span>
                </a>
                <a href="{{ route('admin.home-sections.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.home-sections.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.home-sections.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="layout-template" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Homepage Sections</span>
                </a>
                <a href="{{ route('admin.testimonials.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.testimonials.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.testimonials.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="message-square-quote" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Testimonials</span>
                </a>
                <a href="{{ route('admin.social-posts.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.social-posts.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.social-posts.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="share-2" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Social Feed</span>
                </a>
            </div>
        </div>

        {{-- CUSTOMERS --}}
        <div class="sidebar-section-label flex items-center gap-3 px-3 pt-5 pb-1.5">
            <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-accent/45 whitespace-nowrap">Customers</span>
            <span class="h-px flex-1 bg-gradient-to-r from-white/[0.10] to-transparent"></span>
        </div>

        <a href="{{ route('admin.customers.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.customers.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Customers">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.customers.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="users" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Customers</span>
        </a>

        <a href="{{ route('admin.reviews.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.reviews.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Reviews">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.reviews.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="star" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Reviews</span>
        </a>

        {{-- SALES --}}
        <div class="sidebar-section-label flex items-center gap-3 px-3 pt-5 pb-1.5">
            <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-accent/45 whitespace-nowrap">Sales</span>
            <span class="h-px flex-1 bg-gradient-to-r from-white/[0.10] to-transparent"></span>
        </div>

        <a href="{{ route('admin.orders.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.orders.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Orders">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.orders.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="shopping-bag" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Orders</span>
            @if($pendingOrdersCount)
            <span class="sidebar-badge sidebar-label ml-auto shrink-0 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[11px] font-semibold rounded-full bg-accent/15 text-accent ring-1 ring-accent/20">{{ $pendingOrdersCount }}</span>
            @endif
        </a>

        @php
            $pendingReturnsCount = \App\Models\ReturnRequest::where('status', \App\Enums\ReturnStatus::PENDING)->count();
        @endphp
        <a href="{{ route('admin.returns.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.returns.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Returns & Exchanges">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.returns.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="undo-2" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Returns & Exchanges</span>
            @if($pendingReturnsCount)
            <span class="sidebar-badge sidebar-label ml-auto shrink-0 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[11px] font-semibold rounded-full bg-accent/15 text-accent ring-1 ring-accent/20">{{ $pendingReturnsCount }}</span>
            @endif
        </a>

        <div class="sidebar-group {{ request()->routeIs('admin.pos.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn group relative w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('admin.pos.*') ? 'active text-white' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Point of Sale">
                <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.pos.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="monitor-smartphone" class="w-[18px] h-[18px]"></i></span>
                <span class="sidebar-label truncate">Point of Sale</span>
                <span class="sidebar-chevron sidebar-label ml-auto shrink-0 text-white/35 transition-transform duration-200 group-hover:text-white/60"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.pos.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.pos.index') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.pos.index') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="monitor" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">POS Terminal</span>
                </a>
                <a href="{{ route('admin.pos.sales.index') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.pos.sales.*') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.pos.sales.*') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="receipt" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">POS Sales</span>
                </a>
            </div>
        </div>

        <a href="{{ route('admin.saleReturns.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.saleReturns.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Returns">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.saleReturns.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="rotate-ccw" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Returns</span>
        </a>

        {{-- FINANCE --}}
        <div class="sidebar-section-label flex items-center gap-3 px-3 pt-5 pb-1.5">
            <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-accent/45 whitespace-nowrap">Finance</span>
            <span class="h-px flex-1 bg-gradient-to-r from-white/[0.10] to-transparent"></span>
        </div>

        <a href="{{ route('admin.expenses.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.expenses.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Expenses">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.expenses.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="wallet" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Expenses</span>
        </a>

        {{-- ANALYTICS --}}
        <div class="sidebar-section-label flex items-center gap-3 px-3 pt-5 pb-1.5">
            <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-accent/45 whitespace-nowrap">Analytics</span>
            <span class="h-px flex-1 bg-gradient-to-r from-white/[0.10] to-transparent"></span>
        </div>

        <div class="sidebar-group {{ request()->routeIs('admin.reports.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn group relative w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'active text-white' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Reports">
                <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.reports.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="bar-chart-3" class="w-[18px] h-[18px]"></i></span>
                <span class="sidebar-label truncate">Reports</span>
                <span class="sidebar-chevron sidebar-label ml-auto shrink-0 text-white/35 transition-transform duration-200 group-hover:text-white/60"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.reports.overview') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.reports.overview') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.reports.overview') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="pie-chart" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Overview</span>
                </a>
                <a href="{{ route('admin.reports.financial') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.reports.financial') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.reports.financial') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="landmark" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Financial</span>
                </a>
                <a href="{{ route('admin.reports.sales') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.reports.sales') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.reports.sales') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="trending-up" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Sales</span>
                </a>
                <a href="{{ route('admin.reports.customers') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.reports.customers') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.reports.customers') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="user-round" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Customers</span>
                </a>
                <a href="{{ route('admin.reports.cashRegisters') }}"
                    class="sidebar-sublink group flex items-center gap-2.5 pl-10 pr-3 py-2 rounded-md text-[13px] transition-all duration-200 {{ request()->routeIs('admin.reports.cashRegisters') ? 'active bg-accent/[0.08] text-accent' : 'text-white/45 hover:bg-white/[0.04] hover:text-white' }}">
                    <span class="sidebar-sublink-icon shrink-0 {{ request()->routeIs('admin.reports.cashRegisters') ? 'text-accent' : 'text-white/35 group-hover:text-white/70' }}"><i data-lucide="calculator" class="w-3.5 h-3.5"></i></span>
                    <span class="truncate">Cash Register</span>
                </a>
            </div>
        </div>

        {{-- SYSTEM --}}
        <div class="sidebar-section-label flex items-center gap-3 px-3 pt-5 pb-1.5">
            <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-accent/45 whitespace-nowrap">System</span>
            <span class="h-px flex-1 bg-gradient-to-r from-white/[0.10] to-transparent"></span>
        </div>

        <a href="{{ route('admin.activity-logs.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.activity-logs.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Activity Logs">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.activity-logs.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="scroll-text" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Activity Logs</span>
        </a>

        <a href="{{ route('admin.static_pages.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.static_pages.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Static Pages">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.static_pages.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="file-text" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Static Pages</span>
        </a>

        <a href="{{ route('admin.settings.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.settings.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Settings">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.settings.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="settings" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Settings</span>
        </a>

        <a href="{{ route('admin.stores.index') }}"
            class="sidebar-link group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13.5px] font-medium transition-all duration-200 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-[3px] before:rounded-r-full before:bg-accent before:opacity-0 before:transition-opacity {{ request()->routeIs('admin.stores.*') ? 'active bg-white/[0.07] text-white before:opacity-100' : 'text-white/55 hover:bg-white/[0.05] hover:text-white' }}"
            data-tooltip="Stores">
            <span class="sidebar-icon shrink-0 flex items-center justify-center w-[18px] h-[18px] {{ request()->routeIs('admin.stores.*') ? 'text-accent' : 'text-white/55 group-hover:text-white' }}"><i data-lucide="map-pin" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label truncate">Stores</span>
        </a>

        {{-- Bottom spacer for breathing room --}}
        <div class="h-4 shrink-0"></div>
    </nav>

    {{-- Floating tooltip for collapsed state --}}
    <div id="sidebarTooltip" class="sidebar-tooltip"></div>
</aside>
