{{-- Shared order row (desktop) — used by dashboard recent orders & orders index --}}
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
@endphp

<div class="flex items-center gap-4 px-5 py-4 hover:bg-light transition-colors">
    <div class="min-w-0 flex-1">
        <p class="font-semibold text-primary truncate">#{{ $order->order_number }}</p>
        <p class="text-xs text-secondary-400">{{ $order->created_at->format('M d, Y · h:i A') }}</p>
    </div>

    <div class="hidden sm:block text-sm text-secondary-500 w-24 text-center">
        {{ $order->items_count ?? ($order->items ? count($order->items) : 0) }} item(s)
    </div>

    <div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $meta['bg'] }} {{ $meta['text'] }} {{ $meta['border'] }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>
            {{ $label }}
        </span>
    </div>

    <div class="text-right w-28">
        <p class="font-semibold text-primary">{{ money($order->total) }}</p>
    </div>

    <a href="{{ route('orders.show', $order->order_number) }}"
        class="shrink-0 inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-primary text-surface-elevated text-xs font-semibold hover:bg-primary-700 transition">
        Details <i class="fas fa-arrow-right text-[10px]"></i>
    </a>
</div>
