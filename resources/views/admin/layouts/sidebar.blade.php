<?php
$pendingOrdersCount = \App\Models\Order::where('status', \App\Enums\OrderStatus::PENDING)->count();
$user = auth()->user();
?>

<aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-50 flex flex-col lg:translate-x-0 lg:static lg:inset-0 -translate-x-full">

    {{-- Logo --}}
    <div class="sidebar-header flex items-center h-16 px-5 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0 overflow-hidden">
            @if($settings['site_logo'])
            <img src="{{ storage_url($settings['site_logo']) }}" alt="logo" class="h-8 w-auto shrink-0">
            @else
            <div class="sidebar-logo-icon shrink-0 w-10 h-10 rounded-xl flex items-center justify-center">
                <i data-lucide="store" class="w-5 h-5 text-white"></i>
            </div>
            <div class="sidebar-text overflow-hidden">
                <h1 class="text-[15px] font-bold text-white truncate leading-tight">{{ $siteName }}</h1>
                <p class="text-[10px] text-indigo-400 font-semibold uppercase tracking-[0.15em]">Admin Panel</p>
            </div>
            @endif
        </a>
    </div>

    {{-- Navigation --}}
    <nav id="sidebarNav" class="sidebar-nav flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 space-y-0.5">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            data-tooltip="Dashboard">
            <span class="sidebar-icon"><i data-lucide="layout-dashboard" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Dashboard</span>
        </a>

        {{-- ── SALES ────────────────────────────── --}}
        <div class="sidebar-section-label">
            <span class="sidebar-section-line"></span>
            <span>Sales</span>
            <span class="sidebar-section-line"></span>
        </div>

        <a href="{{ route('admin.orders.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
            data-tooltip="Orders">
            <span class="sidebar-icon"><i data-lucide="shopping-bag" class="w-[18px] h-[18px]"></i></span>
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
                <span class="sidebar-icon"><i data-lucide="monitor-smartphone" class="w-[18px] h-[18px]"></i></span>
                <span class="sidebar-label">Point of Sale</span>
                <span class="sidebar-chevron sidebar-label"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.pos.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.pos.index') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="monitor" class="w-3.5 h-3.5"></i></span>
                    <span>POS Terminal</span>
                </a>
                <a href="{{ route('admin.pos.sales.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.pos.sales.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="receipt" class="w-3.5 h-3.5"></i></span>
                    <span>POS Sales</span>
                </a>
            </div>
        </div>

        <a href="{{ route('admin.saleReturns.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.saleReturns.*') ? 'active' : '' }}"
            data-tooltip="Returns">
            <span class="sidebar-icon"><i data-lucide="rotate-ccw" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Returns</span>
        </a>

        {{-- ── CATALOG ────────────────────────────── --}}
        <div class="sidebar-section-label">
            <span class="sidebar-section-line"></span>
            <span>Catalog</span>
            <span class="sidebar-section-line"></span>
        </div>

        {{-- Products submenu --}}
        <div class="sidebar-group {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') ? 'active' : '' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Products">
                <span class="sidebar-icon"><i data-lucide="package" class="w-[18px] h-[18px]"></i></span>
                <span class="sidebar-label">Products</span>
                <span class="sidebar-chevron sidebar-label"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.products.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="boxes" class="w-3.5 h-3.5"></i></span>
                    <span>All Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="layout-grid" class="w-3.5 h-3.5"></i></span>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.brands.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="award" class="w-3.5 h-3.5"></i></span>
                    <span>Brands</span>
                </a>
                <a href="{{ route('admin.products.printBarcode') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.products.printBarcode') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="scan-barcode" class="w-3.5 h-3.5"></i></span>
                    <span>Print Barcode</span>
                </a>
            </div>
        </div>

        <a href="{{ route('admin.reviews.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
            data-tooltip="Reviews">
            <span class="sidebar-icon"><i data-lucide="star" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Reviews</span>
        </a>

        {{-- ── PEOPLE ────────────────────────────── --}}
        <div class="sidebar-section-label">
            <span class="sidebar-section-line"></span>
            <span>People</span>
            <span class="sidebar-section-line"></span>
        </div>

        <a href="{{ route('admin.customers.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.customers.*') ? 'active' : '' }}"
            data-tooltip="Customers">
            <span class="sidebar-icon"><i data-lucide="users" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Customers</span>
        </a>

        <a href="{{ route('admin.employees.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}"
            data-tooltip="Employees">
            <span class="sidebar-icon"><i data-lucide="user-check" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Employees</span>
        </a>

        {{-- ── MARKETING ────────────────────────────── --}}
        <div class="sidebar-section-label">
            <span class="sidebar-section-line"></span>
            <span>Marketing</span>
            <span class="sidebar-section-line"></span>
        </div>

        <div class="sidebar-group {{ request()->routeIs('admin.coupons.*') || request()->routeIs('admin.banners.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn {{ request()->routeIs('admin.coupons.*') || request()->routeIs('admin.banners.*') ? 'active' : '' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Promotions">
                <span class="sidebar-icon"><i data-lucide="megaphone" class="w-[18px] h-[18px]"></i></span>
                <span class="sidebar-label">Promotions</span>
                <span class="sidebar-chevron sidebar-label"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.coupons.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="ticket-percent" class="w-3.5 h-3.5"></i></span>
                    <span>Coupons</span>
                </a>
                <a href="{{ route('admin.banners.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="image" class="w-3.5 h-3.5"></i></span>
                    <span>Banners</span>
                </a>
            </div>
        </div>

        {{-- ── FINANCE ────────────────────────────── --}}
        <div class="sidebar-section-label">
            <span class="sidebar-section-line"></span>
            <span>Finance</span>
            <span class="sidebar-section-line"></span>
        </div>

        <a href="{{ route('admin.expenses.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}"
            data-tooltip="Expenses">
            <span class="sidebar-icon"><i data-lucide="wallet" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Expenses</span>
        </a>

        {{-- ── REPORT ────────────────────────────── --}}
        <div class="sidebar-section-label">
            <span class="sidebar-section-line"></span>
            <span>Analytics</span>
            <span class="sidebar-section-line"></span>
        </div>
        <div class="sidebar-group {{ request()->routeIs('admin.reports.*') ? 'open' : '' }}">
            <button type="button"
                class="sidebar-group-btn {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                onclick="toggleSidebarGroup(this)"
                data-tooltip="Reports">
                <span class="sidebar-icon"><i data-lucide="bar-chart-3" class="w-[18px] h-[18px]"></i></span>
                <span class="sidebar-label">Reports</span>
                <span class="sidebar-chevron sidebar-label"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
            </button>
            <div class="sidebar-submenu">
                <a href="{{ route('admin.reports.overview') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.reports.overview') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="pie-chart" class="w-3.5 h-3.5"></i></span>
                    <span>Overview</span>
                </a>
                <a href="{{ route('admin.reports.financial') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.reports.financial') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="landmark" class="w-3.5 h-3.5"></i></span>
                    <span>Financial</span>
                </a>
                <a href="{{ route('admin.reports.sales') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="trending-up" class="w-3.5 h-3.5"></i></span>
                    <span>Sales</span>
                </a>
                <a href="{{ route('admin.reports.customers') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.reports.customers') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="user-round" class="w-3.5 h-3.5"></i></span>
                    <span>Customers</span>
                </a>
                <a href="{{ route('admin.reports.cashRegisters') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.reports.cashRegisters') ? 'active' : '' }}">
                    <span class="sidebar-sublink-icon"><i data-lucide="calculator" class="w-3.5 h-3.5"></i></span>
                    <span>Cash Register</span>
                </a>
            </div>
        </div>

        {{-- ── SYSTEM ────────────────────────────── --}}
        <div class="sidebar-section-label">
            <span class="sidebar-section-line"></span>
            <span>System</span>
            <span class="sidebar-section-line"></span>
        </div>

        <a href="{{ route('admin.activity-logs.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}"
            data-tooltip="Activity Logs">
            <span class="sidebar-icon"><i data-lucide="scroll-text" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Activity Logs</span>
        </a>

        <a href="{{ route('admin.static_pages.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.static_pages.*') ? 'active' : '' }}"
            data-tooltip="Static Pages">
            <span class="sidebar-icon"><i data-lucide="file-text" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Static Pages</span>
        </a>

        <a href="{{ route('admin.settings.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
            data-tooltip="Settings">
            <span class="sidebar-icon"><i data-lucide="settings" class="w-[18px] h-[18px]"></i></span>
            <span class="sidebar-label">Settings</span>
        </a>

    </nav>

    {{-- User Footer --}}
    <div class="sidebar-footer shrink-0 px-3 py-3">
        <div class="sidebar-user-card flex items-center gap-3 px-3 py-2.5 rounded-xl">
            <div class="sidebar-avatar shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="sidebar-text overflow-hidden flex-1 min-w-0">
                <p class="text-[13px] font-semibold text-slate-200 truncate leading-tight">{{ $user->name }}</p>
                <p class="text-[11px] text-slate-500 truncate">{{ $user->phone }}</p>
            </div>
            <form method="POST" action="{{ route('auth.logout') }}" class="sidebar-label shrink-0">
                @csrf
                <button type="submit"
                    class="sidebar-logout-btn p-1.5 rounded-lg transition-all"
                    title="Logout">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Floating tooltip for collapsed state --}}
    <div id="sidebarTooltip" class="sidebar-tooltip"></div>
</aside>