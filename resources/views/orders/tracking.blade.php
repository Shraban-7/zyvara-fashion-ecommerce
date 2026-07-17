@extends('customer.layout')
@section('title', 'Track Order #' . ($order->order_number ?? ''))

@php
    // Step order for the tracker
    $steps = ['Order Placed', 'Processing', 'Shipped', 'Out for Delivery', 'Delivered'];
    $currentStep = $currentStep ?? ($order->status->value ?? 'pending');

    $stepIndex = array_search($currentStep, array_map('strtolower', [
        'order placed','processing','shipped','out for delivery','delivered'
    ]));
    if ($stepIndex === false) $stepIndex = 0;

    $carrier   = $carrier ?? $order->courier ?? 'Pathao Courier';
    $trackNo   = $trackNo ?? $order->tracking_number ?? 'PC-2026-ATL-008841';
    $eta       = $eta ?? ($order->estimated_delivery ?? ($order->created_at->copy()->addDays(5)->format('M d, Y')));
    $shipmentItems = $shipmentItems ?? ($order->items ?? collect());
@endphp

@section('dashboard-content')
    <div class="space-y-6">
        <a href="{{ route('orders.show', $order->order_number) }}" class="inline-flex items-center gap-2 text-sm font-medium text-secondary-500 hover:text-primary transition">
            <i class="fas fa-arrow-left"></i> Back to Order Details
        </a>

        {{-- Order summary header --}}
        <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <p class="text-xs text-secondary-400">Order Number</p>
                    <h1 class="font-heading text-2xl font-semibold text-primary">#{{ $order->order_number }}</h1>
                    <p class="text-sm text-secondary-500 mt-1">Placed on {{ $order->created_at->format('F d, Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-secondary-400">Order Total</p>
                    <p class="font-heading text-2xl font-semibold text-primary">{{ money($order->total) }}</p>
                </div>
            </div>
        </div>

        {{-- Visual stepper --}}
        <section class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-8"
            aria-label="Order progress timeline">
            <ol class="flex items-center" role="list">
                @foreach($steps as $i => $label)
                    @php
                        $done = $i < $stepIndex;
                        $active = $i === $stepIndex;
                        $circle = $done ? 'bg-primary text-surface-elevated'
                                     : ($active ? 'bg-accent text-primary ring-4 ring-accent-100'
                                                : 'bg-light text-secondary-300 border border-secondary-200');
                    @endphp
                    <li class="flex-1 relative flex flex-col items-center text-center" role="listitem">
                        @if($i > 0)
                            <span class="absolute top-5 -left-1/2 w-full h-0.5 {{ $i <= $stepIndex ? 'bg-primary' : 'bg-secondary-200' }}" aria-hidden="true"></span>
                        @endif
                        <span class="w-10 h-10 rounded-full flex items-center justify-center z-10 transition {{ $circle }}">
                            @if($done)
                                <i class="fas fa-check text-sm"></i>
                            @else
                                <span class="font-semibold text-sm">{{ $i + 1 }}</span>
                            @endif
                        </span>
                        <span class="mt-3 text-[11px] sm:text-xs font-medium leading-tight max-w-[72px] {{ $active ? 'text-primary' : ($done ? 'text-primary-700' : 'text-secondary-400') }}">
                            {{ $label }}
                        </span>
                    </li>
                @endforeach
            </ol>
            <p class="text-center text-sm text-secondary-500 mt-5">
                @if($currentStep === 'delivered')
                    <i class="fas fa-circle-check text-success-500"></i> Your order has been delivered.
                @else
                    Estimated delivery: <span class="font-semibold text-primary">{{ $eta }}</span>
                @endif
            </p>
        </section>

        {{-- Carrier + tracking number --}}
        <section class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center text-primary-500">
                        <i class="fas fa-truck-fast text-lg"></i>
                    </span>
                    <div>
                        <p class="text-xs text-secondary-400">Carrier</p>
                        <p class="font-semibold text-primary">{{ $carrier }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 bg-light rounded-xl px-3 py-2 border border-secondary-100">
                    <span class="text-sm font-mono text-primary">{{ $trackNo }}</span>
                    <button type="button" onclick="copyTracking('{{ $trackNo }}', this)"
                        class="text-secondary-400 hover:text-primary transition" aria-label="Copy tracking number">
                        <i class="far fa-copy"></i>
                    </button>
                </div>
            </div>
        </section>

        {{-- Shipment items --}}
        <section class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-5 md:p-6">
            <h2 class="font-heading text-lg font-semibold text-primary mb-4">Items in this Shipment</h2>
            <div class="space-y-3">
                @forelse($shipmentItems as $item)
                    <div class="flex gap-3 p-3 bg-light rounded-xl border border-secondary-100">
                        <div class="w-14 h-16 rounded-lg overflow-hidden bg-surface-elevated border border-secondary-100 shrink-0">
                            @if($item->product_image)
                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            @elseif($item->product && $item->product->thumbnail)
                                <img src="{{ $item->product->thumbnail }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-secondary-300"><i class="fas fa-image"></i></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-primary truncate">{{ $item->product_name }}</p>
                            @if($item->size_name || $item->color_name || $item->size || $item->color)
                                <p class="text-xs text-secondary-400 mt-0.5">
                                    @if($item->size_name ?? $item->size)Size: {{ $item->size_name ?? $item->size }}@endif
                                    @if(($item->size_name ?? $item->size) && ($item->color_name ?? $item->color)) · @endif
                                    @if($item->color_name ?? $item->color)Color: {{ $item->color_name ?? $item->color }}@endif
                                </p>
                            @endif
                            <p class="text-xs text-secondary-500 mt-1">Qty: {{ $item->quantity ?? 1 }} · {{ money($item->total ?? $item->subtotal ?? 0) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-secondary-400 text-center py-4">No items to show.</p>
                @endforelse
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            function copyTracking(text, btn) {
                navigator.clipboard.writeText(text).then(() => {
                    const icon = btn.querySelector('i');
                    icon.classList.remove('far', 'fa-copy');
                    icon.classList.add('fas', 'fa-check', 'text-accent-600');
                    setTimeout(() => {
                        icon.classList.add('far', 'fa-copy');
                        icon.classList.remove('fas', 'fa-check', 'text-accent-600');
                    }, 1800);
                });
            }
        </script>
    @endpush
@endsection
