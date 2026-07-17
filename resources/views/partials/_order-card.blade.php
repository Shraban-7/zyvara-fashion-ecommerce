{{-- Mobile order card — used by orders/index.blade.php --}}
@php
    $statusMeta = [
        'pending'    => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'border' => 'border-amber-200',   'dot' => 'bg-amber-500'],
        'processing' => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-200',    'dot' => 'bg-blue-500'],
        'shipped'    => ['bg' => 'bg-purple-50',  'text' => 'text-purple-700',  'border' => 'border-purple-200',  'dot' => 'bg-purple-500'],
        'delivered'  => ['bg' => 'bg-success-50', 'text' => 'text-success-700', 'border' => 'border-success-200', 'dot' => 'bg-success-500'],
        'cancelled'  => ['bg' => 'bg-danger-50',  'text' => 'text-danger-700',  'border' => 'border-danger-200',  'dot' => 'bg-danger-500'],
    ];
    $key = property_exists($order->status, 'value') ? $order->status->value : (string) $order->status;
    $meta = $statusMeta[$key] ?? ['bg' => 'bg-secondary-100', 'text' => 'text-secondary-700', 'border' => 'border-secondary-200', 'dot' => 'bg-secondary-400'];
    $label = method_exists($order->status, 'label') ? $order->status->label() : ucfirst($key);

    $thumbnails = [];
    if ($order->items) {
        foreach ($order->items->take(4) as $it) {
            $thumbnails[] = $it->product_image ?? ($it->product->thumbnail ?? null);
        }
    }
@endphp

<div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-[11px] uppercase tracking-wide text-secondary-400 font-semibold">Order</p>
            <p class="font-semibold text-primary">#{{ $order->order_number }}</p>
            <p class="text-xs text-secondary-400 mt-0.5">{{ $order->created_at->format('M d, Y') }}</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $meta['bg'] }} {{ $meta['text'] }} {{ $meta['border'] }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>{{ $label }}
        </span>
    </div>

    {{-- Stacked thumbnails --}}
    @if(count($thumbnails))
        <div class="flex -space-x-2 mt-4">
            @foreach($thumbnails as $thumb)
                <div class="w-11 h-14 rounded-lg overflow-hidden bg-light border-2 border-surface-elevated">
                    @if($thumb)
                        <img src="{{ $thumb }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-secondary-300"><i class="fas fa-image"></i></div>
                    @endif
                </div>
            @endforeach
            @if($order->items_count > 4 || (count($order->items ?? []) > 4))
                <div class="w-11 h-14 rounded-lg bg-light border-2 border-surface-elevated flex items-center justify-center text-xs font-semibold text-secondary-500">
                    +{{ ($order->items_count ?? count($order->items)) - 4 }}
                </div>
            @endif
        </div>
    @endif

    <div class="flex items-center justify-between mt-4 pt-4 border-t border-secondary-100">
        <span class="font-heading text-lg font-semibold text-primary">{{ money($order->total) }}</span>
        <div class="flex items-center gap-2">
            @if(in_array($key, ['shipped', 'out_for_delivery', 'processing']))
                <a href="{{ route('orders.track', $order->order_number) }}" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg border border-secondary-200 text-xs font-medium text-secondary-600 hover:bg-light transition">
                    <i class="fas fa-truck-fast"></i> Track
                </a>
            @endif
            <a href="{{ route('orders.show', $order->order_number) }}" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-primary text-surface-elevated text-xs font-semibold hover:bg-primary-700 transition">
                View <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
</div>
