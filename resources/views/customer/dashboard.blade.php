@extends('customer.layout')
@section('title', 'Dashboard')

@section('dashboard-content')
    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Orders -->
        <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-6 hover:shadow-xl transition-shadow border border-secondary-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-400 text-sm font-medium">Total Orders</p>
                    <h3 class="text-3xl font-bold text-primary mt-1">{{ $stats['total_orders'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center shadow-sm">
                    <i class="fas fa-shopping-bag text-primary-500 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('orders.index') }}"
                class="text-primary-500 text-sm font-medium mt-4 inline-flex items-center gap-1 hover:gap-2 transition-all hover:text-primary-700">
                View all <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Pending Orders -->
        <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 p-6 hover:shadow-xl transition-shadow border border-secondary-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-400 text-sm font-medium">Pending Orders</p>
                    <h3 class="text-3xl font-bold text-primary mt-1">{{ $stats['pending_orders'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-full flex items-center justify-center shadow-sm">
                    <i class="fas fa-clock text-warning-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('orders.index') }}"
                class="text-warning-600 text-sm font-medium mt-4 inline-flex items-center gap-1 hover:gap-2 transition-all">
                View pending <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-surface rounded-2xl shadow-lg shadow-secondary-200/50 overflow-hidden border border-secondary-100">
        <div class="px-6 py-4 border-b border-secondary-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-primary">Recent Orders</h2>
            <a href="{{ route('orders.index') }}" class="text-primary-500 text-sm font-medium hover:text-primary-700 transition-colors">
                View all orders
            </a>
        </div>

        @if($recent_orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-light border-b border-secondary-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-500 uppercase tracking-wider">
                                Order Number
                            </th>
                            <th
                                class="hidden md:table-cell px-6 py-3 text-left text-xs font-semibold text-secondary-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th
                                class="hidden sm:table-cell px-6 py-3 text-left text-xs font-semibold text-secondary-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-secondary-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-200">
                        @foreach($recent_orders as $order)
                            <tr class="hover:bg-light transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-primary">#{{ $order->order_number }}</p>
                                    <p class="text-sm text-secondary-400 md:hidden">{{ $order->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="hidden md:table-cell px-6 py-4 text-sm text-secondary-500">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-warning-100 text-warning-800 border-warning-200',
                                            'processing' => 'bg-primary-100 text-primary-800 border-primary-200',
                                            'shipped' => 'bg-secondary-100 text-secondary-800 border-secondary-200',
                                            'delivered' => 'bg-success-100 text-success-800 border-success-200',
                                            'cancelled' => 'bg-danger-100 text-danger-800 border-danger-200',
                                        ];
                                        $colorClass = $statusColors[$order->status->value] ?? 'bg-secondary-100 text-secondary-800 border-secondary-200';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full border {{ $colorClass }}">
                                        {{ ucfirst($order->status->value) }}
                                    </span>
                                </td>
                                <td class="hidden sm:table-cell px-6 py-4 text-sm font-semibold text-primary">
                                    ৳{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('orders.show', $order->order_number) }}"
                                        class="inline-flex items-center gap-1 text-primary-500 hover:text-primary-700 font-medium text-sm transition-colors">
                                        View <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-light rounded-full mb-4 border border-secondary-100">
                    <i class="fas fa-shopping-bag text-secondary-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-primary mb-2">No Orders Yet</h3>
                <p class="text-secondary-500 mb-6">Start shopping and your orders will appear here.</p>
                <a href="{{ route('products.index') }}"
                    class="inline-block bg-primary text-surface-elevated px-6 py-3 rounded-xl font-medium hover:bg-primary-700 transition shadow-lg shadow-primary-200/50">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
@endsection