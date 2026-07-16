{{-- Floating Cart Button --}}
<button onclick="openCartDrawer()" id="floatingCartBtn" class="fixed bottom-24 right-4 md:bottom-8 md:right-8 z-40 w-14 h-14 bg-primary text-surface-elevated rounded-full shadow-2xl shadow-primary/40 flex items-center justify-center hover:scale-105 active:scale-95 transition-all duration-300 group backdrop-blur-sm">
    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
    </svg>
    {{-- Cart Count Badge --}}
    <span id="cartCountBadge" class="absolute -top-1 -right-1 min-w-[22px] h-[22px] px-1 bg-danger text-surface-elevated text-[11px] font-bold rounded-full flex items-center justify-center border-2 border-surface-elevated shadow-sm transition-colors duration-300">3</span>
</button>

{{-- Cart Drawer Overlay --}}
<div id="cartOverlay" onclick="closeCartDrawer()" class="fixed inset-0 bg-primary/60 backdrop-blur-sm z-50 opacity-0 invisible transition-all duration-400"></div>

{{-- Cart Drawer --}}
<div id="cartDrawer" class="fixed top-0 right-0 h-full w-full sm:w-[440px] bg-surface-elevated z-50 shadow-2xl transform translate-x-full transition-transform duration-400 ease-[cubic-bezier(0.32,0.72,0,1)] flex flex-col">

    {{-- Cart Header --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-primary-100 bg-surface-elevated/80 backdrop-blur-md sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-primary tracking-tight">Shopping Cart</h2>
                <p class="text-xs text-secondary-400 font-medium"><span id="cartItemCount">3</span> items</p>
            </div>
        </div>
        <button onclick="closeCartDrawer()" class="w-9 h-9 rounded-xl hover:bg-primary-50 flex items-center justify-center transition-colors duration-200 group">
            <svg class="w-5 h-5 text-secondary-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Free Shipping Progress --}}
    <div class="hidden px-6 py-4 bg-gradient-to-r from-primary-50/80 to-secondary-50/80 border-b border-primary-100" id="shippingBanner">
        <div class="flex items-center justify-between mb-2.5">
            <span class="text-xs text-secondary font-medium" id="shippingRemaining">Add <span class="font-semibold text-primary">৳1,500</span> more for free shipping!</span>
            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
            </svg>
        </div>
        <div class="h-1.5 bg-primary-100 rounded-full overflow-hidden">
            <div id="shippingProgressBar" class="h-full bg-gradient-to-r from-primary to-secondary rounded-full transition-all duration-700 ease-out" style="width: 0%"></div>
        </div>
    </div>

    {{-- Cart Items --}}
    <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4" id="cartItemsContainer">
        {{-- Cart items will be loaded dynamically here --}}
    </div>

    {{-- Empty Cart State --}}
    <div id="emptyCartState" class="hidden flex-1 flex flex-col items-center justify-center px-6 py-16">
        <div class="w-20 h-20 bg-primary-50 rounded-2xl flex items-center justify-center mb-5 ring-1 ring-primary-100">
            <svg class="w-10 h-10 text-secondary-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-primary mb-1.5">Your cart is empty</h3>
        <p class="text-sm text-secondary-400 text-center mb-8 max-w-[240px] leading-relaxed">Looks like you haven't added anything to your cart yet.</p>
        <a href="{{ route('products.index') }}" onclick="closeCartDrawer()" class="bg-primary text-surface-elevated px-8 py-3 rounded-xl font-semibold text-sm hover:bg-primary-700 transition-all duration-300 shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5">
            Start Shopping
        </a>
    </div>

    {{-- Coupon Code --}}
    <div class="px-6 py-4 border-t border-primary-100 bg-light/50" id="couponSection">
        <div class="flex gap-2">
            <div class="flex-1 relative">
                <input type="text" placeholder="Enter coupon code" class="w-full h-11 px-4 pr-10 bg-surface-elevated border border-primary-100 rounded-xl text-sm text-primary placeholder-secondary-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all duration-200">
                <svg class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-secondary-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <button class="h-11 px-5 bg-primary text-surface-elevated rounded-xl font-semibold text-sm hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-sm hover:shadow-md">
                Apply
            </button>
        </div>
    </div>

    {{-- Cart Footer --}}
    <div class="px-6 py-5 border-t border-primary-100 bg-surface-elevated" id="cartFooter">
        {{-- Price Summary --}}
        <div class="space-y-2.5 mb-5">
            <div class="flex items-center justify-between text-sm">
                <span class="text-secondary-400">Subtotal</span>
                <span class="font-semibold text-primary" id="cartSubtotal">৳0</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-secondary-400">Shipping</span>
                <span class="font-semibold text-primary" id="cartShipping">৳60</span>
            </div>
            <div class="flex items-center justify-between text-sm text-primary hidden" id="discountRow">
                <span>Discount</span>
                <span class="font-semibold" id="cartDiscount">-৳0</span>
            </div>
            <div class="h-px bg-primary-100 my-3"></div>
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-primary">Total</span>
                <span class="text-xl font-black text-primary tracking-tight" id="cartTotal">৳0</span>
            </div>
        </div>

        {{-- Checkout Button --}}
        <a href="{{ route('checkout.index') }}" class="w-full bg-primary text-surface-elevated py-4 rounded-xl font-bold text-center hover:bg-primary-700 active:bg-primary-800 transition-all duration-300 shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 flex items-center justify-center gap-2 mb-3 group">
            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span>Proceed to Checkout</span>
        </a>

        {{-- Continue Shopping --}}
        <button onclick="closeCartDrawer()" class="w-full text-center text-sm text-secondary-400 hover:text-primary transition-colors duration-200 py-2 font-medium group">
            <svg class="w-3.5 h-3.5 inline mr-1.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Continue Shopping
        </button>

        {{-- Payment Methods --}}
        <div class="flex items-center justify-center gap-4 mt-5 pt-4 border-t border-primary-100">
            <img src="{{ asset('assets/images/bkash.png') }}" alt="bKash" class="h-5 opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
            <img src="{{ asset('assets/images/nagad.png') }}" alt="Nagad" class="h-5 opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
            <img src="{{ asset('assets/images/visa.png') }}" alt="Visa" class="h-3.5 opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
            <img src="{{ asset('assets/images/mastercard.png') }}" alt="Mastercard" class="h-5 opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
        </div>
    </div>

</div>

<style>
/* Custom easing for smooth drawer */
#cartDrawer {
    transition-timing-function: cubic-bezier(0.32, 0.72, 0, 1);
}

/* Scrollbar styling for cart items */
#cartItemsContainer::-webkit-scrollbar {
    width: 4px;
}
#cartItemsContainer::-webkit-scrollbar-track {
    background: transparent;
}
#cartItemsContainer::-webkit-scrollbar-thumb {
    background: #c2c2c2;
    border-radius: 4px;
}
#cartItemsContainer::-webkit-scrollbar-thumb:hover {
    background: #a3a3a3;
}

