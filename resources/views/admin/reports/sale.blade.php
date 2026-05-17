@extends('admin.layouts.app')

@section('title', 'Sales Reports')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">Sales Report</h1>

                <nav class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                    <span>Reports</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="font-medium text-slate-900">Sales Report</span>
                </nav>
            </div>

            {{-- Filter --}}
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">

                <select name="range" onchange="toggleCustomDates(this.value)"
                    class="w-full rounded-lg border-2 border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-slate-300 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:w-48">

                    <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>

                <div id="customDateRange" class="{{ request('range') == 'custom' ? 'flex' : 'hidden' }} gap-2">

                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="rounded-lg border-2 border-slate-200 px-4 py-2.5 text-sm font-medium transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="rounded-lg border-2 border-slate-200 px-4 py-2.5 text-sm font-medium transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>

                <button type="submit"
                    class="h-11 px-5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium flex items-center justify-center whitespace-nowrap">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </form>
        </header>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5 mb-8">

            {{-- Total Revenue --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-sack-dollar text-blue-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Revenue</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ money($totalRevenue) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs">
                    <span class="{{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        <i class="fas {{ $revenueGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($revenueGrowth), 2) }}%
                    </span>
                </div>
            </div>

            {{-- Orders --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-cyan-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-cyan-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Orders</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ number_format($totalOrder) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs">
                    <span class="{{ $orderGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        <i class="fas {{ $orderGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($orderGrowth), 2) }}%
                    </span>
                </div>
            </div>

            {{-- Average Order Value --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-amber-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-chart-line text-amber-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Avg Order Value</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ money($avgOrder) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs">
                    <span class="{{ $avgOrderGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        <i class="fas {{ $avgOrderGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($avgOrderGrowth), 2) }}%
                    </span>
                </div>
            </div>

            {{-- Growth Rate --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-emerald-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-arrow-trend-up text-emerald-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Growth Rate</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ $avgOrderGrowth }}%
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs text-gray-500">
                    Period comparison
                </div>
            </div>

            {{-- Refund Items --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-rose-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center">
                        <i class="fas fa-rotate-left text-rose-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Refund Items</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ number_format($totalRefundItems) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs text-gray-500">
                    Returned products count
                </div>
            </div>

        </div>

        {{-- Revenue Chart --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Revenue Trend</h3>
                    <p class="text-sm text-slate-500">Track your revenue performance over time</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <canvas id="revenueTrendChart" height="80"></canvas>
            </div>
        </div>

        {{-- Middle Section --}}
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Category Performance --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Category Performance</h3>
                    <p class="text-sm text-slate-500">Sales breakdown by category</p>
                </div>

                <div class="grid gap-6 md:grid-cols-2">

                    <div class="flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <canvas id="categoryPieChart"></canvas>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-slate-200">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr class="text-slate-600">
                                    <th class="px-4 py-3 text-left font-semibold">Category</th>
                                    <th class="px-4 py-3 text-right font-semibold">Sales</th>
                                    <th class="px-4 py-3 text-right font-semibold">Orders</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @foreach ($categoryData ?? [] as $data)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-4 py-3 font-medium text-slate-900">{{ $data['category'] }}</td>
                                        <td class="px-4 py-3 text-right text-slate-700">{{ money($data['sales']) }}</td>
                                        <td class="px-4 py-3 text-right text-slate-700">{{ $data['orders'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- Channel Contribution --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Channel Contribution</h3>
                    <p class="text-sm text-slate-500">Revenue distribution across channels</p>
                </div>

                <div class="overflow-hidden rounded-lg border border-slate-200">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-slate-600">
                                <th class="px-4 py-3 text-left font-semibold">Channel</th>
                                <th class="px-4 py-3 text-right font-semibold">Revenue</th>
                                <th class="px-4 py-3 text-right font-semibold">Orders</th>
                                <th class="px-4 py-3 text-right font-semibold">Share</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($channelData ?? [] as $data)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $data['channel'] }}</td>
                                    <td class="px-4 py-3 text-right text-slate-700">{{ money($data['revenue']) }}</td>
                                    <td class="px-4 py-3 text-right text-slate-700">{{ $data['orders'] }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <div class="h-2 w-16 overflow-hidden rounded-full bg-slate-100">
                                                <div class="h-full bg-blue-600" style="width: {{ $data['contribution'] }}%">
                                                </div>
                                            </div>
                                            <span class="font-semibold text-slate-900">{{ $data['contribution'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Top Products --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Top Products</h3>
                <p class="text-sm text-slate-500">Best performing products by sales volume</p>
            </div>

            <div class="overflow-hidden rounded-lg border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-slate-600">
                            <th class="px-4 py-3 text-left font-semibold">Product</th>
                            <th class="px-4 py-3 text-right font-semibold">Price</th>
                            <th class="px-4 py-3 text-right font-semibold">Units Sold</th>
                            <th class="px-4 py-3 text-right font-semibold">Total Sales</th>
                            <th class="px-4 py-3 text-right font-semibold">Margin</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($productStats ?? [] as $prod)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $prod['product_name'] }}</td>
                                <td class="px-4 py-3 text-right text-slate-700">{{ money($prod['price']) }}</td>
                                <td class="px-4 py-3 text-right text-slate-700">{{ number_format($prod['units_sold']) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="font-semibold text-green-600">{{ money($prod['total_sales']) }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                        {{ $prod['profit_margin'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function toggleCustomDates(value) {
            const el = document.getElementById('customDateRange');
            el.classList.toggle('hidden', value !== 'custom');
            el.classList.toggle('flex', value === 'custom');
        }

        // Revenue Trend Chart
        new Chart(document.getElementById('revenueTrendChart'), {
            type: 'line',
            data: {
                labels: @json($labels ?? []),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenues ?? []),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Category Pie Chart
        new Chart(document.getElementById('categoryPieChart'), {
            type: 'doughnut',
            data: {
                labels: @json($categoryData->pluck('category') ?? []),
                datasets: [{
                    data: @json($categoryData->pluck('sales') ?? []),
                    backgroundColor: [
                        '#2563eb',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#ec4899'
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    </script>
@endpush