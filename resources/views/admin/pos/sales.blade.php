@extends('admin.layouts.app')

@section('title', 'POS Orders')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary">POS Orders</h1>
            <p class="text-sm text-secondary-500 mt-1">Manage POS sales, drafts & completed orders</p>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                class="px-4 py-2 border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition flex items-center gap-2">
                <i class="fas fa-print"></i>
                <span class="hidden sm:inline">Print</span>
            </button>

            <button onclick="exportPosOrders()"
                class="px-4 py-2 bg-success text-white rounded-xl hover:bg-success-700 transition flex items-center gap-2">
                <i class="fas fa-download"></i>
                <span class="hidden sm:inline">Export</span>
            </button>
        </div>
    </div>

    {{-- Filters & Search (MATCH ORDERS PAGE STYLE) --}}
    <div class="bg-white rounded-2xl border border-secondary-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.pos.sales.index') }}" class="space-y-4">

            {{-- Status Tabs --}}
            <div class="flex flex-wrap gap-2 border-b border-secondary-200 pb-4">

                <a href="{{ route('admin.pos.sales.index', array_merge(request()->except('status'), ['status' => 'all'])) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition
                       {{ request('status', 'all') === 'all' ? 'bg-primary-100 text-primary' : 'bg-secondary-100 text-secondary-600 hover:bg-gray-200' }}">
                    All ({{ $statusCounts['all'] ?? 0 }})
                </a>

                <a href="{{ route('admin.pos.sales.index', array_merge(request()->except('status'), ['status' => 'draft'])) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition
                       {{ request('status') === 'draft' ? 'bg-warning-100 text-warning' : 'bg-secondary-100 text-secondary-600 hover:bg-gray-200' }}">
                    Draft ({{ $statusCounts['draft'] ?? 0 }})
                </a>

                <a href="{{ route('admin.pos.sales.index', array_merge(request()->except('status'), ['status' => 'delivered'])) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition
                       {{ request('status') === 'delivered' ? 'bg-success-100 text-success' : 'bg-secondary-100 text-secondary-600 hover:bg-gray-200' }}">
                    Delivered ({{ $statusCounts['delivered'] ?? 0 }})
                </a>

                <a href="{{ route('admin.pos.sales.index', array_merge(request()->except('status'), ['status' => 'cancelled'])) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition
                       {{ request('status') === 'cancelled' ? 'bg-danger-100 text-danger' : 'bg-secondary-100 text-secondary-600 hover:bg-gray-200' }}">
                    Cancelled ({{ $statusCounts['cancelled'] ?? 0 }})
                </a>

            </div>

            {{-- Search & Filters (SAME STRUCTURE AS ORDERS) --}}
            <div class="grid md:grid-cols-4 gap-4">

                <div class="md:col-span-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by order #, customer name..."
                            class="w-full h-11 pl-10 pr-4 border border-secondary-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-secondary-400"></i>
                    </div>
                </div>

                <div>
                    <select name="payment_status"
                        class="w-full h-11 px-4 border border-secondary-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="all">All Payment Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                <div>
                    <select name="payment_method"
                        class="w-full h-11 px-4 border border-secondary-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="all">All Methods</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs text-secondary-500">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full h-11 px-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary">
                </div>

                <div>
                    <label class="text-xs text-secondary-500">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full h-11 px-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary">
                </div>

            </div>

            <div class="flex justify-end gap-2">
                <button type="submit" class="h-11 px-6 bg-primary text-white rounded-xl hover:bg-primary-700 transition">
                    Apply Filters
                </button>
                <a href="{{ route('admin.pos.sales.index') }}"
                    class="h-11 px-4 border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition flex items-center">
                    Clear
                </a>
            </div>

        </form>
    </div>

    {{-- TABLE (MATCH ORDERS DESIGN EXACTLY) --}}
    <div class="bg-white rounded-2xl border border-secondary-200 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase">Items</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($orders as $order)
                        <tr class="hover:bg-secondary-50 transition">

                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-cash-register text-primary"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-primary">#{{ $order->order_number ?? $order->id }}</p>
                                                    <p class="text-xs text-secondary-500">ID: {{ $order->id }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="font-medium text-primary">
                                                {{ $order->shipping_name ?? 'Walk-in Customer' }}
                                            </p>
                                            <p class="text-sm text-secondary-500">
                                                {{ $order->shipping_phone ?? '-' }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-primary">
                                                {{ $order->items->count() }} item(s)
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-base font-bold text-primary">
                                                {{ money($order->total) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'draft' => 'bg-warning-100 text-warning',
                                                    'delivered' => 'bg-success-100 text-success',
                                                    'cancelled' => 'bg-danger-100 text-danger',
                                                ];
                                            @endphp

                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        {{ $statusColors[$order->status->value] ?? 'bg-secondary-100 text-secondary-700' }}">
                                                {{ $order->status->label() }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-medium text-primary">
                                                {{ $order->created_at->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-secondary-500">
                                                {{ $order->created_at->format('h:i A') }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.pos.sales.show',  $order->order_number) }}" class="w-8 h-8 flex items-center justify-center text-primary hover:bg-primary-50 rounded-lg">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                </a>
                                                <a href="{{ route('admin.pos.index', ['order_number' => $order->order_number]) }}"
                                                    class="w-8 h-8 flex items-center justify-center text-primary hover:bg-primary-50 rounded-lg">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                            </div>
                                        </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-secondary-500">
                                No POS orders found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        @if($orders->hasPages())
            <div class="border-t border-secondary-200 px-6 py-4">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

@endsection