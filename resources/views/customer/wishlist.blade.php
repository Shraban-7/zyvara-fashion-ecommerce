@extends('customer.layout')
@section('title', 'Wishlist')

@section('dashboard-content')
    <div class="space-y-6">
        <div class="flex items-end justify-between">
            <div>
                <h2 class="font-heading text-2xl font-semibold text-primary">My Wishlist</h2>
                <p class="text-sm text-secondary-500 mt-1">{{ $wishlists->count() }} {{ Str::plural('item', $wishlists->count()) }} saved for later</p>
            </div>
            <a href="{{ route('products.index') }}"
                class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-accent-600 hover:text-accent-700 transition">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </div>

        @if($wishlists->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($wishlists as $wishlist)
                    @php $product = $wishlist->product; @endphp
                    <div class="group bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm overflow-hidden hover:shadow-md transition relative flex flex-col">
                        {{-- Image --}}
                        <div class="relative aspect-[4/5] overflow-hidden bg-light">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-secondary-100">
                                    <i class="fas fa-image text-secondary-400 text-4xl"></i>
                                </div>
                            @endif

                            {{-- Remove --}}
                            <button type="button" onclick="removeFromWishlist({{ $wishlist->id }})"
                                class="absolute top-2 right-2 w-9 h-9 rounded-full bg-surface-elevated/90 backdrop-blur border border-secondary-100 flex items-center justify-center text-danger-500 hover:bg-danger-50 transition shadow-sm"
                                aria-label="Remove from wishlist">
                                <i class="fas fa-xmark"></i>
                            </button>

                            {{-- Stock badges --}}
                            @if($product->currentStock <= 0)
                                <span class="absolute top-2 left-2 bg-danger-600 text-surface-elevated px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide shadow-sm">Out of Stock</span>
                            @elseif($product->currentStock <= 5)
                                <span class="absolute top-2 left-2 bg-warning-600 text-surface-elevated px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide shadow-sm">Low Stock</span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-4 flex flex-col flex-1">
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                <h3 class="font-medium text-primary text-sm leading-snug line-clamp-2 group-hover:text-accent-600 transition">{{ $product->name }}</h3>
                            </a>

                            <div class="flex items-center gap-2 mt-2 mb-4">
                                @if($product->discount_price)
                                    <span class="font-semibold text-primary">{{ money($product->discount_price) }}</span>
                                    <span class="text-xs text-secondary-400 line-through">{{ money($product->price) }}</span>
                                @else
                                    <span class="font-semibold text-primary">{{ money($product->price) }}</span>
                                @endif
                            </div>

                            <div class="mt-auto space-y-2">
                                @if($product->currentStock > 0)
                                    <button type="button" onclick="addToWishlistCart({{ $product->id }}, '{{ $product->slug }}')"
                                        class="w-full inline-flex items-center justify-center gap-2 bg-primary text-surface-elevated py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                    <button type="button" onclick="buyNowWishlist({{ $product->id }}, '{{ $product->slug }}')"
                                        class="w-full inline-flex items-center justify-center gap-2 bg-accent text-surface-elevated py-2.5 rounded-xl text-sm font-medium hover:bg-accent-600 transition">
                                        <i class="fas fa-bolt"></i> Buy Now
                                    </button>
                                @else
                                    <button disabled
                                        class="w-full inline-flex items-center justify-center gap-2 bg-secondary-100 text-secondary-400 py-2.5 rounded-xl text-sm font-medium cursor-not-allowed">
                                        <i class="fas fa-ban"></i> Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $wishlists->links() }}
            </div>
        @else
            <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-12 text-center">
                <div class="w-20 h-20 rounded-full bg-light border border-secondary-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-secondary-400 text-3xl"></i>
                </div>
                <h3 class="font-heading text-xl font-semibold text-primary mb-1">Your Wishlist is Empty</h3>
                <p class="text-sm text-secondary-500 mb-6 max-w-sm mx-auto">Save your favorite items to buy them later. Tap the heart on any product to add it here.</p>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-6 py-3 rounded-xl text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fas fa-bag-shopping"></i> Continue Shopping
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function removeFromWishlist(id) {
                if (!confirm('Remove this item from your wishlist?')) return;
                fetch(`/wishlist/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => { if (data.success) location.reload(); })
                .catch(console.error);
            }
            function addToWishlistCart(productId, slug) {
                if (window.cartManager && typeof window.cartManager.addToCart === 'function') {
                    window.cartManager.addToCart(productId, null, 1)
                        .then(() => { if (typeof window.openCartDrawer === 'function') window.openCartDrawer(); })
                        .catch((err) => {
                            if (err.cartError && err.cartError.requires_variant) {
                                window.location.href = '{{ route('products.show', '') }}/' + slug;
                            }
                        });
                    return;
                }
                // Fallback: navigate to product page if cart helper isn't loaded
                window.location.href = '{{ route('products.index') }}';
            }
            async function buyNowWishlist(productId, slug) {
                if (window.cartManager && typeof window.cartManager.addToCart === 'function') {
                    try {
                        await window.cartManager.addToCart(productId, null, 1);
                        window.location.href = '{{ route('checkout.index') }}';
                    } catch (err) {
                        if (err.cartError && err.cartError.requires_variant) {
                            window.location.href = '{{ route('products.show', '') }}/' + slug;
                        }
                    }
                    return;
                }
                window.location.href = '{{ route('products.show', '') }}/' + slug;
            }
        </script>
    @endpush
@endsection
