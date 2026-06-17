@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Orders</h1>
        <p class="text-xs text-gray-500 mt-0.5">Manage and track all customer orders</p>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="window.print()" class="px-3.5 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
            <i class="fas fa-print"></i>
            <span>Print</span>
        </button>
        <button onclick="exportOrders()" class="px-3.5 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
    </div>
</div>

{{-- Filters & Search --}}
<div class="bg-white rounded-2xl border border-gray-200 p-5 mb-5 shadow-sm">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
        {{-- Status Tabs --}}
        <div class="flex flex-wrap gap-1.5 border-b border-gray-100 pb-3.5">
            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'all'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status', 'all') === 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                All ({{ $statusCounts['all'] }})
            </a>
            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                Pending ({{ $statusCounts['pending'] }})
            </a>
            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'confirmed'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'confirmed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                Confirmed ({{ $statusCounts['confirmed'] }})
            </a>
            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'shipped'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'shipped' ? 'bg-purple-100 text-purple-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                Shipped ({{ $statusCounts['shipped'] }})
            </a>
            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'delivered'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'delivered' ? 'bg-green-100 text-green-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                Delivered ({{ $statusCounts['delivered'] }})
            </a>
            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'cancelled'])) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                Cancelled ({{ $statusCounts['cancelled'] }})
            </a>
        </div>

        {{-- Search & Advanced Filters --}}
        <div class="grid md:grid-cols-4 gap-3.5">
            {{-- Search --}}
            <div class="md:col-span-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order #, customer name, or phone..." 
                        class="w-full h-10 pl-9 pr-4 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition">
                    <i class="fas fa-search absolute left-3.5 top-3.5 text-gray-400 text-xs"></i>
                </div>
            </div>

            {{-- Payment Status --}}
            <div>
                <select name="payment_status" class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs bg-white focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition">
                    <option value="all">All Payment Status</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            {{-- Payment Method --}}
            <div>
                <select name="payment_method" class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs bg-white focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition">
                    <option value="all">All Payment Methods</option>
                    <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                    <option value="bkash" {{ request('payment_method') === 'bkash' ? 'selected' : '' }}>bKash</option>
                    <option value="nagad" {{ request('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad</option>
                </select>
            </div>
        </div>

        {{-- Date Range --}}
        <div class="grid md:grid-cols-4 gap-3.5">
            <div>
                <label class="block text-[11px] font-bold text-gray-600 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                    class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-600 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                    class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition">
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="flex-1 h-10 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-xs font-semibold shadow-sm">
                    <i class="fas fa-filter mr-1.5"></i>Apply Filters
                </button>
                <a href="{{ route('admin.orders.index') }}" class="h-10 px-3.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition flex items-center justify-center text-xs shadow-sm" title="Reset Filters">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Orders Table Card --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Items</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/80 transition-colors">
                    {{-- Order Column --}}
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <a href="{{ route('admin.orders.show', $order->order_number) }}" class="font-bold text-blue-600 hover:text-blue-800 text-sm">
                            {{ $order->order_number }}
                        </a>
                        <p class="text-[10px] text-gray-400 font-mono mt-0.5">ID: #{{ $order->id }}</p>
                    </td>
                    
                    {{-- Customer Column --}}
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <p class="font-semibold text-gray-800 text-sm">{{ $order->shipping_name }}</p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $order->shipping_phone }}</p>
                    </td>
                    
                    {{-- Items Column --}}
                    <td class="px-5 py-3.5 whitespace-nowrap text-xs text-gray-700 font-medium">
                        {{ $order->items->count() }} item(s)
                    </td>
                    
                    {{-- Total Column --}}
                    <td class="px-5 py-3.5 whitespace-nowrap text-sm font-bold text-gray-800">
                        {{ money($order->total) }}
                    </td>
                    
                    {{-- Payment Column --}}
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-700 w-fit">
                                {{ $order->payment_method->label() }}
                            </span>
                            @if($order->payment_status->value === 'paid')
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-green-50 border border-green-200 text-green-700 w-fit">
                                <i class="fas fa-check-circle text-[9px]"></i> Paid
                            </span>
                            @else
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-yellow-50 border border-yellow-200 text-yellow-700 w-fit">
                                <i class="fas fa-clock text-[9px]"></i> {{ $order->payment_status->label() }}
                            </span>
                            @endif
                        </div>
                    </td>
                    
                    {{-- Status Column --}}
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        @php
                        $statusColors = [
                            'pending' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
                            'confirmed' => 'bg-blue-50 border-blue-200 text-blue-700',
                            'shipped' => 'bg-purple-50 border-purple-200 text-purple-700',
                            'delivered' => 'bg-green-50 border-green-200 text-green-700',
                            'cancelled' => 'bg-red-50 border-red-200 text-red-700',
                        ];
                        $statusIcons = [
                            'pending' => 'fa-clock',
                            'confirmed' => 'fa-check',
                            'shipped' => 'fa-truck',
                            'delivered' => 'fa-box-open',
                            'cancelled' => 'fa-times-circle',
                        ];
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold border {{ $statusColors[$order->status->value] ?? 'bg-gray-50 border-gray-200 text-gray-700' }}">
                            <i class="fas {{ $statusIcons[$order->status->value] ?? 'fa-circle' }} text-[10px]"></i>
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    
                    {{-- Date Column --}}
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <p class="text-xs font-semibold text-gray-700">{{ $order->created_at->format('M d, Y') }}</p>
                        <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $order->created_at->format('h:i A') }}</p>
                    </td>
                    
                    {{-- Actions Column --}}
                    <td class="px-5 py-3.5 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('admin.orders.show', $order->order_number) }}" 
                                class="w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-50 rounded-lg border border-transparent hover:border-blue-100 transition shadow-sm" 
                                title="View Details">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100">
                                <i class="fas fa-shopping-bag text-gray-400 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-base font-bold text-gray-900">No orders found</p>
                                <p class="text-xs text-gray-500 mt-0.5">Try adjusting your search query or filters</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="border-t border-gray-100 px-5 py-3.5 bg-gray-50/50">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    function printOrder(orderId) {
        window.open(`/admin/orders/${orderId}/print`, '_blank');
    }

    function exportOrders() {
        const params = new URLSearchParams(window.location.search);
        params.append('export', 'csv');
        window.location.href = `{{ route('admin.orders.index') }}?${params.toString()}`;
    }
</script>
@endpush
@endsection