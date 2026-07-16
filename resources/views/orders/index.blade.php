@extends('customer.layout')
@section('title', 'Orders')

@section('dashboard-content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-primary">Order History</h2>
                <p class="text-sm text-secondary mt-1">Track and manage your orders</p>
            </div>
        </div>
        <div class="space-y-4">
            @if ($orders->isEmpty())
                <!-- Empty State -->
                <div class="bg-surface-elevated rounded-2xl shadow-sm border border-primary-100 p-12 text-center">
                    <div class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-primary-100">
                        <svg class="h-8 w-8 text-secondary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-2">No Orders Yet</h3>
                    <p class="text-secondary mb-6">Start shopping to see your orders here</p>
                    <a href="/"
                        class="inline-block bg-primary text-surface-elevated px-6 py-3 rounded-xl font-bold hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-lg shadow-primary/20">Continue
                        Shopping</a>
                </div>
            @else
                <!-- Orders Grid for Mobile & Table for Desktop -->
                <div class="hidden lg:block bg-surface-elevated rounded-2xl shadow-sm border border-primary-100 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-light border-b border-primary-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold text-primary">Order ID</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-primary">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-primary">Items</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-primary">Status</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-primary">Total</th>
                                <th class="px-6 py-4 text-center text-sm font-bold text-primary">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-light transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-primary">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-secondary">{{ $order->created_at->format('M d, Y h:i a') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-secondary">
                                        {{ $order->items_count ?? ($order->items ? (is_countable($order->items) ? count($order->items) : 0) : 0) }}
                                        item(s)</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold text-{{ $order->status->color() }}-600 bg-{{ $order->status->color() }}-50 border border-{{ $order->status->color() }}-100">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-primary">{{ money($order->total) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('orders.show', $order->order_number) }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary text-surface-elevated rounded-lg hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 text-sm font-bold shadow-sm hover:shadow-md">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-4">
                    @foreach ($orders as $order)
                        <div class="bg-surface-elevated rounded-2xl shadow-sm p-6 border border-primary-100 hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-xs text-secondary uppercase tracking-wide mb-1 font-bold">Order</p>
                                    <p class="font-bold text-lg text-primary">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold text-{{ $order->status->color() }}-600 bg-{{ $order->status->color() }}-50 border border-{{ $order->status->color() }}-100">
                                    {{ $order->status->label() }}
                                </span>
                            </div>

                            <div class="space-y-3 mb-6 pb-6 border-b border-primary-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-secondary">Date</span>
                                    <span class="font-semibold text-primary">{{ $order->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-secondary">Items</span>
                                    <span
                                        class="font-semibold text-primary">{{ $order->items_count ?? ($order->items ? (is_countable($order->items) ? count($order->items) : 0) : 0) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-secondary">Total</span>
                                    <span class="font-bold text-lg text-primary">{{ money($order->total) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('orders.show', $order->order_number) }}"
                                class="block w-full bg-primary text-surface-elevated py-3 rounded-xl font-bold text-center hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-lg shadow-primary/20">
                                View Details
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection