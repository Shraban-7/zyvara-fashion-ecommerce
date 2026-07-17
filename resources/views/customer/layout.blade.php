@extends('layouts.app')

@section('content')
    <div class="bg-light min-h-screen py-6 md:py-10">
        <div class="container mx-auto px-4 max-w-7xl">
            @php
                $user = auth()->user();
                // Stats supplied by controller (e.g. $stats); fall back to safe defaults for demo.
                $stats = $stats ?? [
                    'total_orders'   => 0,
                    'pending_orders' => 0,
                    'wishlist_count' => 0,
                    'reward_points'  => 0,
                ];
                $greetingName = Str::before($user->name, ' ') ?: $user->name;
            @endphp

            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                {{-- Sidebar (desktop) + tabs (mobile) --}}
                @include('partials.account-sidebar')

                {{-- Main content --}}
                <main class="flex-1 min-w-0">
                    {{-- Greeting header --}}
                    <div class="mb-6">
                        <p class="text-sm text-secondary-400">Welcome back,</p>
                        <h1 class="font-heading text-2xl md:text-3xl font-semibold text-primary">Hi, {{ $greetingName }}</h1>

                        {{-- Account summary stats --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
                            <div class="bg-surface-elevated rounded-xl border border-secondary-100 p-4">
                                <p class="text-xs text-secondary-400 font-medium">Total Orders</p>
                                <p class="font-heading text-2xl font-semibold text-primary mt-0.5">{{ $stats['total_orders'] }}</p>
                            </div>
                            <div class="bg-surface-elevated rounded-xl border border-secondary-100 p-4">
                                <p class="text-xs text-secondary-400 font-medium">Wishlist</p>
                                <p class="font-heading text-2xl font-semibold text-primary mt-0.5">{{ $stats['wishlist_count'] }}</p>
                            </div>
                            <div class="bg-surface-elevated rounded-xl border border-secondary-100 p-4">
                                <p class="text-xs text-secondary-400 font-medium">Pending</p>
                                <p class="font-heading text-2xl font-semibold text-primary mt-0.5">{{ $stats['pending_orders'] }}</p>
                            </div>
                            <div class="bg-surface-elevated rounded-xl border border-secondary-100 p-4">
                                <p class="text-xs text-secondary-400 font-medium">Reward Points</p>
                                <p class="font-heading text-2xl font-semibold text-accent-600 mt-0.5">{{ $stats['reward_points'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Flash messages --}}
                    @if(session('success'))
                        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                            <i class="fas fa-check-circle text-xl text-success-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold">Success!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-danger-50 border-l-4 border-danger-500 text-danger-700 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                            <i class="fas fa-exclamation-circle text-xl text-danger-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold">Error!</p>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @yield('dashboard-content')
                </main>
            </div>
        </div>
    </div>
@endsection
