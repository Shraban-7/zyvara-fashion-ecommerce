@extends('admin.layouts.app')
@section('title', 'Financial Reports')

@section('content')
    <div>
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Financial Reports</h3>
                <nav class="text-sm text-gray-500 mt-1">
                    <span class="text-gray-400">Reports</span>
                    <span class="mx-2 text-gray-300">/</span>
                    <span class="font-semibold text-gray-700">Financial Reports</span>
                </nav>
            </div>

            {{-- Filter Section --}}
            <form method="GET" class="flex flex-col lg:flex-row gap-3 lg:items-end w-full lg:w-auto">

                {{-- Filter Dropdown --}}
                <div class="w-full sm:w-48">
                    <select name="range"
                        class="w-full h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        onchange="toggleCustomDates(this.value)">
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

                {{-- Custom Date Range --}}
                <div id="customDateRange"
                    class="{{ request('range') == 'custom' ? 'flex' : 'hidden' }} flex sm:flex-row gap-2 w-full lg:w-auto">

                    <input type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm w-full">

                    <input type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="h-11 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm w-full">
                </div>

                {{-- Filter Button --}}
                <button type="submit"
                    class="h-11 px-5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium flex items-center justify-center whitespace-nowrap">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </form>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
            
            {{-- Total Revenue --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-sack-dollar text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-blue-100">Total Revenue</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ money($currentMetrics['totalRevenue']) }}</h3>
                    <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($changes['revenue'] >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($changes['revenue']), 2) }}%
                    </div>
                </div>
            </div>

            {{-- Gross Profit --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-hand-holding-dollar text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-cyan-100">Gross Profit</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ money($currentMetrics['grossProfit']) }}</h3>
                    <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($changes['grossProfit'] >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($changes['grossProfit']), 2) }}%
                    </div>
                </div>
            </div>

            {{-- Net Profit --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-coins text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-emerald-100">Net Profit</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ money($currentMetrics['netProfit']) }}</h3>
                    <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($changes['netProfit'] >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($changes['netProfit']), 2) }}%
                    </div>
                </div>
            </div>

            {{-- Total Expenses --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-wallet text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-rose-100">Total Expenses</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ money($currentMetrics['totalExpense']) }}</h3>
                    <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($changes['expense'] >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($changes['expense']), 2) }}%
                    </div>
                </div>
            </div>

            {{-- Inventory Value --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-boxes-stacked text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-amber-100">Inventory Value</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ money($inventoryValue) }}</h3>
                    <p class="text-xs text-amber-100">Current stock value</p>
                </div>
            </div>

            {{-- Profit Margin --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 p-6 shadow-lg transition hover:shadow-xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="mb-3 flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                            <i class="fas fa-percent text-lg text-white"></i>
                        </div>
                    </div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-violet-100">Profit Margin</p>
                    <h3 class="mb-2 text-2xl font-bold text-white">{{ number_format($currentMetrics['profitMargin'], 2) }}%</h3>
                    <div class="inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-semibold text-white">
                        @if($changes['profitMargin'] >= 0)
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($changes['profitMargin']), 2) }}%
                    </div>
                </div>
            </div>

        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex gap-1 px-4 pt-2" aria-label="Tabs">
                    <button onclick="switchTab('pnl')" id="tab-pnl"
                        class="tab-btn px-4 py-3 text-sm font-semibold text-blue-600 border-b-2 border-blue-600 rounded-t-lg hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="fas fa-chart-line"></i>
                        Profit & Loss
                    </button>
                    <button onclick="switchTab('income')" id="tab-income"
                        class="tab-btn px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent rounded-t-lg hover:text-gray-700 hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="fas fa-money-bill-transfer"></i>
                        Income Breakdown
                    </button>
                    <button onclick="switchTab('expenses')" id="tab-expenses"
                        class="tab-btn px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent rounded-t-lg hover:text-gray-700 hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="fas fa-hand-holding-usd"></i>
                        Expenses
                    </button>
                    <button onclick="switchTab('inventory')" id="tab-inventory"
                        class="tab-btn px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent rounded-t-lg hover:text-gray-700 hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="fas fa-warehouse"></i>
                        Inventory Value
                    </button>
                </nav>
            </div>

            <div class="p-6">
                {{-- Profit & Loss Tab --}}
                <div id="panel-pnl" class="tab-panel">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{-- Chart --}}
                        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
                            @php
                                $filterText = match (request('range', 'daily')) {
                                    'daily' => 'Daily Profit Trend',
                                    'weekly' => 'Weekly Profit Trend',
                                    'monthly' => 'Monthly Profit Trend',
                                    'yearly' => 'Yearly Profit Trend',
                                    'custom' => 'Custom Profit Trend',
                                    default => 'Daily Profit Trend',
                                };
                                $descriptionText = match (request('range', 'daily')) {
                                    'daily' => 'Net Profit Over the Last 7 Days',
                                    'weekly' => 'Net Profit Over the Last 12 Weeks',
                                    'monthly' => 'Net Profit Over the Last 12 Months',
                                    'yearly' => 'Net Profit Over the Last 5 Years',
                                    'custom' => 'Net Profit Over the Selected Date Range',
                                    default => 'Net Profit Over the Last 7 Days',
                                };
                            @endphp
                            <h5 class="text-lg font-bold text-blue-600 mb-1">{{ $filterText }}</h5>
                            <p class="text-sm text-gray-500 mb-4">{{ $descriptionText }}</p>

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <canvas id="profitChart" class="w-full" style="max-height: 300px;"></canvas>
                            </div>

                            <div class="mt-4 px-4 py-3 rounded-lg font-bold text-center text-sm {{ $changes['profitMargin'] >= 0 ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                Net Profit Margin: {{ number_format($currentMetrics['profitMargin'], 2) }}%
                            </div>
                        </div>

                        {{-- P&L Summary Table --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h5 class="text-lg font-bold text-gray-800 mb-4">P&L Summary</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600 rounded-tl-lg">Category</th>
                                            <th class="px-3 py-2 text-right font-semibold text-gray-600">Amount</th>
                                            <th class="px-3 py-2 text-right font-semibold text-gray-600 rounded-tr-lg">Change %</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <td class="px-3 py-3 text-gray-700">Total Sales</td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-800">{{ money($currentMetrics['totalRevenue']) }}</td>
                                            <td class="px-3 py-3 text-right {{ $changes['revenue'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $changes['revenue'] >= 0 ? '+' : '' }}{{ number_format($changes['revenue'], 2) }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-3 text-gray-700">Cost of Goods Sold</td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-800">{{ money($currentMetrics['totalProductCost']) }}</td>
                                            <td class="px-3 py-3 text-right {{ $changes['grossProfit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $changes['grossProfit'] >= 0 ? '+' : '' }}{{ number_format($changes['grossProfit'], 2) }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-3 text-gray-700">Gross Profit</td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-800">{{ money($currentMetrics['grossProfit']) }}</td>
                                            <td class="px-3 py-3 text-right {{ $changes['grossProfit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $changes['grossProfit'] >= 0 ? '+' : '' }}{{ number_format($changes['grossProfit'], 2) }}%
                                            </td>
                                        </tr>
                                        <tr class="bg-green-50">
                                            <td class="px-3 py-3 font-bold text-gray-800 rounded-bl-lg">Net Profit</td>
                                            <td class="px-3 py-3 text-right font-bold text-gray-800">{{ money($currentMetrics['netProfit']) }}</td>
                                            <td class="px-3 py-3 text-right font-bold {{ $changes['netProfit'] >= 0 ? 'text-green-600' : 'text-red-600' }} rounded-br-lg">
                                                {{ $changes['netProfit'] >= 0 ? '+' : '' }}{{ number_format($changes['netProfit'], 2) }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Profit Margin Card --}}
                            <div class="mt-4 p-4 rounded-xl {{ $changes['profitMargin'] >= 0 ? 'bg-green-600' : 'bg-red-600' }} text-white">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-sm">Current Profit Margin</span>
                                    <span class="font-bold text-xl">{{ number_format($currentMetrics['profitMargin'], 2) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Income Breakdown Tab --}}
                <div id="panel-income" class="tab-panel hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Pie Chart --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h5 class="text-lg font-bold text-cyan-600 mb-1">Income Source Proportions</h5>
                            <p class="text-sm text-gray-500 mb-4">Visual breakdown of all income streams.</p>
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 flex items-center justify-center">
                                <canvas id="incomePieChart" style="max-height: 300px;"></canvas>
                            </div>
                        </div>

                        {{-- Income Table --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h5 class="text-lg font-bold text-gray-800 mb-4">Income Data Table</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600 rounded-tl-lg">Source</th>
                                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Amount</th>
                                            <th class="px-4 py-3 text-right font-semibold text-gray-600 rounded-tr-lg">Contribution %</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($incomeData as $income)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $income['source'] }}</td>
                                                <td class="px-4 py-3 text-right text-gray-700">{{ money($income['amount']) }}</td>
                                                <td class="px-4 py-3 text-right text-gray-700">{{ number_format($income['percentage'], 2) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Expenses Tab --}}
                <div id="panel-expenses" class="tab-panel hidden">
                    {{-- Expense KPI Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white rounded-xl shadow-sm border-l-4 border-red-500 p-4">
                            <p class="text-xs text-gray-500 font-medium mb-1">Total Expense</p>
                            <h4 class="text-xl font-bold text-red-600">{{ money($totalExpense) }}</h4>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border-l-4 border-yellow-500 p-4">
                            <p class="text-xs text-gray-500 font-medium mb-1">Highest Expense Category</p>
                            <h4 class="text-xl font-bold text-yellow-600">
                                {{ $highestExpense->category->name ?? '' }}
                                <span class="text-sm font-normal text-gray-500">
                                    {{ isset($highestExpense->totalAmount) ? '('.money($highestExpense->totalAmount).')' : '' }}
                                </span>
                            </h4>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border-l-4 border-gray-500 p-4">
                            <p class="text-xs text-gray-500 font-medium mb-1">Expense Growth %</p>
                            <h4 class="text-xl font-bold {{ $expenseGrowth >= 0 ? 'text-red-600' : 'text-green-600' }}">
                                <i class="fas fa-arrow-{{ $expenseGrowth >= 0 ? 'up' : 'down' }} mr-1 text-sm"></i>
                                {{ number_format($expenseGrowth, 2) }}%
                            </h4>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Expense Trend Chart --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h5 class="text-lg font-bold text-red-600 mb-1">Expense Trend</h5>
                            <p class="text-sm text-gray-500 mb-4">{{ ucfirst(request('range')) }} expense comparison.</p>
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <canvas id="expenseBarChart" class="w-full" style="max-height: 300px;"></canvas>
                            </div>
                        </div>

                        {{-- Expense Breakdown Table --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h5 class="text-lg font-bold text-gray-800 mb-4">Expense Breakdown Table</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600 rounded-tl-lg">Category</th>
                                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Amount</th>
                                            <th class="px-4 py-3 text-right font-semibold text-gray-600 rounded-tr-lg">Change</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($expenseCategories ?? [] as $expense)
                                            @php
                                                $lastAmount = \App\Models\Expense::where('category_id', $expense['category_id'])
                                                    ->whereBetween('created_at', [$lastStart, $lastEnd])
                                                    ->sum('amount');
                                                $change = $lastAmount > 0
                                                    ? (($expense['totalAmount'] - $lastAmount) / $lastAmount) * 100
                                                    : 100;
                                                $categoryName = $expense['category']->name ?? '';
                                                $progressWidth = ($expense['totalAmount'] / ($totalExpense ?: 1)) * 100;
                                            @endphp
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $categoryName }}</td>
                                                <td class="px-4 py-3 text-right text-gray-700">{{ money($expense['totalAmount']) }}</td>
                                                <td class="px-4 py-3 text-right {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="px-4 pb-3">
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-{{ $loop->index % 2 == 0 ? 'yellow' : 'blue' }}-500 h-1.5 rounded-full transition-all" 
                                                            style="width: {{ $progressWidth }}%"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inventory Value Tab --}}
                <div id="panel-inventory" class="tab-panel hidden">
                    {{-- Inventory Header --}}
                    <div class="bg-white rounded-xl shadow-sm border-b-4 border-yellow-500 p-5 mb-6">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <h4 class="text-lg font-bold text-yellow-600">
                                Total Inventory Value: <span class="text-gray-800">{{ money($inventoryValue) }}</span>
                            </h4>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-sm font-semibold border border-red-200">
                                <i class="fas fa-triangle-exclamation mr-2"></i>
                                Low Turnover Warning: {{ $lowTurnoverDays }} Days ({{ $lowTurnoverCount }} SKUs)
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Value by Category Chart --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h5 class="text-lg font-bold text-yellow-600 mb-1">Value by Category</h5>
                            <p class="text-sm text-gray-500 mb-4">Horizontal Bar Chart showing stock worth.</p>
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <canvas id="inventoryChart" class="w-full" style="max-height: 300px;"></canvas>
                            </div>
                        </div>

                        {{-- Inventory Details Table --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h5 class="text-lg font-bold text-gray-800 mb-4">Inventory Details</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600 rounded-tl-lg">Category</th>
                                            <th class="px-4 py-3 text-right font-semibold text-gray-600">SKU Count</th>
                                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Stock Value</th>
                                            <th class="px-4 py-3 text-right font-semibold text-gray-600 rounded-tr-lg">% of Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($inventoryByCategory as $item)
                                            @php
                                                $categoryName = $item['category']->name ?? '';
                                                $skuCount = $item['skuCount'];
                                                $stockValue = $item['stockValue'];
                                                $percent = $totalStockValue > 0 ? ($stockValue / $totalStockValue) * 100 : 0;
                                            @endphp
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $categoryName }}</td>
                                                <td class="px-4 py-3 text-right text-gray-700">{{ $skuCount }}</td>
                                                <td class="px-4 py-3 text-right text-gray-700">{{ money($stockValue) }}</td>
                                                <td class="px-4 py-3 text-right text-gray-700">{{ number_format($percent, 2) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        function toggleCustomDates(value) {
            const custom = document.getElementById('customDateRange');
            custom.classList.toggle('hidden', value !== 'custom');
        }

        function switchTab(tabId) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
            // Show selected panel
            document.getElementById('panel-' + tabId).classList.remove('hidden');

            // Reset all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-blue-600', 'border-blue-600');
                btn.classList.add('text-gray-500', 'border-transparent');
            });

            // Activate selected tab
            const activeBtn = document.getElementById('tab-' + tabId);
            activeBtn.classList.remove('text-gray-500', 'border-transparent');
            activeBtn.classList.add('text-blue-600', 'border-blue-600');
        }

        const ctx = document.getElementById('profitChart').getContext('2d');
        const profitChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendData->pluck('label')) !!},
                datasets: [{
                        label: 'Net Profit',
                        data: {!! json_encode($trendData->pluck('netProfit')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Gross Profit',
                        data: {!! json_encode($trendData->pluck('grossProfit')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Revenue',
                        data: {!! json_encode($trendData->pluck('totalRevenue')) !!},
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                }
            }
        });

        const incomeCtx = document.getElementById('incomePieChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($incomeData->pluck('source')) !!},
                datasets: [{
                    data: {!! json_encode($incomeData->pluck('amount')) !!},
                    backgroundColor: ['#0d6efd', '#6c757d', '#17a2b8', '#ffc107', '#198754'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        const expenseCtx = document.getElementById('expenseBarChart').getContext('2d');
        new Chart(expenseCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($expenseTrend->pluck('label')) !!},
                datasets: [{
                    label: 'Expenses',
                    data: {!! json_encode($expenseTrend->pluck('amount')) !!},
                    backgroundColor: '#dc3545'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '৳ ' + context.formattedValue;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳ ' + value;
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                }
            }
        });

        const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
        const inventoryChart = new Chart(inventoryCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($inventoryByCategory->pluck('category.name')) !!},
                datasets: [{
                    label: "Stock Value ({{ currency('symbol') }})",
                    data: {!! json_encode($inventoryByCategory->pluck('stockValue')) !!},
                    backgroundColor: 'rgba(255, 193, 7, 0.7)'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush