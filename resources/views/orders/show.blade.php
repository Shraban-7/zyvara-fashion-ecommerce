@extends('customer.layout')
@section('title', 'Order #' . ($order->order_number ?? ''))

@php
    $statusKey = property_exists($order->status, 'value') ? $order->status->value : (string) $order->status;
    $statusLabel = method_exists($order->status, 'label') ? $order->status->label() : ucfirst($statusKey);

    $statusBadge = [
        'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
        'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
        'shipped'    => 'bg-purple-50 text-purple-700 border-purple-200',
        'out_for_delivery' => 'bg-purple-50 text-purple-700 border-purple-200',
        'delivered'  => 'bg-success-50 text-success-700 border-success-200',
        'cancelled'  => 'bg-danger-50 text-danger-700 border-danger-200',
    ][$statusKey] ?? 'bg-secondary-100 text-secondary-700 border-secondary-200';
@endphp

@section('dashboard-content')
    <div class="space-y-6">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-secondary-500 hover:text-primary transition">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>

        {{-- Header --}}
        <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-xs text-secondary-400">Order Number</p>
                    <h1 class="font-heading text-2xl md:text-3xl font-semibold text-primary">#{{ $order->order_number }}</h1>
                    <p class="text-sm text-secondary-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full border text-sm font-semibold {{ $statusBadge }}">
                        <span class="w-2 h-2 rounded-full bg-current opacity-70"></span>{{ $statusLabel }}
                    </span>
                    <button onclick="printReceipt('{{ route('orders.invoice', $order->order_number) }}')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-primary text-surface-elevated text-sm font-semibold hover:bg-primary-700 transition">
                        <i class="fas fa-file-download"></i> Invoice
                    </button>
                </div>
            </div>
        </div>

        {{-- Contextual action buttons --}}
        <div class="flex flex-wrap gap-3">
            @if(in_array($statusKey, ['shipped', 'out_for_delivery', 'processing']))
                <a href="{{ route('orders.track', $order->order_number) }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-accent text-primary font-semibold text-sm hover:bg-accent-600 transition shadow-sm">
                    <i class="fas fa-truck-fast"></i> Track Order
                </a>
            @endif
            @if(in_array($statusKey, ['pending', 'processing']))
                <button onclick="openCancelModal()"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-danger-200 text-danger-600 font-semibold text-sm hover:bg-danger-50 transition">
                    <i class="fas fa-xmark"></i> Cancel Order
                </button>
            @endif
            @if($statusKey === 'delivered')
                <button onclick="reorderItems()"  {{-- wire to a reorder route later --}}
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-primary text-primary font-semibold text-sm hover:bg-primary-50 transition">
                    <i class="fas fa-rotate-left"></i> Reorder
                </button>
                <button onclick="window.location.href='{{ route('orders.returns.create') }}?order={{ $order->order_number }}'"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-secondary-200 text-secondary-600 font-semibold text-sm hover:bg-light transition">
                    <i class="fas fa-rotate"></i> Return / Exchange
                </button>
            @endif
        </div>

        {{-- Items --}}
        <section class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6">
            <h2 class="font-heading text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                <i class="fas fa-box text-accent-600"></i> Items ({{ $order->items ? count($order->items) : 0 }})
            </h2>
            <div class="space-y-3">
                @foreach($order->items ?? [] as $item)
                    <div class="flex gap-3 p-3 bg-light rounded-xl border border-secondary-100 relative">
                        @if(!empty($item->return_item_id))
                            <span class="absolute top-2 right-2 text-[10px] font-semibold px-2 py-1 rounded-full bg-danger-100 text-danger-600 border border-danger-200">Returned</span>
                        @endif
                        <div class="w-20 h-24 rounded-lg overflow-hidden bg-surface-elevated border border-secondary-100 shrink-0">
                            <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-sm text-primary leading-snug">{{ $item->product_name }}</h4>
                            @if($item->size_name || $item->color_name)
                                <p class="text-xs text-secondary-400 mt-1">
                                    @if($item->size_name)Size: {{ $item->size_name }}@endif
                                    @if($item->size_name && $item->color_name) · @endif
                                    @if($item->color_name)Color: {{ $item->color_name }}@endif
                                </p>
                            @endif
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm text-secondary-500">Qty: {{ $item->quantity }} × {{ money($item->unit_price) }}</span>
                                <span class="text-sm font-semibold text-primary-500">{{ money($item->total) }}</span>
                            </div>

                            @if($statusKey === 'delivered')
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button type="button" onclick="openReviewModal({{ $item->product_id }}, '{{ addslashes($item->product_name) }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-primary text-surface-elevated rounded-lg hover:bg-primary-700 transition">
                                        <i class="fas fa-star"></i> Write Review
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Addresses (two columns) --}}
        <section class="grid md:grid-cols-2 gap-4">
            <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-primary uppercase tracking-wide mb-3 flex items-center gap-2">
                    <i class="fas fa-location-dot text-accent-600"></i> Shipping Address
                </h3>
                <p class="font-medium text-primary">{{ $order->shipping_name ?? $order->user->name ?? 'N/A' }}</p>
                <p class="text-sm text-secondary-500 mt-1">{{ $order->shipping_address ?? 'N/A' }}</p>
                <p class="text-sm text-secondary-500">{{ $order->shipping_city ?? '' }}{{ $order->shipping_city && $order?->district?->name ? ', ' : '' }}{{ $order?->district?->name ?? '' }}</p>
                <p class="text-sm text-secondary-500 mt-1">{{ $order->shipping_phone ?? $order->user->phone ?? '' }}</p>
            </div>
            <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-primary uppercase tracking-wide mb-3 flex items-center gap-2">
                    <i class="fas fa-file-invoice text-accent-600"></i> Billing & Payment
                </h3>
                <p class="text-sm text-secondary-500">Payment Method</p>
                <p class="font-medium text-primary mb-2">{{ $order->payment_method->label() ?? 'N/A' }}</p>
                <p class="text-sm text-secondary-500">Billing Address</p>
                <p class="text-sm text-primary">{{ $order->billing_address ?? $order->shipping_address ?? 'Same as shipping' }}</p>
            </div>
        </section>

        {{-- Summary --}}
        <section class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6">
            <h2 class="font-heading text-lg font-semibold text-primary mb-4">Order Summary</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-secondary-500">Subtotal</span><span class="font-medium text-primary">{{ money($order->subtotal) }}</span></div>
                    <div class="flex justify-between"><span class="text-secondary-500">Shipping</span><span class="font-medium text-primary">{{ money($order->shipping_cost ?? 0) }}</span></div>
                    @if($order->discount_amount && $order->discount_amount > 0)
                        <div class="flex justify-between text-success-600">
                            <span>Discount @if($order->coupon_code)<span class="text-xs">({{ $order->coupon_code }})</span>@endif</span>
                            <span class="font-medium">-{{ money($order->discount_amount) }}</span>
                        </div>
                    @endif
                    <div class="h-px bg-secondary-100 my-2"></div>
                    <div class="flex justify-between text-lg"><span class="font-bold text-primary">Total</span><span class="font-bold text-primary-500">{{ money($order->total) }}</span></div>
                </div>
                <div class="bg-primary-50 rounded-xl p-4 border border-primary-100 text-sm space-y-2">
                    <div class="flex justify-between"><span class="text-secondary-500">Payment Status</span>
                        <span class="font-semibold {{ $order->payment_status->isPending() ? 'text-amber-600' : 'text-success-600' }}">{{ $order->payment_status->label() }}</span></div>
                    @if($order->payment_status->isPending() && $order->payment_method->isOnline())
                        <form action="{{ route('orders.payNow', $order->order_number) }}" method="POST" class="mt-1">
                            @csrf
                            <button class="inline-flex items-center gap-1.5 bg-accent text-primary px-4 py-2 rounded-lg text-xs font-semibold hover:bg-accent-600 transition">Pay Now</button>
                        </form>
                    @endif
                </div>
            </div>
        </section>
    </div>

    {{-- ===== Modals (kept from original) ===== --}}
    @include('partials._review-modal', ['order' => $order])
    @include('partials._return-modal', ['order' => $order])

    {{-- Cancel confirm modal (lightweight) --}}
    <div id="cancelModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-primary/50 p-4 backdrop-blur-sm">
        <div class="bg-surface-elevated rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-danger-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-xmark text-danger-500"></i>
            </div>
            <h3 class="font-heading text-lg font-semibold text-primary mb-1">Cancel this order?</h3>
            <p class="text-sm text-secondary-500 mb-5">This action can't be undone. You can also contact support.</p>
            <form action="#" method="POST" class="flex gap-3">
                @csrf
                <button type="button" onclick="closeCancelModal()" class="flex-1 bg-secondary-100 text-secondary-700 py-2.5 rounded-xl font-medium hover:bg-secondary-200 transition">Keep Order</button>
                <button type="submit" class="flex-1 bg-danger-600 text-surface-elevated py-2.5 rounded-xl font-medium hover:bg-danger-700 transition">Cancel Order</button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function printReceipt(url) {
                let w = window.open(url, '_blank', 'width=800,height=600');
                w.onload = function () { w.focus(); w.print(); w.onafterprint = () => w.close(); };
            }
            function openCancelModal() { document.getElementById('cancelModal').classList.replace('hidden', 'flex'); document.body.style.overflow = 'hidden'; }
            function closeCancelModal() { document.getElementById('cancelModal').classList.replace('flex', 'hidden'); document.body.style.overflow = 'auto'; }
            function reorderItems() { alert('Reorder will be wired to a controller endpoint.'); }

            const labels = ['', 'Terrible', 'Poor', 'Okay', 'Good', 'Excellent'];
            let selectedRating = 0;
            function paintStars(upTo) {
                document.querySelectorAll('#starGroup .star').forEach((s, i) => {
                    s.textContent = i < upTo ? '★' : '☆';
                    s.classList.toggle('text-accent', i < upTo);
                    s.classList.toggle('text-secondary-200', i >= upTo);
                });
                const lbl = document.getElementById('ratingLabel');
                if (lbl) lbl.textContent = labels[upTo] ?? '';
            }
            document.querySelectorAll('#starGroup .star').forEach(s => {
                s.addEventListener('mouseenter', () => paintStars(+s.dataset.val));
                s.addEventListener('mouseleave', () => paintStars(selectedRating));
                s.addEventListener('click', () => { selectedRating = +s.dataset.val; document.getElementById('ratingVal').value = selectedRating; paintStars(selectedRating); });
            });
            function openReviewModal(productId, productName) {
                document.getElementById('review_product_id').value = productId;
                document.getElementById('review_product_name').textContent = productName;
                selectedRating = 0; paintStars(0);
                document.getElementById('reviewModal').classList.replace('hidden', 'flex');
                document.body.style.overflow = 'hidden';
            }
            function closeReviewModal() { document.getElementById('reviewModal').classList.replace('flex', 'hidden'); document.body.style.overflow = 'auto'; }

            const returnModal = document.getElementById('returnModal');
            function openReturnModal(itemId) {
                document.getElementById('return_order_item_id').value = itemId;
                returnModal.classList.replace('hidden', 'flex');
                document.body.style.overflow = 'hidden';
            }
            function closeReturnModal() { returnModal.classList.replace('flex', 'hidden'); document.body.style.overflow = 'auto'; }
            function money(a){ try { return new Intl.NumberFormat(undefined,{style:'currency',currency:'{{ config('app.currency','USD') }}'}).format(a); } catch(e){ return '$'+a; } }
        </script>
    @endpush
@endsection
