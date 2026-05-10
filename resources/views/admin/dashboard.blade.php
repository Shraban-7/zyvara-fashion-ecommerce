@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<main class="space-y-6">
    <section class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h1 class="text-2xl font-bold tracking-tight text-slate-950 mb-4 md:mb-0">
            Welcome, {{ auth()->user()->name }}!
        </h1>
        <div class="flex flex-col gap-3 sm:flex-row">
            <button
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                <i data-lucide="calendar-days" class="h-4 w-4"></i>
                Last 30 Days
            </button>
        </div>
    </section>

    <!-- Stats Cards -->
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article
            class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Revenue</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">$128,420</h2>
                </div>

                <div class="rounded-2xl bg-emerald-50 p-3 text-emerald-600">
                    <i data-lucide="dollar-sign" class="h-6 w-6"></i>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between">
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-600">
                    <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                    12.8%
                </span>
                <span class="text-xs text-slate-400">vs last month</span>
            </div>
        </article>

        <article
            class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Orders</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">3,482</h2>
                </div>

                <div class="rounded-2xl bg-blue-50 p-3 text-blue-600">
                    <i data-lucide="shopping-cart" class="h-6 w-6"></i>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between">
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-600">
                    <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                    8.2%
                </span>
                <span class="text-xs text-slate-400">vs last month</span>
            </div>
        </article>

        <article
            class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Customers</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">12,846</h2>
                </div>

                <div class="rounded-2xl bg-violet-50 p-3 text-violet-600">
                    <i data-lucide="users" class="h-6 w-6"></i>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between">
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-600">
                    <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                    4.6%
                </span>
                <span class="text-xs text-slate-400">vs last month</span>
            </div>
        </article>

        <article
            class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Refunds</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">$2,860</h2>
                </div>

                <div class="rounded-2xl bg-rose-50 p-3 text-rose-600">
                    <i data-lucide="credit-card" class="h-6 w-6"></i>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between">
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-600">
                    <i data-lucide="arrow-down-right" class="h-3.5 w-3.5"></i>
                    2.4%
                </span>
                <span class="text-xs text-slate-400">vs last month</span>
            </div>
        </article>
    </section>

    <!-- Main Grid -->
    <section class="grid gap-6 xl:grid-cols-3">
        <!-- Revenue Chart -->
        <article
            class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Revenue Overview</h2>
                    <p class="text-sm text-slate-500">
                        Monthly revenue and order performance
                    </p>
                </div>

                
            </div>

           <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
        </article>

        <!-- Sales Channels -->
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Sales Channels</h2>
                    <p class="text-sm text-slate-500">Orders by platform</p>
                </div>

                <button
                    class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <i data-lucide="more-horizontal" class="h-5 w-5"></i>
                </button>
            </div>

            <div class="mt-6 flex justify-center">
                <div
                    class="relative grid h-44 w-44 place-items-center rounded-full bg-[conic-gradient(#4f46e5_0_42%,#10b981_42%_70%,#f59e0b_70%_88%,#ef4444_88%_100%)]">
                    <div class="grid h-28 w-28 place-items-center rounded-full bg-white">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-slate-950">8.4k</p>
                            <p class="text-xs text-slate-500">Orders</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 font-medium text-slate-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-indigo-600"></span>
                            Website
                        </span>
                        <span class="font-semibold text-slate-950">42%</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100">
                        <div class="h-2 w-[42%] rounded-full bg-indigo-600"></div>
                    </div>
                </div>

                <div>
                    <div class="mb-1.5 flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 font-medium text-slate-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Mobile App
                        </span>
                        <span class="font-semibold text-slate-950">28%</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100">
                        <div class="h-2 w-[28%] rounded-full bg-emerald-500"></div>
                    </div>
                </div>

                <div>
                    <div class="mb-1.5 flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 font-medium text-slate-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                            Marketplace
                        </span>
                        <span class="font-semibold text-slate-950">18%</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100">
                        <div class="h-2 w-[18%] rounded-full bg-amber-500"></div>
                    </div>
                </div>

                <div>
                    <div class="mb-1.5 flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 font-medium text-slate-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                            Social Media
                        </span>
                        <span class="font-semibold text-slate-950">12%</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100">
                        <div class="h-2 w-[12%] rounded-full bg-rose-500"></div>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <!-- Lower Grid -->
    <section class="grid gap-6 xl:grid-cols-3">
        <!-- Recent Orders -->
        <article
            class="rounded-3xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div
                class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Recent Orders</h2>
                    <p class="text-sm text-slate-500">Latest customer purchases</p>
                </div>

                <button
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    <i data-lucide="filter" class="h-4 w-4"></i>
                    Filter
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Order</th>
                            <th class="px-5 py-4 font-semibold">Customer</th>
                            <th class="px-5 py-4 font-semibold">Product</th>
                            <th class="px-5 py-4 font-semibold">Amount</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4 text-sm font-semibold text-slate-950">
                                #ORD-7841
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="grid h-9 w-9 place-items-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600">
                                        AR
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">
                                            Ayesha Rahman
                                        </p>
                                        <p class="text-xs text-slate-400">Today</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">
                                Wireless Headphones
                            </td>
                            <td class="px-5 py-4 text-sm font-semibold text-slate-950">
                                $129.00
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                    Completed
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <button class="text-slate-400 hover:text-slate-700">
                                    <i data-lucide="eye" class="h-5 w-5"></i>
                                </button>
                            </td>
                        </tr>

                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4 text-sm font-semibold text-slate-950">
                                #ORD-7840
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="grid h-9 w-9 place-items-center rounded-full bg-blue-100 text-sm font-bold text-blue-600">
                                        TH
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">
                                            Tanvir Hasan
                                        </p>
                                        <p class="text-xs text-slate-400">Today</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">Smart Watch Pro</td>
                            <td class="px-5 py-4 text-sm font-semibold text-slate-950">
                                $249.00
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600">
                                    Processing
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <button class="text-slate-400 hover:text-slate-700">
                                    <i data-lucide="eye" class="h-5 w-5"></i>
                                </button>
                            </td>
                        </tr>

                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4 text-sm font-semibold text-slate-950">
                                #ORD-7839
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="grid h-9 w-9 place-items-center rounded-full bg-rose-100 text-sm font-bold text-rose-600">
                                        NS
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">
                                            Nadia Sultana
                                        </p>
                                        <p class="text-xs text-slate-400">Yesterday</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">Running Shoes</td>
                            <td class="px-5 py-4 text-sm font-semibold text-slate-950">
                                $89.00
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                    Shipped
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <button class="text-slate-400 hover:text-slate-700">
                                    <i data-lucide="eye" class="h-5 w-5"></i>
                                </button>
                            </td>
                        </tr>

                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4 text-sm font-semibold text-slate-950">
                                #ORD-7838
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="grid h-9 w-9 place-items-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-600">
                                        KI
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">
                                            Karim Islam
                                        </p>
                                        <p class="text-xs text-slate-400">Yesterday</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">Leather Backpack</td>
                            <td class="px-5 py-4 text-sm font-semibold text-slate-950">
                                $156.00
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600">
                                    Cancelled
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <button class="text-slate-400 hover:text-slate-700">
                                    <i data-lucide="eye" class="h-5 w-5"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </article>

        <!-- Top Products -->
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Top Products</h2>
                    <p class="text-sm text-slate-500">Best selling items</p>
                </div>

                <button class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                    View All
                </button>
            </div>

            <div class="mt-6 space-y-4">
                <div
                    class="flex items-center gap-4 rounded-2xl border border-slate-100 p-3 transition hover:bg-slate-50">
                    <div
                        class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                        <i data-lucide="headphones" class="h-6 w-6"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-950">
                            Wireless Headphones
                        </p>
                        <div class="mt-1 flex items-center gap-1 text-xs text-amber-500">
                            <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                            <span>4.9</span>
                            <span class="text-slate-400">(1.2k)</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-950">$129</p>
                        <p class="text-xs text-slate-400">482 sold</p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-4 rounded-2xl border border-slate-100 p-3 transition hover:bg-slate-50">
                    <div
                        class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                        <i data-lucide="watch" class="h-6 w-6"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-950">
                            Smart Watch Pro
                        </p>
                        <div class="mt-1 flex items-center gap-1 text-xs text-amber-500">
                            <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                            <span>4.8</span>
                            <span class="text-slate-400">(980)</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-950">$249</p>
                        <p class="text-xs text-slate-400">391 sold</p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-4 rounded-2xl border border-slate-100 p-3 transition hover:bg-slate-50">
                    <div
                        class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                        <i data-lucide="shirt" class="h-6 w-6"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-950">
                            Cotton T-Shirt
                        </p>
                        <div class="mt-1 flex items-center gap-1 text-xs text-amber-500">
                            <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                            <span>4.7</span>
                            <span class="text-slate-400">(763)</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-950">$39</p>
                        <p class="text-xs text-slate-400">354 sold</p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-4 rounded-2xl border border-slate-100 p-3 transition hover:bg-slate-50">
                    <div
                        class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                        <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-950">
                            Leather Backpack
                        </p>
                        <div class="mt-1 flex items-center gap-1 text-xs text-amber-500">
                            <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                            <span>4.6</span>
                            <span class="text-slate-400">(641)</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-950">$156</p>
                        <p class="text-xs text-slate-400">286 sold</p>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <!-- Bottom Grid -->
    <section class="grid gap-6 lg:grid-cols-2">
        <!-- Inventory Alerts -->
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Inventory Alerts</h2>
                    <p class="text-sm text-slate-500">Products requiring attention</p>
                </div>

                <div class="rounded-2xl bg-amber-50 p-3 text-amber-600">
                    <i data-lucide="boxes" class="h-5 w-5"></i>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="rounded-2xl border border-rose-100 bg-rose-50/60 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex gap-3">
                            <div class="rounded-xl bg-white p-2 text-rose-600">
                                <i data-lucide="package-x" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-950">Running Shoes</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    Only 4 units left in stock
                                </p>
                            </div>
                        </div>

                        <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-600">
                            Low
                        </span>
                    </div>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50/60 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex gap-3">
                            <div class="rounded-xl bg-white p-2 text-amber-600">
                                <i data-lucide="package" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-950">Bluetooth Speaker</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    12 units remaining, reorder soon
                                </p>
                            </div>
                        </div>

                        <span
                            class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-600">
                            Medium
                        </span>
                    </div>
                </div>

                <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex gap-3">
                            <div class="rounded-xl bg-white p-2 text-blue-600">
                                <i data-lucide="truck" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-950">Laptop Stand</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    New shipment arriving tomorrow
                                </p>
                            </div>
                        </div>

                        <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-600">
                            Incoming
                        </span>
                    </div>
                </div>
            </div>
        </article>

        <!-- Customer Activity -->
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Customer Activity</h2>
                    <p class="text-sm text-slate-500">Recent store interactions</p>
                </div>

                <div class="rounded-2xl bg-indigo-50 p-3 text-indigo-600">
                    <i data-lucide="activity" class="h-5 w-5"></i>
                </div>
            </div>

            <div class="mt-6 space-y-5">
                <div class="relative flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                            <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                        </div>
                        <div class="mt-2 h-full w-px bg-slate-200"></div>
                    </div>
                    <div class="pb-5">
                        <p class="text-sm font-semibold text-slate-950">
                            New order placed by Ayesha Rahman
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Purchased Wireless Headphones for $129.00
                        </p>
                        <p class="mt-2 text-xs text-slate-400">2 minutes ago</p>
                    </div>
                </div>

                <div class="relative flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="grid h-10 w-10 place-items-center rounded-full bg-blue-50 text-blue-600">
                            <i data-lucide="user-plus" class="h-5 w-5"></i>
                        </div>
                        <div class="mt-2 h-full w-px bg-slate-200"></div>
                    </div>
                    <div class="pb-5">
                        <p class="text-sm font-semibold text-slate-950">
                            18 new customers registered
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Most signups came from mobile app campaign
                        </p>
                        <p class="mt-2 text-xs text-slate-400">28 minutes ago</p>
                    </div>
                </div>

                <div class="relative flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="grid h-10 w-10 place-items-center rounded-full bg-violet-50 text-violet-600">
                            <i data-lucide="star" class="h-5 w-5"></i>
                        </div>
                        <div class="mt-2 h-full w-px bg-slate-200"></div>
                    </div>
                    <div class="pb-5">
                        <p class="text-sm font-semibold text-slate-950">
                            Product review received
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Smart Watch Pro received a 5-star review
                        </p>
                        <p class="mt-2 text-xs text-slate-400">1 hour ago</p>
                    </div>
                </div>

                <div class="relative flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="grid h-10 w-10 place-items-center rounded-full bg-rose-50 text-rose-600">
                            <i data-lucide="rotate-ccw" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-950">
                            Refund request submitted
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Order #ORD-7838 is awaiting approval
                        </p>
                        <p class="mt-2 text-xs text-slate-400">3 hours ago</p>
                    </div>
                </div>
            </div>
        </article>
    </section>
