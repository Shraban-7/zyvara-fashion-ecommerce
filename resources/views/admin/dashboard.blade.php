@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
    <main class="space-y-6 p-1">

        {{-- Page Header --}}
        <section class="flex flex-col gap-1">
            <h1 class="text-xl font-bold text-primary sm:text-2xl">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-sm text-secondary-500">Here's what's happening with your store today.</p>
        </section>

        {{-- Stat Cards (4) --}}
        <section class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $s)
                <article
                    class="rounded-2xl border border-secondary-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md{{ isset($s['link']) ? ' hover:border-accent/40' : '' }}">
                    @if (isset($s['link']))
                        <a href="{{ $s['link'] }}" class="block">
                    @endif
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-secondary-500 truncate">{{ $s['label'] }}</p>
                            <h2 class="mt-2 text-2xl font-bold text-primary truncate">{{ $s['value'] }}</h2>
                        </div>
                        <div class="rounded-xl {{ $s['icon_bg'] }} {{ $s['icon_color'] }} p-3 shrink-0">
                            <i data-lucide="{{ $s['icon'] }}" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        @if (! is_null($s['pct']))
                            @if ($s['pct'] >= 0)
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-success-50 px-2.5 py-1 text-xs font-semibold text-success">
                                    <i data-lucide="arrow-up-right" class="h-3 w-3"></i>{{ $s['pct'] }}%
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-danger-50 px-2.5 py-1 text-xs font-semibold text-danger">
                                    <i data-lucide="arrow-down-right" class="h-3 w-3"></i>{{ abs($s['pct']) }}%
                                </span>
                            @endif
                        @else
                            <span></span>
                        @endif
                        <span class="text-xs text-secondary-400">{{ $s['sub'] }}</span>
                    </div>
                    @if (isset($s['link']))
                        </a>
                    @endif
                </article>
            @endforeach
        </section>

        {{-- Revenue Chart + Category Donut --}}
        <section class="grid gap-6 lg:grid-cols-3">
            <article class="rounded-2xl border border-secondary-200 bg-white p-5 shadow-sm lg:col-span-2">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-primary">Revenue Overview</h2>
                        <p class="text-sm text-secondary-500">Daily revenue performance</p>
                    </div>
                    <div class="inline-flex rounded-xl bg-secondary-100 p-1" role="tablist" aria-label="Chart range">
                        <button id="range7" onclick="setChartRange(7)"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition text-secondary-600">7 Days</button>
                        <button id="range30" onclick="setChartRange(30)"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition text-secondary-600">30 Days</button>
                    </div>
                </div>
                <div class="relative h-64 sm:h-72">
                    <canvas id="revenueChart"></canvas>
                </div>
            </article>

            <article class="rounded-2xl border border-secondary-200 bg-white p-5 shadow-sm">
                <div class="mb-4">
                    <h2 class="text-base font-bold text-primary">Products by Category</h2>
                    <p class="text-sm text-secondary-500">Distribution across top categories</p>
                </div>
                <div class="relative h-56 flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </article>
        </section>

        {{-- Recent Orders + Top Products --}}
        <section class="grid gap-6 lg:grid-cols-3">
            <article class="overflow-hidden rounded-2xl border border-secondary-200 bg-white shadow-sm lg:col-span-2">
                <div
                    class="flex flex-col gap-3 border-b border-secondary-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-primary">Recent Orders</h2>
                        <p class="text-sm text-secondary-500">Latest customer purchases</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-secondary-200 px-4 py-1.5 text-sm font-semibold text-secondary-700 transition hover:bg-secondary-50 hover:text-primary">
                        <i data-lucide="external-link" class="h-3.5 w-3.5"></i>View All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-left admin-table">
                        <thead>
                            <tr>
                                <th class="px-5 py-3">Order</th>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Amount</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-50">
                            @forelse($recentOrders as $order)
                                <tr class="transition hover:bg-secondary-50">
                                    <td class="px-5 py-3.5 text-sm font-semibold text-primary">
                                        #{{ $order['order_number'] }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="grid h-8 w-8 place-items-center rounded-full bg-accent-100 text-xs font-bold text-primary">
                                                {{ collect(explode(' ', $order['customer_name']))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('') }}
                                            </div>
                                            <p class="text-sm font-semibold text-secondary-800 truncate max-w-[160px]">
                                                {{ $order['customer_name'] }}</p>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm text-secondary-500">{{ $order['created_at'] }}</td>
                                    <td class="px-5 py-3.5 text-sm font-semibold text-primary">
                                        ৳{{ number_format($order['total'], 2) }}</td>
                                    <td class="px-5 py-3.5">
                                        <span
                                            class="status-badge {{ $statusBadgeMap[$order['status']] ?? 'pending' }}">{{ $order['status_label'] }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <a href="{{ route('admin.orders.show', $order['id']) }}"
                                            class="text-secondary-400 transition hover:text-primary" title="View">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-sm text-secondary-500">No recent
                                        orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-2xl border border-secondary-200 bg-white p-5 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-primary">Top Products</h2>
                        <p class="text-sm text-secondary-500">Best sellers this month</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
                        class="text-sm font-semibold text-accent hover:text-primary transition">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-center gap-3 rounded-xl border border-secondary-100 p-3 transition hover:bg-secondary-50">
                            <span class="text-xs font-bold text-secondary-300 w-4 shrink-0">{{ $index + 1 }}</span>
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-secondary-100">
                                @if ($product['image'])
                                    <img src="{{ storage_url($product['image']) }}" alt="{{ $product['name'] }}"
                                        class="h-full w-full object-cover">
                                @else
                                    <i data-lucide="package" class="h-5 w-5 text-secondary-400"></i>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-primary">{{ $product['name'] }}</p>
                                <p class="mt-0.5 text-xs text-secondary-400">{{ number_format($product['units']) }} sold</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-primary">৳{{ number_format($product['revenue'], 0) }}</p>
                                <p class="text-xs text-secondary-400">revenue</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-secondary-500">No sales yet this month.</div>
                    @endforelse
                </div>
            </article>
        </section>

        {{-- Low Stock + Flash Sale Performance --}}
        <section class="grid gap-6 lg:grid-cols-2">

            {{-- Low Stock Alert --}}
            <article class="rounded-2xl border border-secondary-200 bg-white p-5 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-primary">Low Stock Alert</h2>
                        <p class="text-sm text-secondary-500">Products below 10 units</p>
                    </div>
                    <div class="rounded-xl bg-warning-50 p-2.5 text-warning">
                        <i data-lucide="boxes" class="h-4 w-4"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($lowStock as $product)
                        <div class="flex items-center gap-3 rounded-xl border border-secondary-100 p-3">
                            <div class="rounded-lg bg-secondary-50 p-2 text-secondary-400">
                                <i data-lucide="package" class="h-4 w-4"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-primary text-sm truncate">{{ $product->name }}</p>
                                <p class="text-xs text-secondary-500 mt-0.5">
                                    {{ $product->stock_in }} units left
                                    @if($product->sku)· {{ $product->sku }}@endif
                                </p>
                            </div>
                            <a href="{{ route('admin.products.manage-stock', $product->id) }}"
                                class="shrink-0 inline-flex items-center gap-1 rounded-lg bg-primary px-2.5 py-1.5 text-xs font-semibold text-white transition hover:bg-primary-700">
                                <i data-lucide="plus" class="h-3 w-3"></i>Restock
                            </a>
                        </div>
                    @empty
                        <div class="rounded-xl border border-success-100 bg-success-50/60 p-6 text-center">
                            <div
                                class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-white text-success">
                                <i data-lucide="check-circle" class="h-5 w-5"></i>
                            </div>
                            <h3 class="mt-3 text-sm font-semibold text-primary">Inventory Healthy</h3>
                            <p class="mt-1 text-xs text-secondary-500">No low stock products found.</p>
                        </div>
                    @endforelse
                </div>
            </article>

            {{-- Flash Sale Performance --}}
            <article class="rounded-2xl border border-secondary-200 bg-white p-5 shadow-sm">
                @if($flashSale)
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-primary">{{ $flashSale['name'] }}</h2>
                            <p class="text-sm text-secondary-500">Live flash sale performance</p>
                        </div>
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-danger-50 px-3 py-1 text-xs font-semibold text-danger">
                            <span class="h-1.5 w-1.5 rounded-full bg-danger animate-pulse"></span>
                            <span id="flashCountdown" data-ends="{{ $flashSale['ends_at'] }}">--:--:--</span>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="rounded-xl bg-secondary-50 p-3">
                            <p class="text-xs text-secondary-500">Products</p>
                            <p class="text-lg font-bold text-primary">{{ $flashSale['products_count'] }}</p>
                        </div>
                        <div class="rounded-xl bg-secondary-50 p-3">
                            <p class="text-xs text-secondary-500">Avg. Discount</p>
                            <p class="text-lg font-bold text-accent">{{ $flashSale['avg_discount'] }}%</p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        @foreach($flashSale['products'] as $fp)
                            <div class="flex items-center gap-3 rounded-xl border border-secondary-100 p-2.5">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-secondary-100">
                                    @if($fp['image'])
                                        <img src="{{ storage_url($fp['image']) }}" alt="{{ $fp['name'] }}"
                                            class="h-full w-full object-cover">
                                    @else
                                        <i data-lucide="package" class="h-4 w-4 text-secondary-400"></i>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-primary">{{ $fp['name'] }}</p>
                                    <p class="text-xs text-secondary-400 line-through">৳{{ number_format($fp['price'], 2) }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm font-bold text-danger">৳{{ number_format($fp['sale_price'], 2) }}</p>
                                    <p class="text-[10px] font-semibold text-accent">-{{ $fp['discount'] }}%</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('admin.flash-sales.index') }}"
                        class="mt-4 block text-center text-sm font-semibold text-accent hover:text-primary transition">Manage Flash Sale</a>
                @else
                    <div class="flex h-full flex-col items-center justify-center py-8 text-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-accent-50 text-accent">
                            <i data-lucide="zap" class="h-6 w-6"></i>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-primary">No Active Flash Sale</h3>
                        <p class="mt-1 max-w-xs text-sm text-secondary-500">Boost sales by launching a limited-time
                            discount event.</p>
                        <a href="{{ route('admin.flash-sales.create') }}"
                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700">
                            <i data-lucide="plus" class="h-4 w-4"></i>Create Flash Sale
                        </a>
                    </div>
                @endif
            </article>
        </section>

    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dashboardData = {
            '7': { labels: @json($revenue['today']['labels']), data: @json($revenue['today']['data']) },
            '30': { labels: @json($revenue['month']['labels']), data: @json($revenue['month']['data']) },
        };

        let revenueChart;

        function renderRevenueChart(range) {
            const el = document.getElementById('revenueChart');
            if (!el) return;
            const series = dashboardData[range];

            const ctx = el.getContext('2d');
            const grad = ctx.createLinearGradient(0, 0, 0, 280);
            grad.addColorStop(0, 'rgba(201,168,124,0.25)');
            grad.addColorStop(1, 'rgba(201,168,124,0.02)');

            const config = {
                type: 'line',
                data: {
                    labels: series.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: series.data,
                        borderColor: '#C9A87C',
                        backgroundColor: grad,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#C9A87C',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1A1A1A',
                            padding: 12,
                            titleColor: '#E5E0D8',
                            bodyColor: '#b3aca1',
                            borderColor: '#C9A87C',
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
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { font: { size: 11 }, callback: v => '৳' + (v / 1000).toFixed(0) + 'k' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 12 }
                        }
                    }
                }
            };

            if (revenueChart) {
                revenueChart.data = config.data;
                revenueChart.update();
            } else {
                revenueChart = new Chart(ctx, config);
            }
        }

        function setChartRange(range) {
            renderRevenueChart(range);
            document.getElementById('range7').classList.toggle('bg-white', range === 7);
            document.getElementById('range7').classList.toggle('shadow-sm', range === 7);
            document.getElementById('range7').classList.toggle('text-primary', range === 7);
            document.getElementById('range30').classList.toggle('bg-white', range === 30);
            document.getElementById('range30').classList.toggle('shadow-sm', range === 30);
            document.getElementById('range30').classList.toggle('text-primary', range === 30);
        }

        // Category donut
        function renderCategoryChart() {
            const el = document.getElementById('categoryChart');
            if (!el) return;
            const labels = @json($categories['labels']);
            const data = @json($categories['data']);
            const palette = ['#1A1A1A', '#C9A87C', '#8A8A8A', '#3d3933', '#b8925f', '#cfc8bd'];

            new Chart(el.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: palette,
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { size: 11 }, boxWidth: 10, padding: 12, color: '#6f6a62' }
                        },
                        tooltip: {
                            backgroundColor: '#1A1A1A',
                            padding: 10,
                            titleColor: '#E5E0D8',
                            bodyColor: '#b3aca1',
                            displayColors: false,
                            callbacks: { label: ctx => `${ctx.label}: ${ctx.parsed} products` }
                        }
                    }
                }
            });
        }

        function startFlashCountdown() {
            const el = document.getElementById('flashCountdown');
            if (!el) return;
            const ends = parseInt(el.dataset.ends, 10) * 1000;

            function tick() {
                const diff = ends - Date.now();
                if (diff <= 0) { el.textContent = 'Ended'; return; }
                const h = Math.floor(diff / 3.6e6);
                const m = Math.floor((diff % 3.6e6) / 6e4);
                const s = Math.floor((diff % 6e4) / 1000);
                el.textContent =
                    String(h).padStart(2, '0') + ':' +
                    String(m).padStart(2, '0') + ':' +
                    String(s).padStart(2, '0');
            }
            tick();
            setInterval(tick, 1000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            setChartRange(7);
            renderCategoryChart();
            startFlashCountdown();
        });
    </script>
@endpush
