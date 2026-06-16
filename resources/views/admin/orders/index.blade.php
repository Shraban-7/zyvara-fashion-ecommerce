@extends('admin.layouts.app')
@section('title', 'Orders')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Orders</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage and track all customer orders</p>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="window.print()"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
            <i data-lucide="printer" class="h-4 w-4"></i>
            <span class="hidden sm:inline">Print</span>
        </button>
        <button onclick="exportOrders()"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition">
            <i data-lucide="download" class="h-4 w-4"></i>
            <span class="hidden sm:inline">Export</span>
        </button>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">

        {{-- Status Tabs --}}
        @php
            $tabs = [
                ['key' => 'all',       'label' => 'All',       'count' => $statusCounts['all'],       'active' => 'bg-indigo-50 text-indigo-700 border-indigo-200',   'default' => 'bg-slate-50 text-slate-600 hover:bg-slate-100'],
                ['key' => 'pending',   'label' => 'Pending',   'count' => $statusCounts['pending'],   'active' => 'bg-amber-50 text-amber-700 border-amber-200',     'default' => 'bg-slate-50 text-slate-600 hover:bg-slate-100'],
                ['key' => 'confirmed', 'label' => 'Confirmed', 'count' => $statusCounts['confirmed'], 'active' => 'bg-blue-50 text-blue-700 border-blue-200',        'default' => 'bg-slate-50 text-slate-600 hover:bg-slate-100'],
                ['key' => 'shipped',   'label' => 'Shipped',   'count' => $statusCounts['shipped'],   'active' => 'bg-violet-50 text-violet-700 border-violet-200',  'default' => 'bg-slate-50 text-slate-600 hover:bg-slate-100'],
                ['key' => 'delivered', 'label' => 'Delivered', 'count' => $statusCounts['delivered'], 'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200','default' => 'bg-slate-50 text-slate-600 hover:bg-slate-100'],
                ['key' => 'cancelled', 'label' => 'Cancelled', 'count' => $statusCounts['cancelled'], 'active' => 'bg-rose-50 text-rose-700 border-rose-200',        'default' => 'bg-slate-50 text-slate-600 hover:bg-slate-100'],
            ];
            $currentStatus = request('status', 'all');
        @endphp

        <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-100">
            @foreach($tabs as $tab)
            @php $isActive = $currentStatus === $tab['key']; @endphp
            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => $tab['key']])) }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-sm font-medium border transition
                    {{ $isActive ? $tab['active'] . ' border' : $tab['default'] . ' border-transparent' }}">
                {{ $tab['label'] }}
                <span class="text-xs {{ $isActive ? 'opacity-80' : 'opacity-60' }}">{{ $tab['count'] }}</span>
            </a>
            @endforeach
        </div>

        {{-- Row 1: Search + Payment Status --}}
        <div class="grid sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by order #, customer name or phone…"
                    class="w-full h-10 pl-9 pr-4 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition placeholder:text-slate-400">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none"></i>
            </div>
            <select name="payment_status"
                class="h-10 px-3 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition text-slate-700">
                <option value="all">All payment status</option>
                <option value="pending"  {{ request('payment_status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="paid"     {{ request('payment_status') === 'paid'     ? 'selected' : '' }}>Paid</option>
                <option value="failed"   {{ request('payment_status') === 'failed'   ? 'selected' : '' }}>Failed</option>
            </select>
        </div>

        {{-- Row 2: Dates + Method + Actions --}}
        <div class="grid sm:grid-cols-4 gap-3 items-end">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-slate-500">From date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="h-10 px-3 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition text-slate-700">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-slate-500">To date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="h-10 px-3 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition text-slate-700">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-slate-500">Payment method</label>
                <select name="payment_method"
                    class="h-10 px-3 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition text-slate-700">
                    <option value="all">All methods</option>
                    <option value="cod"   {{ request('payment_method') === 'cod'   ? 'selected' : '' }}>Cash on delivery</option>
                    <option value="bkash" {{ request('payment_method') === 'bkash' ? 'selected' : '' }}>bKash</option>
                    <option value="nagad" {{ request('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 h-10 inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                    <i data-lucide="sliders-horizontal" class="h-4 w-4"></i>Apply
                </button>
                <a href="{{ route('admin.orders.index') }}"
                    class="h-10 w-10 inline-flex items-center justify-center border border-slate-200 text-slate-500 rounded-xl hover:bg-slate-50 transition">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Order</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Customer</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Items</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Total</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Payment</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($orders as $order)
                @php
                    $statusMap = [
                        'pending'   => ['pill' => 'bg-amber-50 text-amber-700',   'icon' => 'clock'],
                        'confirmed' => ['pill' => 'bg-blue-50 text-blue-700',     'icon' => 'check'],
                        'shipped'   => ['pill' => 'bg-violet-50 text-violet-700', 'icon' => 'truck'],
                        'delivered' => ['pill' => 'bg-emerald-50 text-emerald-700','icon' => 'package'],
                        'cancelled' => ['pill' => 'bg-rose-50 text-rose-700',     'icon' => 'x-circle'],
                    ];
                    $sm = $statusMap[$order->status->value] ?? ['pill' => 'bg-slate-100 text-slate-600', 'icon' => 'circle'];
                @endphp
                <tr class="transition hover:bg-slate-50/60">

                    {{-- Order --}}
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="shopping-bag" class="h-4 w-4 text-indigo-600"></i>
                            </div>
                            <div>
                                <a href="{{ route('admin.orders.show', $order->order_number) }}"
                                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                    {{ $order->order_number }}
                                </a>
                                <p class="text-xs text-slate-400">#{{ $order->id }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Customer --}}
                    <td class="px-5 py-3.5">
                        <p class="text-sm font-medium text-slate-900">{{ $order->shipping_name }}</p>
                        <p class="text-xs text-slate-400">{{ $order->shipping_phone }}</p>
                    </td>

                    {{-- Items --}}
                    <td class="px-5 py-3.5">
                        <span class="text-sm font-medium text-slate-700">{{ $order->items->count() }}</span>
                        <span class="text-xs text-slate-400 ml-0.5">item(s)</span>
                    </td>

                    {{-- Total --}}
                    <td class="px-5 py-3.5">
                        <span class="text-sm font-semibold text-slate-900">{{ money($order->total) }}</span>
                    </td>

                    {{-- Payment --}}
                    <td class="px-5 py-3.5">
                        <div class="flex flex-col gap-1.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 w-fit">
                                {{ $order->payment_method->label() }}
                            </span>
                            @if($order->payment_status->value === 'paid')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 w-fit">
                                <i data-lucide="check-circle" class="h-3 w-3"></i>Paid
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 w-fit">
                                <i data-lucide="clock" class="h-3 w-3"></i>{{ $order->payment_status->label() }}
                            </span>
                            @endif
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sm['pill'] }}">
                            <i data-lucide="{{ $sm['icon'] }}" class="h-3 w-3"></i>
                            {{ $order->status->label() }}
                        </span>
                    </td>

                    {{-- Date --}}
                    <td class="px-5 py-3.5">
                        <p class="text-sm font-medium text-slate-800">{{ $order->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-slate-400">{{ $order->created_at->format('h:i A') }}</p>
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('admin.orders.show', $order->order_number) }}"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition"
                            title="View order">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                                <i data-lucide="shopping-bag" class="h-6 w-6 text-slate-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">No orders found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your filters or search query</p>
                            </div>
                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition">
                                <i data-lucide="refresh-cw" class="h-3.5 w-3.5"></i>Clear filters
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="border-t border-slate-100 px-5 py-3.5">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    function exportOrders() {
        const params = new URLSearchParams(window.location.search);
        params.append('export', 'csv');
        window.location.href = `{{ route('admin.orders.index') }}?${params.toString()}`;
    }
</script>
@endpush

@endsection