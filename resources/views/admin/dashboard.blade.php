@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
    <main class="space-y-6 p-1">

        {{-- Page Header --}}
        <section class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-sm text-slate-500 mt-0.5">Here's what's happening with your store today.</p>
            </div>
            <form method="GET">
                <div class="relative">
                    <select name="filter" onchange="this.form.submit()"
                        class="appearance-none rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-10 text-sm font-medium text-slate-700 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <option value="today" {{ request('filter', 'today') === 'today' ? 'selected' : '' }}>Today
                        </option>
                        <option value="this_week" {{ request('filter') === 'this_week' ? 'selected' : '' }}>This
                            Week</option>
                        <option value="this_month" {{ request('filter') === 'this_month' ? 'selected' : '' }}>This
                            Month</option>
                    </select>
                    <i data-lucide="calendar-days"
                        class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none"></i>
                    <i data-lucide="chevron-down"
                        class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none"></i>
                </div>
            </form>
        </section>

        {{-- Stat Cards --}}
        @php
            $vsLabel = match ($filter) {
                'today' => 'vs yesterday',
                'this_week' => 'vs last week',
                default => 'vs last month',
            };
        @endphp

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            @php
                $stats = [
                    [
                        'label' => 'Total Revenue',
                        'value' => money($widgets['totalRevenue']),
                        'pct' => $widgets['totalRevenuePercentage'],
                        'icon' => 'banknote',
                        'icon_color' => 'text-emerald-600',
                        'icon_bg' => 'bg-emerald-50',
                    ],
                    [
                        'label' => 'Total Orders',
                        'value' => number_format($widgets['totalOrders']),
                        'pct' => $widgets['totalOrdersPercentage'],
                        'icon' => 'shopping-cart',
                        'icon_color' => 'text-blue-600',
                        'icon_bg' => 'bg-blue-50',
                    ],
                    [
                        'label' => 'Customers',
                        'value' => number_format($widgets['totalCustomers']),
                        'pct' => $widgets['totalCustomersPercentage'],
                        'icon' => 'users',
                        'icon_color' => 'text-violet-600',
                        'icon_bg' => 'bg-violet-50',
                    ],
                    [
                        'label' => 'Refunds',
                        'value' => money($widgets['totalRefund']),
                        'pct' => $widgets['totalRefundPercentage'],
                        'icon' => 'credit-card',
                        'icon_color' => 'text-rose-600',
                        'icon_bg' => 'bg-rose-50',
                    ],
                ];
            @endphp

            @foreach ($stats as $s)
                <article
                    class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500">{{ $s['label'] }}</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $s['value'] }}</h2>
                        </div>
                        <div class="rounded-xl {{ $s['icon_bg'] }} {{ $s['icon_color'] }} p-3">
                            <i data-lucide="{{ $s['icon'] }}" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        @if ($s['pct'] >= 0)
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                <i data-lucide="arrow-up-right" class="h-3 w-3"></i>{{ $s['pct'] }}%
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                <i data-lucide="arrow-down-right" class="h-3 w-3"></i>{{ abs($s['pct']) }}%
                            </span>
                        @endif
                        <span class="text-xs text-slate-400">{{ $vsLabel }}</span>
                    </div>
                </article>
            @endforeach
        </section>

        {{-- Revenue Chart + Sales Channels --}}
        <section class="grid gap-6 xl:grid-cols-3">

            {{-- Revenue Chart --}}
            <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm xl:col-span-2">
                <div class="mb-5 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Revenue Overview</h2>
                        <p class="text-sm text-slate-500">Monthly revenue &amp; order performance</p>
                    </div>
                </div>
                <div class="relative h-60">
                    <canvas id="revenueChart"></canvas>
                </div>
            </article>

            {{-- Sales Channels --}}
            <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Sales Channels</h2>
                        <p class="text-sm text-slate-500">Orders by platform</p>
                    </div>
                    <button class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                        <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                    </button>
                </div>

                <div class="flex justify-center my-4">
                    <div class="relative grid h-36 w-36 place-items-center rounded-full"
                        style="background:conic-gradient(#10b981 0 {{ $websitePercentage }}%,#f59e0b {{ $websitePercentage }}% 100%)">
                        <div class="grid h-24 w-24 place-items-center rounded-full bg-white">
                            <div class="text-center">
                                <p class="text-xl font-bold text-slate-900">{{ number_format($totalChannelOrders) }}</p>
                                <p class="text-xs text-slate-500">Orders</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ([['label' => 'Website', 'pct' => $websitePercentage, 'orders' => $websiteOrders, 'color' => 'bg-emerald-500', 'dot' => 'bg-emerald-500'], ['label' => 'POS', 'pct' => $posPercentage, 'orders' => $posOrders, 'color' => 'bg-amber-500', 'dot' => 'bg-amber-500']] as $ch)
                        <div>
                            <div class="mb-1.5 flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 font-medium text-slate-700">
                                    <span class="h-2 w-2 rounded-full {{ $ch['dot'] }}"></span>{{ $ch['label'] }}
                                </span>
                                <span class="font-semibold text-slate-900">{{ $ch['pct'] }}%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-slate-100">
                                <div class="h-1.5 rounded-full {{ $ch['color'] }} transition-all duration-500"
                                    style="width:{{ $ch['pct'] }}%"></div>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">{{ number_format($ch['orders']) }} orders</p>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        {{-- Recent Orders + Top Products --}}
        <section class="grid gap-6 xl:grid-cols-3">

            {{-- Recent Orders --}}
            <article class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm xl:col-span-2">
                <div
                    class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Recent Orders</h2>
                        <p class="text-sm text-slate-500">Latest customer purchases</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        <i data-lucide="external-link" class="h-3.5 w-3.5"></i>View All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-left">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Order</th>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Amount</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentOrders as $order)
                                @php
                                    $sc =
                                        [
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'processing' => 'bg-blue-50 text-blue-700',
                                            'completed' => 'bg-emerald-50 text-emerald-700',
                                            'delivered' => 'bg-emerald-50 text-emerald-700',
                                            'cancelled' => 'bg-rose-50 text-rose-700',
                                            'shipped' => 'bg-indigo-50 text-indigo-700',
                                        ][$order['status']] ?? 'bg-slate-100 text-slate-600';

                                    $initials = collect(explode(' ', $order['customer_name']))
                                        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                        ->take(2)
                                        ->implode('');
                                @endphp
                                <tr class="transition hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-sm font-semibold text-slate-900">
                                        #{{ $order['order_number'] }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="grid h-8 w-8 place-items-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-600">
                                                {{ $initials }}</div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">
                                                    {{ $order['customer_name'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm text-slate-500">{{ $order['created_at'] }}</td>
                                    <td class="px-5 py-3.5 text-sm font-semibold text-slate-900">
                                        ৳{{ number_format($order['total'], 2) }}</td>
                                    <td class="px-5 py-3.5">
                                        <span
                                            class="rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $sc }}">{{ $order['status'] }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <a href="{{ route('admin.orders.show', $order['id']) }}"
                                            class="text-slate-400 transition hover:text-indigo-600">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">No recent
                                        orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            {{-- Top Products --}}
            <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Top Products</h2>
                        <p class="text-sm text-slate-500">Best selling items</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($topProducts as $product)
                        <div
                            class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 transition hover:bg-slate-50">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100">
                                @if ($product['image'])
                                    <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}"
                                        class="h-full w-full object-cover">
                                @else
                                    <i data-lucide="package" class="h-5 w-5 text-slate-400"></i>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $product['name'] }}</p>
                                <p class="mt-0.5 text-xs text-slate-400">{{ number_format($product['sales']) }} sold</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-900">৳{{ number_format($product['revenue'], 0) }}
                                </p>
                                <p class="text-xs text-slate-400">revenue</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-slate-500">No top products found.</div>
                    @endforelse
                </div>
            </article>
        </section>

        {{-- Inventory Alerts + Recent Customers --}}
        <section class="grid gap-6 lg:grid-cols-2">

            {{-- Inventory Alerts --}}
            <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Inventory Alerts</h2>
                        <p class="text-sm text-slate-500">Products requiring attention</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 p-2.5 text-amber-600">
                        <i data-lucide="boxes" class="h-4 w-4"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($lowStockProducts as $product)
                        @php
                            if ($product->stock_in <= 5) {
                                $cardCls = 'border-rose-100 bg-rose-50/70';
                                $iconCls = 'text-rose-600';
                                $badgeCls = 'bg-rose-100 text-rose-700';
                                $status = 'Critical';
                                $icon = 'package-x';
                                $desc = 'Only ' . $product->stock_in . ' units left in stock';
                            } elseif ($product->stock_in <= 10) {
                                $cardCls = 'border-amber-100 bg-amber-50/70';
                                $iconCls = 'text-amber-600';
                                $badgeCls = 'bg-amber-100 text-amber-700';
                                $status = 'Low';
                                $icon = 'alert-triangle';
                                $desc = $product->stock_in . ' units remaining, reorder soon';
                            } else {
                                $cardCls = 'border-blue-100 bg-blue-50/70';
                                $iconCls = 'text-blue-600';
                                $badgeCls = 'bg-blue-100 text-blue-700';
                                $status = 'Medium';
                                $icon = 'package';
                                $desc = $product->stock_in . ' units available';
                            }
                        @endphp
                        <div class="flex items-center gap-3 rounded-xl border p-3.5 {{ $cardCls }}">
                            <div class="rounded-lg bg-white p-2 {{ $iconCls }}">
                                <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-slate-900 text-sm">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $desc }}</p>
                            </div>
                            <span
                                class="shrink-0 rounded-full px-2.5 py-1 text-xs font-bold {{ $badgeCls }}">{{ $status }}</span>
                        </div>
                    @empty
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-6 text-center">
                            <div
                                class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-white text-emerald-600">
                                <i data-lucide="check-circle" class="h-5 w-5"></i>
                            </div>
                            <h3 class="mt-3 text-sm font-semibold text-slate-900">Inventory Healthy</h3>
                            <p class="mt-1 text-xs text-slate-500">No low stock products found.</p>
                        </div>
                    @endforelse
                </div>
            </article>

            {{-- Recent Customers --}}
            <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Recent Customers</h2>
                        <p class="text-sm text-slate-500">Newly registered users</p>
                    </div>
                    <a href="{{ route('admin.customers.index') }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">View All</a>
                </div>
                <div class="space-y-0">
                    @forelse($recentCustomers as $customer)
                        @php
                            $initials = collect(explode(' ', $customer['name']))
                                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                ->take(2)
                                ->implode('');

                            $ordersCount = $customer['orders_count'] ?? 0;
                            $totalSpent = $customer['total_spent'] ?? 0;

                            if ($ordersCount >= 5) {
                                $badgeCls = 'bg-amber-50 text-amber-700';
                                $badgeLabel = 'VIP';
                            } elseif ($ordersCount >= 1) {
                                $badgeCls = 'bg-emerald-50 text-emerald-700';
                                $badgeLabel = 'Active';
                            } else {
                                $badgeCls = 'bg-slate-100 text-slate-600';
                                $badgeLabel = 'New';
                            }
                        @endphp
                        <div class="relative flex gap-3 {{ !$loop->last ? 'pb-4 mb-4 border-b border-slate-100' : '' }}">
                            <div
                                class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-600">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $customer['name'] }}</p>
                                    <span class="shrink-0 ml-2 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $badgeCls }}">
                                        {{ $badgeLabel }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $customer['email'] }}</p>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-xs text-slate-400">
                                        <i data-lucide="shopping-bag" class="h-3 w-3 inline mr-0.5 -mt-0.5"></i>
                                        {{ $ordersCount }} orders
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        <i data-lucide="wallet" class="h-3 w-3 inline mr-0.5 -mt-0.5"></i>
                                        ৳{{ number_format($totalSpent, 0) }}
                                    </span>
                                    <span class="text-xs text-slate-400 ml-auto">
                                        {{ $customer['joined_at'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                <i data-lucide="users" class="h-5 w-5"></i>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">No new customers yet.</p>
                        </div>
                    @endforelse
                </div>
            </article>
        </section>

    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($chartData),
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99,102,241,0.07)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 12,
                            titleColor: '#f1f5f9',
                            bodyColor: '#94a3b8',
                            borderColor: '#1e293b',
                            borderWidth: 1,
                            displayColors: false,
                            callbacks: {
                                label: ctx => '৳' + Number(ctx.parsed.y).toLocaleString()
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                callback: v => '৳' + (v / 1000).toFixed(0) + 'k'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush