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
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">

            {{-- Total Revenue --}}
            <div
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-1 flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs font-medium uppercase tracking-wider text-blue-100">Total Revenue</p>
                    </div>
                    <h3 class="mb-2 text-3xl font-bold text-white">{{ money($totalRevenue) }}</h3>
                    <div
                        class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($revenueGrowth >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        @endif
                        {{ abs($revenueGrowth) }}%
                    </div>
                </div>
            </div>

            {{-- Orders --}}
            <div
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-1 flex items-center gap-2">
                        <svg class="h-5 w-5 text-cyan-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-xs font-medium uppercase tracking-wider text-cyan-100">Total Orders</p>
                    </div>
                    <h3 class="mb-2 text-3xl font-bold text-white">{{ number_format($totalOrder) }}</h3>
                    <div
                        class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($orderGrowth >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        @endif
                        {{ abs($orderGrowth) }}%
                    </div>
                </div>
            </div>

            {{-- Average Order Value --}}
            <div
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-1 flex items-center gap-2">
                        <svg class="h-5 w-5 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xs font-medium uppercase tracking-wider text-amber-100">Avg Order Value</p>
                    </div>
                    <h3 class="mb-2 text-3xl font-bold text-white">{{ money($avgOrder) }}</h3>
                    <div
                        class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($avgOrderGrowth >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        @endif
                        {{ abs($avgOrderGrowth) }}%
                    </div>
                </div>
            </div>

            {{-- Growth Rate --}}
            <div
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-1 flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <p class="text-xs font-medium uppercase tracking-wider text-emerald-100">Growth Rate</p>
                    </div>
                    <h3 class="mb-2 text-3xl font-bold text-white">{{ $avgOrderGrowth }}%</h3>
                    <p class="text-xs text-emerald-100">Period comparison</p>
                </div>
            </div>

            {{-- Refund Rate --}}
            <div
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-1 flex items-center gap-2">
                        <svg class="h-5 w-5 text-rose-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                        </svg>
                        <p class="text-xs font-medium uppercase tracking-wider text-rose-100">Total Refund Items</p>
                    </div>
                    <h3 class="mb-2 text-3xl font-bold text-white">{{ $totalRefundItems }}</h3>
                    <p class="text-xs text-rose-100">Of total orders</p>
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
            <canvas id="revenueTrendChart" height="80"></canvas>
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

                    <div class="flex items-center justify-center">
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