</main>

@endsection

@section('content_old')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-900 mb-2">Welcome, {{ auth()->user()->name }}</h1>
    {{--<p class="text-gray-500">Welcome back! Here's what's happening with your store today.</p>--}}
</div>

{{-- Quick Stats Grid --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div
                class="w-10 h-10 bg-linear-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-white text-base"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">+12%</span>
        </div>
        <h3 class="text-gray-500 text-xs font-medium mb-1">Total Revenue</h3>
        <p class="text-xl font-bold text-gray-900">{{ money($widgets['totalRevenue']) }}</p>
    </div>

    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div
                class="w-10 h-10 bg-linear-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-white text-base"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">+8%</span>
        </div>
        <h3 class="text-gray-500 text-xs font-medium mb-1">Total Orders</h3>
        <p class="text-xl font-bold text-gray-900">{{ number_format($widgets['totalOrders'], 0) }}</p>
    </div>

    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div
                class="w-10 h-10 bg-linear-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-white text-base"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">+15%</span>
        </div>
        <h3 class="text-gray-500 text-xs font-medium mb-1">Customers</h3>
        <p class="text-xl font-bold text-gray-900">{{ number_format($widgets['totalCustomers'], 0) }}</p>
    </div>

    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div
                class="w-10 h-10 bg-linear-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-white text-base"></i>
            </div>
            <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">Pending</span>
        </div>
        <h3 class="text-gray-500 text-xs font-medium mb-1">Pending Orders</h3>
        <p class="text-xl font-bold text-gray-900">{{ number_format($widgets['pendingOrders'], 0) }}</p>
    </div>

    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div
                class="w-10 h-10 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-white text-base"></i>
            </div>
        </div>
        <h3 class="text-gray-500 text-xs font-medium mb-1">Total Products</h3>
        <p class="text-xl font-bold text-gray-900">{{ number_format($widgets['totalProducts'], 0) }}</p>
    </div>

    {{-- Total Categories --}}
    <!-- <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tags text-white text-base"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-xs font-medium mb-1">Categories</h3>
            <p class="text-xl font-bold text-gray-900">{{ number_format($widgets['totalCategories'], 0) }}</p>
        </div> -->

    {{-- Average Order Value --}}
    <!-- <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-base"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-xs font-medium mb-1">Avg Order Value</h3>
            <p class="text-xl font-bold text-gray-900">{{ money($widgets['avgOrderValue']) }}</p>
        </div> -->

    {{-- Today's Orders --}}
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div
                class="w-10 h-10 bg-linear-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-day text-white text-base"></i>
            </div>
        </div>
        <h3 class="text-gray-500 text-xs font-medium mb-1">Today's Orders</h3>
        <p class="text-xl font-bold text-gray-900">{{ number_format($widgets['todayOrders'], 0) }}</p>
    </div>

    {{-- Today's Revenue --}}
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div
                class="w-10 h-10 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-coins text-white text-base"></i>
            </div>
        </div>
        <h3 class="text-gray-500 text-xs font-medium mb-1">Today's Revenue</h3>
        <p class="text-xl font-bold text-gray-900">{{ money($widgets['todayRevenue']) }}</p>
    </div>

    {{-- Out of Stock --}}
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-linear-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-white text-base"></i>
            </div>
            @if($widgets['outOfStock'] > 0)
            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Alert</span>
            @endif
        </div>
        <h3 class="text-gray-500 text-xs font-medium mb-1">Out of Stock</h3>
        <p class="text-xl font-bold text-gray-900">{{ number_format($widgets['outOfStock'], 0) }}</p>
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
        <!-- <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div> -->
    </div>

    {{-- Top Products --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Top Products</h2>
        <p class="text-sm text-gray-500 mb-6">Best selling products this month</p>

        <div class="space-y-4">
            @forelse($topProducts ?? [] as $product)
            <div class="flex items-center gap-3">
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                    class="w-12 h-12 rounded-lg object-cover bg-gray-100">
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

        <a href="{{ route('admin.products.index') }}"
            class="mt-6 flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
            <span>View All Products</span>
            <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </div>
</div>

{{-- Recent Orders and Activity --}}
<div>
    {{-- Recent Orders --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Recent Orders</h2>
                    <p class="text-sm text-gray-500">Latest orders from your store</p>
                </div>
                <a href="{{ route('admin.orders.index') }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-700 transition">View All</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Action</th>
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
                                <div
                                    class="w-8 h-8 bg-linear-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
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
                        @php
                        $status = \App\Enums\OrderStatus::tryFrom($order['status']);
                        @endphp

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($status)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full text-{{ $status->color() }}-700 bg-{{ $status->color() }}-100">
                                {{ $status->label() }}
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">
                                Unknown
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('admin.orders.show', $order['id']) }}"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">View</a>
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