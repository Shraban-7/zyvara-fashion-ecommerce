{{-- Mobile Bottom Navigation --}}
<nav class="mobile-nav md:hidden">
    <div class="mobile-nav-inner">

        {{-- Home --}}
        <a href="{{ url('/') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'mobile-nav-item--active' : '' }}">
            <div class="mobile-nav-icon">
                <i class="fas fa-home"></i>
            </div>
            <span class="mobile-nav-label">Home</span>
        </a>

        {{-- Shop --}}
        <a href="{{ route('products.index') }}" class="mobile-nav-item {{ request()->routeIs('products.index') ? 'mobile-nav-item--active' : '' }}">
            <div class="mobile-nav-icon">
                <i class="fas fa-th-large"></i>
            </div>
            <span class="mobile-nav-label">Shop</span>
        </a>

        {{-- Categories --}}
        <button onclick="toggleMobileMenu(true)" class="mobile-nav-item">
            <div class="mobile-nav-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <span class="mobile-nav-label">Categories</span>
        </button>

        {{-- Cart --}}
        <button class="mobile-nav-item mobile-nav-item--cart" onclick="openCartDrawer()">
            <div class="mobile-nav-icon">
                <i class="fas fa-shopping-bag"></i>
                <span class="mobile-nav-badge" id="mobileCartCount">3</span>
            </div>
            <span class="mobile-nav-label">Cart</span>
        </button>

        {{-- Account --}}
        @auth
            <a href="{{ route('customer.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('customer.dashboard') ? 'mobile-nav-item--active' : '' }}">
                <div class="mobile-nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span class="mobile-nav-label">Account</span>
            </a>
        @else
            <button onclick="openAuthModal('login')" class="mobile-nav-item">
                <div class="mobile-nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span class="mobile-nav-label">Account</span>
            </button>
        @endauth

    </div>
</nav>

<style>
    /* ====================================================
       MOBILE BOTTOM NAVIGATION — Deep Navy Theme
    ==================================================== */

    .mobile-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        z-index: 50;
        padding-bottom: env(safe-area-inset-bottom);
        box-shadow: 0 -4px 20px rgba(26, 31, 46, 0.06);
    }

    .mobile-nav-inner {
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 8px 0 calc(8px + env(safe-area-inset-bottom));
        max-width: 500px;
        margin: 0 auto;
    }

    /* ── Nav Item ── */
    .mobile-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        flex: 1;
        min-width: 0;
    }

    .mobile-nav-item:active {
        transform: scale(0.95);
    }

    /* ── Icon ── */
    .mobile-nav-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        position: relative;
        transition: all 0.2s ease;
    }

    .mobile-nav-icon i {
        font-size: 18px;
        color: #8a919d;
        transition: color 0.2s ease;
    }

    /* ── Active State ── */
    .mobile-nav-item--active .mobile-nav-icon {
        background: #e3e6eb;
    }

    .mobile-nav-item--active .mobile-nav-icon i {
        color: #1a1f2e;
    }

    .mobile-nav-item--active .mobile-nav-label {
        color: #1a1f2e;
        font-weight: 700;
    }

    /* ── Hover State ── */
    .mobile-nav-item:hover .mobile-nav-icon {
        background: #f4f5f7;
    }

    .mobile-nav-item:hover .mobile-nav-icon i {
        color: #5e6572;
    }

    /* ── Label ── */
    .mobile-nav-label {
        font-size: 10px;
        font-weight: 600;
        color: #8a919d;
        line-height: 1;
        white-space: nowrap;
        transition: color 0.2s ease;
    }

    /* ── Cart Badge ── */
    .mobile-nav-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        background: #1a1f2e;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        border-radius: 99px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    /* ── Cart Special ── */
    .mobile-nav-item--cart .mobile-nav-icon {
        background: #e3e6eb;
    }

    .mobile-nav-item--cart .mobile-nav-icon i {
        color: #1a1f2e;
    }

    /* ── Accessibility ── */
    @media (prefers-reduced-motion: reduce) {
        .mobile-nav-item,
        .mobile-nav-icon,
        .mobile-nav-icon i,
        .mobile-nav-label {
            transition: none !important;
        }
        .mobile-nav-item:active {
            transform: none;
        }
    }
</style>