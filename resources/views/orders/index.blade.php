@extends('customer.layout')
@section('title', 'My Orders')

@section('dashboard-content')
    <div class="space-y-6">
        <div>
            <h2 class="font-heading text-2xl font-semibold text-primary">My Orders</h2>
            <p class="text-sm text-secondary-500 mt-1">Track, view and manage your orders.</p>
        </div>

        {{-- Filter / sort bar --}}
        <form method="GET" class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-4 flex flex-col sm:flex-row gap-3 sm:items-end">
            <div class="flex-1">
                <label for="status" class="block text-xs font-medium text-secondary-500 mb-1.5">Status</label>
                <select name="status" id="status" onchange="this.form.submit()"
                    class="w-full h-11 px-3 rounded-xl border border-secondary-200 bg-light text-sm text-primary focus:ring-2 focus:ring-accent/30 focus:border-accent transition">
                    <option value="">All Orders</option>
                    @foreach(['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $val => $lbl)
                        <option value="{{ $val }}" {{ (request('status') == $val) ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label for="date" class="block text-xs font-medium text-secondary-500 mb-1.5">Date Range</label>
                <select name="date" id="date" onchange="this.form.submit()"
                    class="w-full h-11 px-3 rounded-xl border border-secondary-200 bg-light text-sm text-primary focus:ring-2 focus:ring-accent/30 focus:border-accent transition">
                    <option value="">Any time</option>
                    <option value="30" {{ request('date') == '30' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ request('date') == '90' ? 'selected' : '' }}>Last 3 months</option>
                    <option value="365" {{ request('date') == '365' ? 'selected' : '' }}>Last 12 months</option>
                </select>
            </div>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-xl border border-secondary-200 text-sm font-medium text-secondary-600 hover:bg-light transition">
                <i class="fas fa-arrows-rotate"></i> Reset
            </a>
        </form>

        @php $orders = $orders ?? collect(); @endphp
        @if($orders->count() > 0)
            {{-- Desktop table --}}
            <div class="hidden lg:block bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm overflow-hidden">
                <div class="divide-y divide-secondary-100">
                    @foreach($orders as $order)
                        @include('partials._order-row', ['order' => $order])
                    @endforeach
                </div>
            </div>

            {{-- Mobile cards --}}
            <div class="lg:hidden space-y-3">
                @foreach($orders as $order)
                    @include('partials._order-card', ['order' => $order])
                @endforeach
            </div>

            <div class="flex justify-center pt-2">
                {{ $orders->links() }}
            </div>
        @else
            <div class="bg-surface-elevated rounded-2xl border border-secondary-100 shadow-sm p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-light border border-secondary-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bag-shopping text-secondary-400 text-2xl"></i>
                </div>
                <h3 class="font-heading text-xl font-semibold text-primary mb-1">No Orders Found</h3>
                <p class="text-sm text-secondary-500 mb-6">You don't have any orders matching this filter.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-primary text-surface-elevated px-6 py-3 rounded-xl text-sm font-medium hover:bg-primary-700 transition">
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
@endsection
