@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- Page Header --}}
{{--<div class="mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
    <p class="text-gray-500">Welcome back! Here's what's happening with your store today.</p>
</div>--}}

{{-- Quick Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Total Revenue --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-white text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">+12.5%</span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium mb-1">Total Revenue</h3>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ money($widgets['totalRevenue']) }}</p>
        <p class="text-xs text-gray-400">vs last month</p>
    </div>

    {{-- Total Orders --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-bag text-white text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">+8.2%</span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium mb-1">Total Orders</h3>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($widgets['totalOrders'], 0) }}</p>
        <p class="text-xs text-gray-400">vs last month</p>
    </div>

    {{-- Total Customers --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">+15.3%</span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium mb-1">Total Customers</h3>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($widgets['totalCustomers'], 0) }}</p>
        <p class="text-xs text-gray-400">vs last month</p>
    </div>

    {{-- Pending Orders --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-white text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-3 py-1 rounded-full">Needs Attention</span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium mb-1">Pending Orders</h3>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($widgets['pendingOrders'], 0) }}</p>
        <p class="text-xs text-gray-400">awaiting processing</p>
    </div>
</div>

{{-- Charts and Recent Activity --}}
<div class="grid lg:grid-cols-3 gap-6 mb-8">
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-1">Revenue Overview</h2>
                <p class="text-sm text-gray-500">Monthly revenue for the past 6 months</p>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg">6M</button>
                <button class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 rounded-lg">1Y</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Top Products</h2>
        <p class="text-sm text-gray-500 mb-6">Best selling products this month</p>

        <div class="space-y-4">
            @forelse($topProducts ?? [] as $product)
            <div class="flex items-center gap-3">
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $product['sales'] }} sold</p>
                </div>
                <span class="text-sm font-bold text-gray-900">৳{{ number_format($product['revenue'], 0) }}</span>
            </div>
            @empty
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-box text-gray-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">No products available</p>
                </div>
            </div>
            @endforelse
        </div>

        <a href="{{ route('admin.products.index') }}" class="mt-6 flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
            <span>View All Products</span>
            <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </div>
</div>

{{-- Recent Orders and Activity --}}
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Recent Orders --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Recent Orders</h2>
                    <p class="text-sm text-gray-500">Latest orders from your store</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition">View All</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders ?? [] as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">#{{ $order['id'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($order['customer_name'], 0, 1) }}
                                </div>
                                <span class="text-sm text-gray-900">{{ $order['customer_name'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order['created_at'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ৳{{ number_format($order['total'], 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order['status'] === 'pending')
                            <span class="px-3 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-full">Pending</span>
                            @elseif($order['status'] === 'processing')
                            <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Processing</span>
                            @elseif($order['status'] === 'completed')
                            <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Completed</span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('admin.orders.show', $order['id']) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            No recent orders available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions & Stats --}}
    <div class="space-y-6">
        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Quick Actions</h2>
            <p class="text-sm text-gray-500 mb-6">Common tasks and shortcuts</p>

            <div class="space-y-3">
                <a href="{{ route('admin.products.create') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Add Product</p>
                        <p class="text-xs text-gray-500">Create new product</p>
                    </div>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">View Orders</p>
                        <p class="text-xs text-gray-500">Manage all orders</p>
                    </div>
                </a>
                <a href="{{ route('admin.coupons.create') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-xl transition group">
                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Create Coupon</p>
                        <p class="text-xs text-gray-500">New discount code</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <span class="text-xs font-bold bg-white/20 backdrop-blur px-3 py-1 rounded-full">Alert</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Low Stock Products</h3>
            <p class="text-sm text-white/90 mb-4">{{ $lowStockCount ?? 8 }} products are running low on stock</p>
            <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-orange-600 text-sm font-semibold rounded-lg hover:bg-orange-50 transition">
                <span>View Products</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
                datasets: [{
                    label: 'Revenue',
                    data: [45000, 52000, 48000, 61000, 55000, 68000],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgb(17, 24, 39)',
                        padding: 12,
                        titleColor: 'rgb(255, 255, 255)',
                        bodyColor: 'rgb(255, 255, 255)',
                        borderColor: 'rgb(75, 85, 99)',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '৳' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                        },
                        ticks: {
                            callback: function(value) {
                                return '৳' + (value / 1000) + 'k';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
</script>
@endpush