@extends('admin.layouts.app')

@section('title', 'Report Overview')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

            <div>
                <h2 class="text-2xl font-bold text-secondary-800">
                    Business Overview
                </h2>

                <nav class="flex items-center gap-2 text-sm text-secondary-500 mt-1">
                    <span>Reports</span>
                    <span>/</span>
                    <span class="font-semibold text-secondary-700">
                        Business Overview
                    </span>
                </nav>
            </div>

            {{-- Filter --}}
            <form method="GET" class="flex flex-col lg:flex-row gap-3 lg:items-end">

                <div class="w-full sm:w-44">
                    <select name="range" onchange="toggleCustomDates(this.value)"
                        class="w-full h-11 px-3 rounded-xl border border-secondary-300 focus:ring-2 focus:ring-primary focus:border-primary">
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
                        class="h-11 px-3 rounded-xl border border-secondary-300 focus:ring-2 focus:ring-primary">

                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="h-11 px-3 rounded-xl border border-secondary-300 focus:ring-2 focus:ring-primary">
                </div>

                <button type="submit"
                    class="h-11 px-5 bg-primary text-white rounded-xl hover:bg-primary-700 transition font-medium flex items-center justify-center whitespace-nowrap">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </form>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

            {{-- Sales --}}
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-primary p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-primary text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-secondary-500 font-medium">
                            Total Sales
                        </p>

                        <p class="text-lg font-bold text-secondary-800">
                            {{ money($calculateMetrics['totalSales']) }}
                        </p>
                    </div>
                </div>

                <div class="mt-2 flex items-center text-xs">
                    <span
                        class="{{ $calculateMetrics['salesGrowth'] >= 0 ? 'text-success' : 'text-danger' }} font-semibold">
                        <i
                            class="fas {{ $calculateMetrics['salesGrowth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($calculateMetrics['salesGrowth']), 2) }}%
                    </span>
                    <span class="text-secondary-400 ml-1">vs last {{ request('range') }}</span>
                </div>
            </div>

            {{-- Orders --}}
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-accent p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-accent-50 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-accent text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-secondary-500 font-medium">
                            Orders
                        </p>

                        <p class="text-lg font-bold text-secondary-800">
                            {{ $calculateMetrics['totalOrders'] }}
                        </p>
                    </div>
                </div>

                <div class="mt-2 flex items-center text-xs">
                    <span
                        class="{{ $calculateMetrics['ordersGrowth'] >= 0 ? 'text-success' : 'text-danger' }} font-semibold">
                        <i
                            class="fas {{ $calculateMetrics['ordersGrowth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($calculateMetrics['ordersGrowth']), 2) }}%
                    </span>
                    <span class="text-secondary-400 ml-1">vs last {{ request('range') }}</span>
                </div>
            </div>

            {{-- Net Profit --}}
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-success p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-success-50 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-success text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-secondary-500 font-medium">
                            Net Profit
                        </p>

                        <p class="text-lg font-bold text-secondary-800">
                            {{ money($calculateMetrics['netProfit']) }}
                        </p>
                    </div>
                </div>

                <div class="mt-2 flex items-center text-xs">
                    <span
                        class="{{ $calculateMetrics['profitGrowth'] >= 0 ? 'text-success' : 'text-danger' }} font-semibold">
                        <i
                            class="fas {{ $calculateMetrics['profitGrowth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($calculateMetrics['profitGrowth']), 2) }}%
                    </span>
                    <span class="text-secondary-400 ml-1">vs last {{ request('range') }}</span>
                </div>
            </div>

            {{-- Returning Customers --}}
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-warning p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-warning-50 flex items-center justify-center">
                        <i class="fas fa-users text-warning text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-secondary-500 font-medium">
                            Ret. Customers
                        </p>

                        <p class="text-lg font-bold text-secondary-800">
                            {{ number_format($quickFacts['returningCustomersPercent'], 2) }}%
                        </p>
                    </div>
                </div>
            </div>

            {{-- AOV --}}
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-accent p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-accent-50 flex items-center justify-center">
                        <i class="fas fa-basket-shopping text-accent text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-secondary-500 font-medium">
                            AOV
                        </p>

                        <p class="text-lg font-bold text-secondary-800">
                            {{ money($calculateMetrics['aov']) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Stock --}}
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-gray-500 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-secondary-50 flex items-center justify-center">
                        <i class="fas fa-boxes-stacked text-secondary-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-secondary-500 font-medium">
                            Total Stock
                        </p>

                        <p class="text-lg font-bold text-secondary-800">
                            {{ $calculateMetrics['totalStock'] }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <div class="lg:col-span-2 bg-white rounded-2xl border border-secondary-200 shadow-sm p-5">
                <h3 class="text-lg font-bold text-secondary-800 mb-5">Revenue & Order Trends</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Revenue Trend Chart -->
                    <div>
                        <p class="text-sm font-semibold text-secondary-500 mb-2">
                            {{ request('range') }} Revenue Trend
                        </p>
                        <div class="bg-secondary-50 border border-secondary-200 rounded-xl p-4 h-80">
                            <canvas id="revenueTrend"></canvas>
                        </div>
                    </div>

                    <!-- Orders vs Returns Bar Chart -->
                    <div>
                        <p class="text-sm font-semibold text-secondary-500 mb-2">
                            Orders vs Returns
                        </p>
                        <div class="bg-secondary-50 border border-secondary-200 rounded-xl p-4 h-80 relative">
                            <canvas id="ordersReturns"></canvas>

                            <!-- Return Rate Badge -->
                            <div
                                class="absolute top-4 right-4 bg-danger-100 text-danger px-3 py-1 rounded-full text-xs font-bold">
                                {{ $ordersReturnsChart['return_rate'] }}% Return Rate
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Quick Facts --}}
            <div class="bg-white rounded-2xl border border-secondary-200 shadow-sm p-5">

                <h3 class="text-lg font-bold text-secondary-800 mb-5">
                    Quick Facts
                </h3>

                <div class="space-y-4">

                    <div class="flex items-center justify-between">
                        <span class="text-secondary-500">
                            Total Orders
                        </span>

                        <span class="font-bold text-primary">
                            {{ $quickFacts['totalOrders'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-secondary-500">
                            Refund Rate
                        </span>

                        <span class="font-bold text-danger">
                            {{ $quickFacts['refundRate'] }}%
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="text-secondary-500">
                            Best Sales Day
                        </span>

                        <span class="font-bold text-success text-right">
                            {{ $quickFacts['bestSalesDay'] ?? '-' }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="bg-white rounded-2xl border border-secondary-200 shadow-sm overflow-hidden">

            <div class="p-5 border-b border-secondary-200">
                <h3 class="text-lg font-bold text-secondary-800">
                    Top Product Snapshot
                </h3>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-secondary-500">
                                Product
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-secondary-500">
                                Units Sold
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-secondary-500">
                                Sales
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-secondary-500">
                                Stock
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-secondary-100">

                        @foreach ($topProducts as $product)

                            <tr class="hover:bg-secondary-50 transition">

                                <td class="px-5 py-4 font-medium text-secondary-700">
                                    {{ $product['name'] }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    {{ $product['unitsSold'] }}
                                </td>

                                <td class="px-5 py-4 text-right font-semibold text-success">
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