@extends('customer.layout')
@section('title', 'Dashboard')

@section('dashboard-content')
    <div class="space-y-6">
        {{-- Quick actions --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('orders.index') }}"
                class="group flex flex-col items-center gap-2 bg-surface-elevated rounded-xl border border-secondary-100 p-4 hover:border-primary-200 hover:shadow-sm transition">
                <span class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center text-primary-500 group-hover:bg-primary group-hover:text-surface-elevated transition">
                    <i class="fas fa-bag-shopping"></i>
                </span>
                <span class="text-xs font-medium text-secondary-600">My Orders</span>
            </a>
            <a href="{{ route('customer.wishlist') }}"
                class="group flex flex-col items-center gap-2 bg-surface-elevated rounded-xl border border-secondary-100 p-4 hover:border-primary-200 hover:shadow-sm transition">
                <span class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center text-primary-500 group-hover:bg-primary group-hover:text-surface-elevated transition">
                    <i class="fas fa-heart"></i>
                </span>
                <span class="text-xs font-medium text-secondary-600">Wishlist</span>
            </a>
            <a href="{{ route('customer.reviews') }}"
                class="group flex flex-col items-center gap-2 bg-surface-elevated rounded-xl border border-secondary-100 p-4 hover:border-primary-200 hover:shadow-sm transition">
                <span class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center text-primary-500 group-hover:bg-primary group-hover:text-surface-elevated transition">
                    <i class="fas fa-star"></i>
                </span>
                <span class="text-xs font-medium text-secondary-600">Reviews</span>
            </a>
            <a href="{{ route('customer.profile') }}"
                class="group flex flex-col items-center gap-2 bg-surface-elevated rounded-xl border border-secondary-100 p-4 hover:border-primary-200 hover:shadow-sm transition">
                <span class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center text-primary-500 group-hover:bg-primary group-hover:text-surface-elevated transition">
                    <i class="fas fa-user-gear"></i>
                </span>
                <span class="text-xs font-medium text-secondary-600">Settings</span>
            </a>
        </div>

        {{-- Recent orders --}}
        <section class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-secondary-100">
                <h2 class="font-heading text-lg font-semibold text-primary">Recent Orders</h2>
                <a href="{{ route('orders.index') }}" class="text-sm font-medium text-accent-600 hover:text-accent-700 transition">View all</a>
            </div>

            @php
                // Demo fallback if controller hasn't supplied recent_orders yet
                if (!isset($recent_orders)) {
                    $recent_orders = collect();
                }
            @endphp

            @if($recent_orders->count() > 0)
                <div class="divide-y divide-secondary-100">
                    @foreach($recent_orders as $order)
                        @include('partials._order-row', ['order' => $order])
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-light border border-secondary-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bag-shopping text-secondary-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-1">No Orders Yet</h3>
                    <p class="text-sm text-secondary-500 mb-5">Start shopping and your orders will appear here.</p>
                    <a href="{{ route('products.index') }}"
                        class="inline-block bg-primary text-surface-elevated px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition">
                        Browse Products
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection
