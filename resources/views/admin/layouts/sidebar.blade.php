<?php
$pendingOrdersCount = \App\Models\Order::where('status', \App\Enums\OrderStatus::PENDING)->count();
$user = auth()->user();
?>

<aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-50 flex flex-col lg:translate-x-0 lg:static lg:inset-0 -translate-x-full">

    {{-- Logo --}}
    <div class="sidebar-header flex items-center h-16 px-4 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0 overflow-hidden">
            @if($settings['site_logo'])
            <img src="{{ storage_url($settings['site_logo']) }}" alt="logo" class="h-8 w-auto shrink-0">
            @else
            <div class="sidebar-logo-icon shrink-0 w-9 h-9 rounded-xl flex items-center justify-center">
                <i class="fas fa-bolt text-white text-sm"></i>
            </div>
            <div class="sidebar-text overflow-hidden">
                <h1 class="text-sm font-bold text-gray-900 truncate leading-tight">{{ $siteName }}</h1>
                <p class="text-[10px] text-indigo-400 font-semibold uppercase tracking-widest">Admin</p>
            </div>
            @endif
        </a>
    </div>

    {{-- Navigation --}}
    <nav id="sidebarNav" class="sidebar-nav flex-1 overflow-y-auto overflow-x-hidden py-3 px-3 space-y-0.5">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            data-tooltip="Dashboard">
            <span class="sidebar-icon"><i class="fas fa-home"></i></span>
            <span class="sidebar-label">Dashboard</span>
        </a>

        {{-- ── SALES ────────────────────────────── --}}
        <div class="sidebar-section-label">Sales</div>

        <a href="{{ route('admin.orders.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
            data-tooltip="Orders">
            <span class="sidebar-icon"><i class="fas fa-shopping-bag"></i></span>
            <span class="sidebar-label">Orders</span>
            @if($pendingOrdersCount)
            <span class="sidebar-badge sidebar-label">{{ $pendingOrdersCount }}</span>
            @endif
        </a>

        {{-- POS submenu --}}
        <div class="sidebar-group {{ request()->routeIs('admin.pos.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Point of Sale">
                <span class="sidebar-icon"><i class="fas fa-cash-register"></i></span>
                <span class="sidebar-label">Point of Sale</span>
                <span class="sidebar-chevron sidebar-label"><i class="fas fa-chevron-right"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.pos.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.pos.index') ? 'active' : '' }}">
                    <span class="sidebar-sublink-dot"></span>POS Terminal
                </a>
                <a href="{{ route('admin.pos.sales.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.pos.sales.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-dot"></span>POS Sales
                </a>
            </div>
        </div>

        <a href="{{ route('admin.saleReturns.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.saleReturns.*') ? 'active' : '' }}"
            data-tooltip="Returns">
            <span class="sidebar-icon"><i class="fas fa-undo-alt"></i></span>
            <span class="sidebar-label">Returns</span>
        </a>

        {{-- ── CATALOG ────────────────────────────── --}}
        <div class="sidebar-section-label">Catalog</div>

        {{-- Products submenu --}}
        <div class="sidebar-group {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') ? 'active' : '' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Products">
                <span class="sidebar-icon"><i class="fas fa-store"></i></span>
                <span class="sidebar-label">Products</span>
                <span class="sidebar-chevron sidebar-label"><i class="fas fa-chevron-right"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.products.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                    <span class="sidebar-sublink-dot"></span>All Products
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-dot"></span>Categories
                </a>
                <a href="{{ route('admin.brands.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-dot"></span>Brands
                </a>
                <a href="{{ route('admin.products.printBarcode') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.products.printBarcode') ? 'active' : '' }}">
                    <span class="sidebar-sublink-dot"></span>Print Barcode
                </a>
            </div>
        </div>

        <a href="{{ route('admin.reviews.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
            data-tooltip="Reviews">
            <span class="sidebar-icon"><i class="fas fa-star"></i></span>
            <span class="sidebar-label">Reviews</span>
        </a>

        {{-- ── CUSTOMERS ────────────────────────────── --}}
        <div class="sidebar-section-label">Customers</div>

        <a href="{{ route('admin.customers.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.customers.*') ? 'active' : '' }}"
            data-tooltip="Customers">
            <span class="sidebar-icon"><i class="fas fa-users"></i></span>
            <span class="sidebar-label">Customers</span>
        </a>

        {{-- ── MARKETING ────────────────────────────── --}}
        <div class="sidebar-section-label">Marketing</div>

        <div class="sidebar-group {{ request()->routeIs('admin.coupons.*') || request()->routeIs('admin.banners.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn {{ request()->routeIs('admin.coupons.*') || request()->routeIs('admin.banners.*') ? 'active' : '' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Promotions">
                <span class="sidebar-icon"><i class="fas fa-bullhorn"></i></span>
                <span class="sidebar-label">Promotions</span>
                <span class="sidebar-chevron sidebar-label"><i class="fas fa-chevron-right"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.coupons.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-dot"></span>Coupons
                </a>
                <a href="{{ route('admin.banners.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-dot"></span>Banners
                </a>
            </div>
        </div>

        {{-- ── FINANCE ────────────────────────────── --}}
        <div class="sidebar-section-label">Finance</div>

        <a href="{{ route('admin.expenses.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}"
            data-tooltip="Expenses">
            <span class="sidebar-icon"><i class="fas fa-receipt"></i></span>
            <span class="sidebar-label">Expenses</span>
        </a>

        {{-- ── Employees ────────────────────────────── --}}
        <div class="sidebar-section-label">Employees</div>

        <a href="{{ route('admin.employees.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}"
            data-tooltip="Employees">
            <span class="sidebar-icon"><i class="fas fa-users"></i></span>
            <span class="sidebar-label">Employees</span>
        </a>

        {{-- ── SYSTEM ────────────────────────────── --}}
        <div class="sidebar-section-label">System</div>

        <a href="{{ route('admin.settings.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
            data-tooltip="Settings">
            <span class="sidebar-icon"><i class="fas fa-sliders-h"></i></span>
            <span class="sidebar-label">Settings</span>
        </a>

    </nav>

    {{-- User Footer --}}
    <div class="sidebar-footer shrink-0 px-3 py-3 border-t border-gray-100">
        <div class="flex items-center gap-3 px-2 py-2 rounded-xl">
            <div class="sidebar-avatar shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="sidebar-text overflow-hidden flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate leading-tight">{{ $user->name }}</p>
                <p class="text-[11px] text-gray-400 truncate">{{ $user->phone }}</p>
            </div>
            <form method="POST" action="{{ route('auth.logout') }}" class="sidebar-label shrink-0">
                @csrf
                <button type="submit"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                    title="Logout">
                    <i class="fas fa-sign-out-alt text-sm"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Floating tooltip for collapsed state --}}
    <div id="sidebarTooltip" class="sidebar-tooltip"></div>
</aside>