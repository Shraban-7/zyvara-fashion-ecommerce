@extends('admin.layouts.app')

@section('title', 'Return Details')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

    <div>
        <h1 class="text-2xl font-bold text-primary">
            Return #{{ $return->return_number }}
        </h1>
        <p class="text-sm text-secondary-500 mt-1">
            Order: {{ $return->order->order_number ?? 'N/A' }}
        </p>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('admin.saleReturns.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition">
            Back
        </a>

    </div>

</div>

{{-- SUMMARY CARDS --}}
<div class="grid md:grid-cols-3 gap-4 mb-6">

    <div class="bg-white border rounded-2xl p-4">
        <p class="text-sm text-secondary-500">Refund Amount</p>
        <p class="text-xl font-bold text-success">
            {{ money($return->refund_amount) }}
        </p>
    </div>

    <div class="bg-white border rounded-2xl p-4">
        <p class="text-sm text-secondary-500">Refund Method</p>
        <p class="text-xl font-bold text-primary">
            {{ ucfirst($return->refund_method) }}
        </p>
    </div>

    <div class="bg-white border rounded-2xl p-4">
        <p class="text-sm text-secondary-500">Items Returned</p>
        <p class="text-xl font-bold text-primary">
            {{ $return->items->count() }}
        </p>
    </div>

</div>

{{-- CUSTOMER INFO --}}
<div class="bg-white border rounded-2xl p-6 mb-6">

    <h2 class="text-lg font-semibold mb-4">Customer Information</h2>

    <div class="grid md:grid-cols-3 gap-4">

        <div>
            <p class="text-sm text-secondary-500">Name</p>
            <p class="font-medium">{{ $return->customer_name }}</p>
        </div>

        <div>
            <p class="text-sm text-secondary-500">Phone</p>
            <p class="font-medium">{{ $return->customer_phone }}</p>
        </div>

        <div>
            <p class="text-sm text-secondary-500">Created At</p>
            <p class="font-medium">
                {{ $return->created_at->format('M d, Y h:i A') }}
            </p>
        </div>

    </div>

</div>

{{-- RETURN ITEMS --}}
<div class="bg-white border rounded-2xl overflow-hidden">

    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold">Returned Items</h2>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-secondary-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Unit Price</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Total</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($return->items as $item)
                <tr class="hover:bg-secondary-50">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-secondary-100 rounded-lg overflow-hidden">
                                <img src="{{ $item->product_image }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-medium text-primary">
                                    {{ $item->product_name }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->quantity }}
                    </td>

                    <td class="px-6 py-4">
                        {{ money($item->unit_price) }}
                    </td>

                    <td class="px-6 py-4 font-semibold">
                        {{ money($item->quantity * $item->unit_price) }}
                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

</div>

{{-- REMARKS --}}
@if($return->remarks)
<div class="bg-white border rounded-2xl p-6 mt-6">
    <h2 class="text-lg font-semibold mb-2">Remarks</h2>
    <p class="text-secondary-600">{{ $return->remarks }}</p>
</div>
@endif

@endsection