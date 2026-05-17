@extends('admin.layouts.app')

@section('title', 'Report Overview')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Business Overview
                </h2>

                <nav class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                    <span>Reports</span>
                    <span>/</span>
                    <span class="font-semibold text-slate-700">
                        Business Overview
                    </span>
                </nav>
            </div>

            {{-- Filter --}}
            <form method="GET" class="flex flex-col lg:flex-row gap-3 lg:items-end">

                <div class="w-full sm:w-44">
                    <select name="range" onchange="toggleCustomDates(this.value)"
                        class="w-full h-11 px-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>
                            Daily
                        </option>

                        <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>
                            Weekly
                        </option>

                        <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>
                            Monthly
                        </option>

                        <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>
                            Yearly
                        </option>

                        <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>
                            Custom
                        </option>
                    </select>
                </div>

                <div id="customDateRange"
                    class="{{ request('range') == 'custom' ? 'flex' : 'hidden' }} flex-col sm:flex-row gap-2">

                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="h-11 px-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">

                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="h-11 px-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="h-11 px-5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium flex items-center justify-center whitespace-nowrap">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </form>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">

            {{-- Total Sales --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-shopping-bag text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-blue-100">Total Sales</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ money($calculateMetrics['totalSales']) }}</h3>
                    <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($calculateMetrics['salesGrowth'] >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($calculateMetrics['salesGrowth']), 2) }}%
                    </div>
                </div>
            </div>

            {{-- Orders --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-clipboard-list text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-cyan-100">Total Orders</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ number_format($calculateMetrics['totalOrders']) }}</h3>
                    <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($calculateMetrics['ordersGrowth'] >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($calculateMetrics['ordersGrowth']), 2) }}%
                    </div>
                </div>
            </div>

            {{-- Net Profit --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-dollar-sign text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-emerald-100">Net Profit</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ money($calculateMetrics['netProfit']) }}</h3>
                    <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($calculateMetrics['profitGrowth'] >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($calculateMetrics['profitGrowth']), 2) }}%
                    </div>
                </div>
            </div>

            {{-- Returning Customers --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-users text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-amber-100">Returning Customers</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ number_format($quickFacts['returningCustomersPercent'], 2) }}%</h3>
                    <p class="text-xs text-amber-100">Customer retention rate</p>
                </div>
            </div>

            {{-- AOV (Average Order Value) --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-basket-shopping text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-violet-100">Avg Order Value</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ money($calculateMetrics['aov']) }}</h3>
                    <p class="text-xs text-violet-100">Per transaction</p>
                </div>
            </div>

            {{-- Total Stock --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-500 to-slate-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-boxes-stacked text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-slate-100">Total Stock</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ number_format($calculateMetrics['totalStock']) }}</h3>
                    <p class="text-xs text-slate-100">Units in inventory</p>
                </div>
            </div>

        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-lg font-bold text-slate-800 mb-5">Revenue & Order Trends</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Revenue Trend Chart -->
                    <div>
                        <p class="text-sm font-semibold text-slate-500 mb-2">
                            {{ request('range') }} Revenue Trend
                        </p>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 h-80">
                            <canvas id="revenueTrend"></canvas>
                        </div>
                    </div>

                    <!-- Orders vs Returns Bar Chart -->
                    <div>
                        <p class="text-sm font-semibold text-slate-500 mb-2">
                            Orders vs Returns
                        </p>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 h-80 relative">
                            <canvas id="ordersReturns"></canvas>

                            <!-- Return Rate Badge -->
                            <div
                                class="absolute top-4 right-4 bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                {{ $ordersReturnsChart['return_rate'] }}% Return Rate
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Quick Facts --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">

                <h3 class="text-lg font-bold text-slate-800 mb-5">
                    Quick Facts
                </h3>

                <div class="space-y-4">

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">
                            Total Orders
                        </span>

                        <span class="font-bold text-blue-600">
                            {{ $quickFacts['totalOrders'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">
                            Refund Rate
                        </span>

                        <span class="font-bold text-red-500">
                            {{ $quickFacts['refundRate'] }}%
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500">
                            Best Sales Day
                        </span>

                        <span class="font-bold text-emerald-600 text-right">
                            {{ $quickFacts['bestSalesDay'] ?? '-' }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-5 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800">
                    Top Product Snapshot
                </h3>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Product
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Units Sold
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Sales
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Stock
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach ($topProducts as $product)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-5 py-4 font-medium text-slate-700">
                                    {{ $product['name'] }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    {{ $product['unitsSold'] }}
                                </td>

                                <td class="px-5 py-4 text-right font-semibold text-emerald-600">
                                    {{ money($product['sales']) }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    {{ $product['stock'] }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>
        </div>

    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            function toggleCustomDates(value) {
                const custom = document.getElementById('customDateRange');
                custom.style.display = (value === 'custom') ? 'block' : 'none';
            }

            const revenueCtx = document.getElementById('revenueTrend');

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($chartData['revenueTrend']['labels']),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($chartData['revenueTrend']['values']),
                        borderWidth: 2
                    }]
                }
            });


            // Orders vs Returns Bar Chart
            const ordersReturnsCtx = document.getElementById('ordersReturns').getContext('2d');

            const ordersReturnsChart = new Chart(ordersReturnsCtx, {
                type: 'bar',
                data: {
                    labels: @json($ordersReturnsChart['labels']),
                    datasets: [{
                        label: 'Order Count',
                        data: @json($ordersReturnsChart['data']),
                        backgroundColor: @json($ordersReturnsChart['colors']),
                        borderColor: @json($ordersReturnsChart['colors']),
                        borderWidth: 1,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Hide legend since colors explain the bars
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const total = {{ $ordersReturnsChart['total_orders'] }};
                                    const value = context.raw;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${value} orders (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e2e8f0',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: { size: 11 }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: { size: 12, weight: '600' }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });

        </script>
    @endpush

@endsection