/* Tap effect for buttons */
.tap-effect {
    -webkit-tap-highlight-color: transparent;
}
.tap-effect:active {
    transform: scale(0.97);
}

/* Floating button pulse animation - charcoal theme */
@keyframes cartPulse {
    0%, 100% { box-shadow: 0 10px 40px -10px rgba(28, 28, 30, 0.4); }
    50% { box-shadow: 0 10px 40px -5px rgba(28, 28, 30, 0.55); }
}
#floatingCartBtn {
    animation: cartPulse 3s ease-in-out infinite;
}

/* Footer intersection color change states */
#floatingCartBtn.in-footer {
    background: white;
    color: #1c1c1e;
    box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.3);
    border: 2px solid rgba(28, 28, 30, 0.1);
}

#floatingCartBtn.in-footer #cartCountBadge {
    border-color: #1c1c1e;
    background: #dc2626;
}

#floatingCartBtn.in-footer:hover {
    background: #f5f5f7;
    transform: scale(1.05);
}

#floatingCartBtn.in-footer:active {
    transform: scale(0.95);
}

#floatingCartBtn.in-footer svg {
    color: #1c1c1e;
}
</style>

<script>
    (function() {
        // Cart drawer functions
        window.openCartDrawer = function() {
            const drawer = document.getElementById('cartDrawer');
            const overlay = document.getElementById('cartOverlay');
            
            drawer.classList.remove('translate-x-full');
            overlay.classList.remove('opacity-0', 'invisible');
            overlay.classList.add('opacity-100', 'visible');
            document.body.style.overflow = 'hidden';
        };

        window.closeCartDrawer = function() {
            const drawer = document.getElementById('cartDrawer');
            const overlay = document.getElementById('cartOverlay');
            
            drawer.classList.add('translate-x-full');
            overlay.classList.remove('opacity-100', 'visible');
            overlay.classList.add('opacity-0', 'invisible');
            document.body.style.overflow = '';
        };

        // Footer intersection observer for floating cart button color change
        const footer = document.getElementById('mainFooter');
        const cartBtn = document.getElementById('floatingCartBtn');
        
        if (footer && cartBtn) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        cartBtn.classList.add('in-footer');
                    } else {
                        cartBtn.classList.remove('in-footer');
                    }
                });
            }, {
                root: null,
                rootMargin: '0px 0px -60px 0px',
                threshold: 0
            });

            observer.observe(footer);
        }

        // ESC key to close drawer
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeCartDrawer();
        });
    })();
</script>