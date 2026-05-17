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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="font-medium text-slate-900">Customer Report</span>
                </nav>
            </div>

            {{-- Filter --}}
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">

                <select name="range"
                    onchange="toggleCustomDates(this.value)"
                    class="w-full rounded-lg border-2 border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-slate-300 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:w-48">

                    <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>

                <div id="customDateRange" class="{{ request('range') == 'custom' ? 'flex' : 'hidden' }} gap-2">

                    <input type="date" name="date_from"
                        value="{{ request('date_from') }}"
                        class="rounded-lg border-2 border-slate-200 px-4 py-2.5 text-sm font-medium transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                    <input type="date" name="date_to"
                        value="{{ request('date_to') }}"
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
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-12">

            @php
                $kpis = [
                    [
                        'label' => 'Total Customers',
                        'value' => number_format($allTimeTotalCustomers),
                        'change' => 0,
                        'icon' => 'fa-users',
                        'color' => 'blue',
                        'gradient_from' => 'from-blue-500',
                        'gradient_to' => 'to-blue-600',
                        'note' => 'All time',
                        'col_span' => 'xl:col-span-3',
                    ],
                    [
                        'label' => 'New Customers',
                        'value' => number_format($newCustomersCurrent),
                        'change' => $newCustomersChange,
                        'icon' => 'fa-user-plus',
                        'color' => 'cyan',
                        'gradient_from' => 'from-cyan-500',
                        'gradient_to' => 'to-cyan-600',
                        'note' => 'This period',
                        'col_span' => 'xl:col-span-3',
                    ],
                    [
                        'label' => 'Returning Rate',
                        'value' => $returningPercentage . '%',
                        'change' => null,
                        'icon' => 'fa-redo-alt',
                        'color' => 'emerald',
                        'gradient_from' => 'from-emerald-500',
                        'gradient_to' => 'to-emerald-600',
                        'note' => 'Of total customers',
                        'col_span' => 'xl:col-span-2',
                    ],
                    [
                        'label' => 'Avg CLV',
                        'value' => money($avgClvCurrent),
                        'change' => $avgClvChange,
                        'icon' => 'fa-hand-holding-usd',
                        'color' => 'amber',
                        'gradient_from' => 'from-amber-500',
                        'gradient_to' => 'to-amber-600',
                        'note' => 'Customer lifetime value',
                        'col_span' => 'xl:col-span-2',
                    ],
                    [
                        'label' => 'Avg Orders',
                        'value' => number_format($avgOrdersPerCustomerCurrent, 1),
                        'change' => $avgOrdersPerCustomerChange,
                        'icon' => 'fa-cart-shopping',
                        'color' => 'violet',
                        'gradient_from' => 'from-violet-500',
                        'gradient_to' => 'to-violet-600',
                        'note' => 'Per customer',
                        'col_span' => 'xl:col-span-2',
                    ],
                ];
            @endphp

            @foreach ($kpis as $kpi)
                <div class="{{ $kpi['col_span'] }} col-span-12 sm:col-span-6">
                    <div class="group relative h-full overflow-hidden rounded-2xl bg-gradient-to-br {{ $kpi['gradient_from'] }} {{ $kpi['gradient_to'] }} p-6 shadow-lg transition hover:shadow-xl">
                        
                        <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                        
                        <div class="relative">
                            <div class="mb-3 flex items-center gap-2">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                                    <i class="fas {{ $kpi['icon'] }} text-lg text-white"></i>
                                </div>
                            </div>
                            
                            <p class="mb-1 text-xs font-medium uppercase tracking-wider text-white/80">
                                {{ $kpi['label'] }}
                            </p>
                            
                            <h3 class="mb-2 text-2xl font-bold text-white">
                                {{ $kpi['value'] }}
                            </h3>
                            
                            @if (!is_null($kpi['change']) && $kpi['change'] != 0)
                                <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                                    @if($kpi['change'] >= 0)
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                        </svg>
                                    @else
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                    @endif
                                    {{ abs($kpi['change']) }}%
                                </div>
                            @else
                                <p class="text-xs text-white/70">{{ $kpi['note'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Charts Section --}}
        <div class="grid gap-6 lg:grid-cols-12">

            {{-- Customer Growth Trend --}}
            <div class="lg:col-span-7">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    
                    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Customer Growth Trend</h3>
                            <p class="mt-1 text-sm text-slate-500">Track customer acquisition over time</p>
                        </div>

                        <div class="flex items-center gap-2 rounded-lg bg-slate-100 p-1">
                            <button type="button"
                                onclick="showCustomerChart('total')"
                                id="totalTab"
                                class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition">
                                Total
                            </button>

                            <button type="button"
                                onclick="showCustomerChart('returning')"
                                id="returningTab"
                                class="rounded-md px-4 py-2 text-sm font-semibold text-slate-600 transition hover:text-slate-900">
                                New vs Returning
                            </button>
                        </div>
                    </div>

                    <div id="totalChartWrapper" class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <canvas id="totalCustomersChart"  height="200"></canvas>
                    </div>

                    <div id="returningChartWrapper" class="hidden bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <canvas id="newReturningChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top High-Value Customers --}}
            <div class="lg:col-span-5">
                <div class="h-full rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Top High-Value Customers</h3>
                        <p class="mt-1 text-sm text-slate-500">Customers with highest spending</p>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-slate-200">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr class="text-slate-600">
                                    <th class="px-4 py-3 text-left font-semibold">Customer</th>
                                    <th class="px-4 py-3 text-right font-semibold">Orders</th>
                                    <th class="px-4 py-3 text-right font-semibold">Total Spent</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse ($topCustomers as $index => $cust)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2 font-medium text-slate-900">
                                                @if ($index === 0)
                                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-100">
                                                        <i class="fas fa-crown text-xs text-amber-600"></i>
                                                    </div>
                                                @elseif ($index === 1)
                                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                                                        <i class="fas fa-medal text-xs text-slate-600"></i>
                                                    </div>
                                                @elseif ($index === 2)
                                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-50">
                                                        <i class="fas fa-medal text-xs text-amber-700"></i>
                                                    </div>
                                                @endif
                                                {{ $cust['name'] }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-right text-slate-700">
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                {{ $cust['orders'] }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <span class="font-semibold text-emerald-600">
                                                {{ money($cust['spent']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-slate-500">
                                            <div class="flex flex-col items-center gap-2">
                                                <i class="fas fa-users text-3xl text-slate-300"></i>
                                                <p>No customers found for this period.</p>
                                            </div>
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
                totalTab.classList.remove('text-slate-600', 'hover:text-slate-900');

                returningTab.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                returningTab.classList.add('text-slate-600', 'hover:text-slate-900');
            } else {
                returningWrapper.classList.remove('hidden');
                totalWrapper.classList.add('hidden');

                returningTab.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
                returningTab.classList.remove('text-slate-600', 'hover:text-slate-900');

                totalTab.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                totalTab.classList.add('text-slate-600', 'hover:text-slate-900');
            }
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