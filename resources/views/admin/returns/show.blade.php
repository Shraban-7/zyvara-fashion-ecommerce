@extends('admin.layouts.app')

@section('title', 'Return Details')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('admin.returns.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-secondary-500 hover:text-primary transition">
            <i class="fas fa-arrow-left"></i> Back to Returns
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-success-50 border border-success-200 px-4 py-3 text-sm text-success-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl bg-danger-50 border border-danger-200 px-4 py-3 text-sm text-danger-700">{{ session('error') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left: details + activity -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-secondary-200 p-6">
                <div class="flex items-start justify-between flex-wrap gap-3">
                    <div>
                        <p class="text-sm text-secondary-400">Request #{{ $returnRequest->id }} · {{ $returnRequest->type->label() }}</p>
                        <h2 class="text-xl font-bold text-primary mt-1">
                            {{ $returnRequest->orderItem->product->name ?? 'Order Item' }}
                        </h2>
                        <p class="text-sm text-secondary-500 mt-1">
                            Order #{{ $returnRequest->order->order_number ?? 'N/A' }} · {{ $returnRequest->user->name ?? 'Guest' }}
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-semibold {{ $returnRequest->status->colorClass() }}">
                        {{ $returnRequest->status->label() }}
                    </span>
                </div>

                <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm border-t border-secondary-100 pt-5">
                    <div>
                        <dt class="text-secondary-400">Reason</dt>
                        <dd class="font-medium text-secondary-800">{{ $returnRequest->reason->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-secondary-400">Requested</dt>
                        <dd class="font-medium text-secondary-800">{{ $returnRequest->requested_at->format('M d, Y · h:i A') }}</dd>
                    </div>
                    @if($returnRequest->reason_note)
                        <div class="sm:col-span-2">
                            <dt class="text-secondary-400">Customer Note</dt>
                            <dd class="font-medium text-secondary-700">{{ $returnRequest->reason_note }}</dd>
                        </div>
                    @endif
                    @if($returnRequest->isExchange && $returnRequest->exchangeDetail)
                        <div class="sm:col-span-2">
                            <dt class="text-secondary-400">Exchange Target</dt>
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
                    @if($returnRequest->admin_note)
                        <div class="sm:col-span-2">
                            <dt class="text-secondary-400">Admin Note</dt>
                            <dd class="font-medium text-secondary-700">{{ $returnRequest->admin_note }}</dd>
                        </div>
                    @endif
                </dl>

                @if($returnRequest->images->count())
                    <div class="mt-5">
                        <p class="text-sm text-secondary-400 mb-2">Customer Photos</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($returnRequest->images as $image)
                                <a href="{{ storage_url($image->image_path) }}" target="_blank" class="block h-24 w-24 overflow-hidden rounded-lg border border-secondary-200">
                                    <img src="{{ storage_url($image->image_path) }}" class="h-full w-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Activity -->
            <div class="bg-white rounded-2xl border border-secondary-200 p-6">
                <h3 class="text-base font-bold text-primary mb-4">Activity Timeline</h3>
                <ol class="relative border-l border-secondary-200 ml-2 space-y-5">
                    @foreach($returnRequest->statusHistories as $h)
                        <li class="ml-4">
                            <span class="absolute -left-[7px] mt-1 h-3 w-3 rounded-full bg-accent ring-4 ring-white"></span>
                            <p class="text-sm font-semibold text-secondary-800">{{ $h->status->label() }}</p>
                            @if($h->note)<p class="text-sm text-secondary-600">{{ $h->note }}</p>@endif
                            <p class="text-xs text-secondary-400 mt-0.5">{{ $h->changed_at->format('M d, Y · h:i A') }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <!-- Right: actions -->
        <div class="space-y-6">
            <!-- Item summary -->
            <div class="bg-white rounded-2xl border border-secondary-200 p-6">
                <h3 class="text-base font-bold text-primary mb-4">Item</h3>
                @php $item = $returnRequest->orderItem; @endphp
                <p class="font-semibold text-secondary-800">{{ $item->product->name ?? 'Order Item' }}</p>
                @if($item->variant_description)<p class="text-sm text-secondary-500">{{ $item->variant_description }}</p>@endif
                <p class="text-sm text-secondary-500 mt-1">Qty: {{ $item->quantity }} · {{ money($item->subtotal) }}</p>
            </div>

            <!-- Action panel -->
            @if(!$returnRequest->status->isTerminal())
                <div class="bg-white rounded-2xl border border-secondary-200 p-6">
                    <h3 class="text-base font-bold text-primary mb-4">Update Status</h3>
                    <form method="POST" action="{{ route('admin.returns.update-status', $returnRequest->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-secondary-600 mb-1.5">New Status</label>
                            <select name="status" class="w-full h-11 px-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary">
                                @foreach($nextStatuses as $st)
                                    <option value="{{ $st->value }}">{{ $st->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-600 mb-1.5">Admin Note</label>
                            <textarea name="admin_note" rows="3"
                                class="w-full px-3 py-2 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary"
                                placeholder="Optional note / rejection reason"></textarea>
                        </div>
                        @if($returnRequest->isReturn)
                            <div>
                                <label class="block text-sm font-medium text-secondary-600 mb-1.5">Refund Amount</label>
                                <input type="number" step="0.01" name="refund_amount" value="{{ old('refund_amount', $item->subtotal) }}"
                                    class="w-full h-11 px-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-600 mb-1.5">Refund Method</label>
                                <input type="text" name="refund_method" value="{{ old('refund_method', 'Original Payment Method') }}"
                                    class="w-full h-11 px-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary">
                            </div>
                        @endif
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700">
                            Apply Update
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-secondary-200 p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-secondary-100 text-secondary-400">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="mt-3 text-sm font-medium text-secondary-700">This request is {{ $returnRequest->status->label() }}.</p>
                    <p class="text-xs text-secondary-400 mt-1">No further action required.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
