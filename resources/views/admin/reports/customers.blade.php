@extends('admin.layouts.app')

@section('title', 'Customers Reports')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">Customer Report</h1>

                <nav class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                    <span>Reports</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="font-medium text-slate-900">Customer Report</span>
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

            {{-- Total Customers --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Customers</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ number_format($allTimeTotalCustomers) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs">
                    <span class="{{ $newCustomersChange >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        <i class="fas {{ $newCustomersChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($newCustomersChange), 2) }}%
                    </span>
                </div>
            </div>

            {{-- New Customers --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-cyan-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center">
                        <i class="fas fa-user-plus text-cyan-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">New Customers</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ number_format($newCustomersCurrent) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs">
                    <span class="{{ $newCustomersChange >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        <i class="fas {{ $newCustomersChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($newCustomersChange), 2) }}%
                    </span>
                </div>
            </div>

            {{-- Returning Rate --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-emerald-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-redo-alt text-emerald-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Returning Rate</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ $returningPercentage }}%
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs text-gray-500">
                    Of total customers
                </div>
            </div>

            {{-- Avg CLV --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-amber-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-amber-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Avg CLV</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ money($avgClvCurrent) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs">
                    <span class="{{ $avgClvChange >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        <i class="fas {{ $avgClvChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($avgClvChange), 2) }}%
                    </span>
                </div>
            </div>

            {{-- Avg Orders --}}
            <div
                class="bg-white rounded-xl shadow-sm border-l-4 border-violet-500 p-4 hover:shadow-md transition h-full flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-violet-50 flex items-center justify-center">
                        <i class="fas fa-cart-shopping text-violet-600 text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium">Avg Orders</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ number_format($avgOrdersPerCustomerCurrent, 1) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 text-xs">
                    <span class="{{ $avgOrdersPerCustomerChange >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        <i class="fas {{ $avgOrdersPerCustomerChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ number_format(abs($avgOrdersPerCustomerChange), 2) }}%
                    </span>
                </div>
            </div>

        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-stretch">

            {{-- Customer Growth Trend --}}
            <div class="lg:col-span-6">
                <div class="h-full rounded-2xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm flex flex-col">

                    {{-- Header --}}
                    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                Customer Growth Trend
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Track customer acquisition over time
                            </p>
                        </div>

                        <div class="flex w-full lg:w-auto items-center gap-2 rounded-lg bg-slate-100 p-1">
                            <button type="button" onclick="showCustomerChart('total')" id="totalTab"
                                class="w-1/2 lg:w-auto rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm">
                                Total
                            </button>

                            <button type="button" onclick="showCustomerChart('returning')" id="returningTab"
                                class="w-1/2 lg:w-auto rounded-md px-3 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900">
                                New vs Returning
                            </button>
                        </div>
                    </div>

                    {{-- Charts --}}
                    <div class="flex-1">

                        <div id="totalChartWrapper" class="bg-gray-50 rounded-lg border border-gray-200 p-4">

                            <canvas id="totalCustomersChart" height="150"></canvas>

                        </div>

                        <div id="returningChartWrapper" class="hidden bg-gray-50 rounded-lg border border-gray-200 p-4">

                            <canvas id="newReturningChart" height="150"></canvas>

                        </div>

                    </div>

                </div>
            </div>

            {{-- Top High-Value Customers --}}
            <div class="lg:col-span-6">
                <div class="h-full rounded-2xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm flex flex-col">

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-slate-900">
                            Top High-Value Customers
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Customers with highest spending
                        </p>
                    </div>

                    {{-- Table wrapper fixes overflow --}}
                    <div class="flex-1 overflow-auto rounded-lg border border-slate-200">

                        <table class="min-w-full text-sm">

                            <thead class="bg-slate-50 sticky top-0 z-10">
                                <tr class="text-slate-600">
                                    <th class="px-4 py-3 text-left whitespace-nowrap">Customer</th>
                                    <th class="px-4 py-3 text-right whitespace-nowrap">Orders</th>
                                    <th class="px-4 py-3 text-right whitespace-nowrap">Spent</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">

                                @forelse ($topCustomers as $index => $cust)
                                    <tr class="hover:bg-slate-50">

                                        <td class="px-4 py-3 min-w-[160px]">
                                            <div class="flex items-center gap-2 font-medium text-slate-900">
                                                @if ($index === 0)
                                                    <i class="fas fa-crown text-amber-500"></i>
                                                @elseif ($index === 1)
                                                    <i class="fas fa-medal text-slate-500"></i>
                                                @elseif ($index === 2)
                                                    <i class="fas fa-medal text-amber-700"></i>
                                                @endif

                                                <span class="truncate max-w-[140px]">
                                                    {{ $cust['name'] }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <span
                                                class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">
                                                {{ $cust['orders'] }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 text-right font-semibold text-emerald-600">
                                            {{ money($cust['spent']) }}
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-10 text-center text-slate-500">
                                            No customers found
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function toggleCustomDates(value) {
            const custom = document.getElementById('customDateRange');
            custom.classList.toggle('hidden', value !== 'custom');
            custom.classList.toggle('flex', value === 'custom');
        }

        function showCustomerChart(type) {
            const totalWrapper = document.getElementById('totalChartWrapper');
            const returningWrapper = document.getElementById('returningChartWrapper');

            const totalTab = document.getElementById('totalTab');
            const returningTab = document.getElementById('returningTab');

            if (type === 'total') {
                totalWrapper.classList.remove('hidden');
                returningWrapper.classList.add('hidden');

                totalTab.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
                returningTab.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');

            } else {
                returningWrapper.classList.remove('hidden');
                totalWrapper.classList.add('hidden');

                returningTab.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
                totalTab.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
            }

            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 50);
        }

        // Total Customers Chart
        const totalCtx = document.getElementById('totalCustomersChart').getContext('2d');
        new Chart(totalCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['total']['labels']) !!},
                datasets: [{
                    label: 'Total Customers',
                    data: {!! json_encode($chartData['total']['data']) !!},
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderColor: '#2563eb',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
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
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // New vs Returning Chart
        const newReturningCtx = document.getElementById('newReturningChart').getContext('2d');
        new Chart(newReturningCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['new_vs_returning']['labels']) !!},
                datasets: [
                    {
                        label: 'New Customers',
                        data: {!! json_encode($chartData['new_vs_returning']['new']) !!},
                        borderColor: '#14b8a6',
                        backgroundColor: 'rgba(20, 184, 166, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#14b8a6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Returning Customers',
                        data: {!! json_encode($chartData['new_vs_returning']['returning']) !!},
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            padding: 15,
                            font: { size: 12 },
                            color: '#475569',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8
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
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    </script>
@endpush