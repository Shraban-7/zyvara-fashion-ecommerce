@extends('customer.layout')
@section('title', 'Returns & Exchanges')

@section('dashboard-content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-bold text-secondary-800">Returns & Exchanges</h2>
        <p class="text-secondary-600 mt-1">Track your return and exchange requests</p>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-success-50 border border-success-200 px-4 py-3 text-sm text-success-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-lg bg-danger-50 border border-danger-200 px-4 py-3 text-sm text-danger-700">
            {{ session('error') }}
        </div>
    @endif

    @if($requests->count() > 0)
        <div class="space-y-4">
            @foreach($requests as $req)
                @php
                    $item = $req->orderItem;
                    $product = $item->product ?? null;
                    $img = $product && $product->images->count() ? asset('storage/'.$product->images->first()->image_path) : null;
                @endphp
                <div class="bg-surface-elevated rounded-lg shadow-md p-5 hover:shadow-lg transition">
                    <div class="flex flex-col md:flex-row gap-5">
                        <div class="w-full md:w-24 h-24 flex-shrink-0">
                            @if($img)
                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <div class="w-full h-full bg-secondary-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-secondary-400 text-xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="flex items-start justify-between flex-wrap gap-2">
                                <div>
                                    <a href="{{ route('orders.returns.show', $req->id) }}"
                                        class="font-semibold text-secondary-800 hover:text-primary transition">
                                        {{ $product ? $product->name : 'Order Item' }}
                                    </a>
                                    <p class="text-sm text-secondary-400 mt-1">
                                        {{ $req->type->label() }} · Order #{{ $req->order->order_number ?? 'N/A' }}
                                        · Requested {{ $req->requested_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $req->status->colorClass() }}">
                                    {{ $req->status->label() }}
                                </span>
                            </div>

                            @if($req->isExchange && $req->exchangeDetail)
                                <p class="text-sm text-secondary-500 mt-2">
                                    Swap to: <span class="font-medium text-secondary-700">{{ $req->exchangeDetail->requestedVariant->variant_name ?? 'N/A' }}</span>
                                    @if($req->exchangeDetail->price_difference != 0)
                                        <span class="ml-1 text-accent font-semibold">
                                            ({{ $req->exchangeDetail->price_difference > 0 ? '+' : '' }}{{ money($req->exchangeDetail->price_difference) }})
                                        </span>
                                    @endif
                                </p>
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('orders.returns.show', $req->id) }}"
                                    class="text-sm font-semibold text-accent hover:text-primary transition">View details →</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    @else
        <div class="bg-surface-elevated rounded-lg shadow-md p-12 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-secondary-100 text-secondary-400">
                <i class="fas fa-undo text-xl"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-secondary-800">No return or exchange requests</h3>
            <p class="mt-1 text-secondary-500">When you request a return or exchange, it will appear here.</p>
            <a href="{{ route('customer.orders.index') }}"
                class="inline-flex items-center gap-2 mt-4 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700">
                Go to My Orders
            </a>
        </div>
    @endif
</div>
@endsection
