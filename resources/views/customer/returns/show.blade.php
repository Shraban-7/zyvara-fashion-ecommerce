@extends('customer.layout')
@section('title', 'Return Details')

@section('dashboard-content')
<div class="space-y-6">
    <div>
        <a href="{{ route('orders.returns.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-secondary-500 hover:text-primary transition">
            <i class="fas fa-arrow-left"></i> Back to Returns
        </a>
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

    @php
        $item = $returnRequest->orderItem;
        $product = $item->product ?? null;
        $img = $product && $product->images->count() ? asset('storage/'.$product->images->first()->image_path) : null;
    @endphp

    <!-- Status banner -->
    <div class="bg-surface-elevated rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-sm text-secondary-400">
                    {{ $returnRequest->type->label() }} request #{{ $returnRequest->id }}
                    · Order #{{ $returnRequest->order->order_number ?? 'N/A' }}
                </p>
                <h2 class="text-xl font-bold text-secondary-800 mt-1">{{ $product ? $product->name : 'Order Item' }}</h2>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-semibold {{ $returnRequest->status->colorClass() }}">
                {{ $returnRequest->status->label() }}
            </span>
        </div>

        <!-- Status stepper -->
        @php
            $steps = [
                'pending' => 'Submitted',
                'approved' => 'Approved',
                'item_received' => 'Received',
                'resolved' => $returnRequest->isExchange ? 'Exchanged' : 'Refunded',
                'completed' => 'Completed',
            ];
            $current = $returnRequest->status->value;
            $order = ['pending','approved','item_received','resolved','completed'];
            $rejected = $current === 'rejected';
        @endphp

        @if(!$rejected)
            <ol class="mt-6 flex items-center">
                @foreach($order as $i => $step)
                    @php
                        $stepStatus = match(true) {
                            $current === $step => 'current',
                            $step === 'resolved' && in_array($current, ['refunded','exchanged','completed']) => 'done',
                            array_search($current, $order) > array_search($step, $order) => 'done',
                            default => 'todo',
                        };
                    @endphp
                    <li class="flex items-center {{ $i < count($order)-1 ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center text-center">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold border
                                {{ $stepStatus === 'done' ? 'bg-success-500 text-white border-success-500' : ($stepStatus === 'current' ? 'bg-primary text-white border-primary' : 'bg-secondary-100 text-secondary-400 border-secondary-200') }}">
                                @if($stepStatus === 'done')<i class="fas fa-check text-[10px]"></i>@else{{ $i+1 }}@endif
                            </span>
                            <span class="mt-1.5 text-[11px] {{ $stepStatus === 'todo' ? 'text-secondary-400' : 'text-secondary-700 font-medium' }}">{{ $steps[$step] }}</span>
                        </div>
                        @if($i < count($order)-1)
                            <span class="mx-2 h-0.5 flex-1 rounded {{ array_search($current, $order) > $i ? 'bg-success-400' : 'bg-secondary-200' }}"></span>
                        @endif
                    </li>
                @endforeach
            </ol>
        @else
            <div class="mt-6 rounded-lg bg-danger-50 border border-danger-200 px-4 py-3 text-sm text-danger-700">
                This request was rejected. @if($returnRequest->admin_note) Reason: {{ $returnRequest->admin_note }} @endif
            </div>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left: details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-elevated rounded-lg shadow-md p-6">
                <h3 class="text-base font-bold text-secondary-800 mb-4">Request Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-secondary-400">Type</dt>
                        <dd class="font-medium text-secondary-800">{{ $returnRequest->type->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-secondary-400">Reason</dt>
                        <dd class="font-medium text-secondary-800">{{ $returnRequest->reason->label() }}</dd>
                    </div>
                    @if($returnRequest->reason_note)
                        <div class="sm:col-span-2">
                            <dt class="text-secondary-400">Note</dt>
                            <dd class="font-medium text-secondary-700">{{ $returnRequest->reason_note }}</dd>
                        </div>
                    @endif
                    @if($returnRequest->isExchange && $returnRequest->exchangeDetail)
                        <div class="sm:col-span-2">
                            <dt class="text-secondary-400">Exchange to</dt>
                            <dd class="font-medium text-secondary-800">
                                {{ $returnRequest->exchangeDetail->requestedVariant->variant_name ?? 'N/A' }}
                                @if($returnRequest->exchangeDetail->price_difference != 0)
                                    <span class="ml-1 text-accent font-semibold">
                                        ({{ $returnRequest->exchangeDetail->price_difference > 0 ? 'Additional ' : 'Credit ' }}{{ money(abs($returnRequest->exchangeDetail->price_difference)) }})
                                    </span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    @if($returnRequest->refund_amount)
                        <div>
                            <dt class="text-secondary-400">Refund Amount</dt>
                            <dd class="font-medium text-secondary-800">{{ money($returnRequest->refund_amount) }}</dd>
                        </div>
                        <div>
                            <dt class="text-secondary-400">Refund Method</dt>
                            <dd class="font-medium text-secondary-800">{{ $returnRequest->refund_method ?? '—' }}</dd>
                        </div>
                    @endif
                </dl>

                @if($returnRequest->images->count())
                    <div class="mt-5">
                        <p class="text-sm text-secondary-400 mb-2">Photos</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($returnRequest->images as $image)
                                <a href="{{ storage_url($image->image_path) }}" target="_blank" class="block h-20 w-20 overflow-hidden rounded-lg border border-secondary-200">
                                    <img src="{{ storage_url($image->image_path) }}" class="h-full w-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-surface-elevated rounded-lg shadow-md p-6">
                <h3 class="text-base font-bold text-secondary-800 mb-4">Activity</h3>
                <ol class="relative border-l border-secondary-200 ml-2 space-y-5">
                    @foreach($returnRequest->statusHistories as $h)
                        <li class="ml-4">
                            <span class="absolute -left-[7px] mt-1 h-3 w-3 rounded-full bg-accent ring-4 ring-white"></span>
                            <p class="text-sm font-semibold text-secondary-800">{{ $h->status->label() }}</p>
                            @if($h->note)
                                <p class="text-sm text-secondary-600">{{ $h->note }}</p>
                            @endif
                            <p class="text-xs text-secondary-400 mt-0.5">{{ $h->changed_at->format('M d, Y · h:i A') }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <!-- Right: item summary -->
        <div class="space-y-6">
            <div class="bg-surface-elevated rounded-lg shadow-md p-6">
                <h3 class="text-base font-bold text-secondary-800 mb-4">Item</h3>
                <div class="flex gap-4">
                    <div class="w-20 h-20 shrink-0">
                        @if($img)
                            <img src="{{ $img }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <div class="w-full h-full bg-secondary-200 rounded-lg"></div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-secondary-800 truncate">{{ $product ? $product->name : 'Order Item' }}</p>
                        @if($item->variant_description)<p class="text-sm text-secondary-500">{{ $item->variant_description }}</p>@endif
                        <p class="text-sm text-secondary-500 mt-1">Qty: {{ $item->quantity }} · {{ money($item->subtotal) }}</p>
                    </div>
                </div>
                @if($returnRequest->order)
                    <a href="{{ route('customer.orders.show', $returnRequest->order->order_number) }}"
                        class="inline-flex items-center gap-1.5 mt-4 text-sm font-semibold text-accent hover:text-primary transition">
                        View order →
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
