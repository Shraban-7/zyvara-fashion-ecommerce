@extends('admin.layouts.app')
@section('title', 'Stock History')
@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-2">Stock History</h1>
            <p class="text-secondary-500">{{ $product->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.manage-stock', $product) }}"
                class="px-4 py-2 text-sm font-medium text-primary bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 transition">
                <i class="fas fa-boxes mr-2"></i>Manage Stock
            </a>
            <a href="{{ route('admin.products.edit', $product) }}"
                class="px-4 py-2 text-sm font-medium text-secondary-700 bg-white border border-secondary-300 rounded-lg hover:bg-secondary-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Edit
            </a>
        </div>
    </div>
</div>

{{-- Stock Summary --}}
<div class="grid md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-secondary-600 mb-1">Current Stock</p>
                <p class="text-3xl font-bold text-primary">
                    {{ $product->totalStock }}
                </p>
                <p class="text-xs text-secondary-500 mt-1">
                    @if($product->variants->isNotEmpty())
                    Total across all variants
                    @else
                    Base product stock
                    @endif
                </p>
            </div>
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-boxes text-primary text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-secondary-600 mb-1">Total Stock In</p>
                <p class="text-3xl font-bold text-success">
                    {{ $product->totalStockIn }}
                </p>
                <p class="text-xs text-secondary-500 mt-1">Added to inventory</p>
            </div>
            <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-down text-success text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-secondary-600 mb-1">Total Stock Out</p>
                <p class="text-3xl font-bold text-danger">
                    {{ $product->totalStockOut }}
                </p>
                <p class="text-xs text-secondary-500 mt-1">Removed from inventory</p>
            </div>
            <div class="w-12 h-12 bg-danger-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-up text-danger text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Stock History Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
   

    @if($stockLogs->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-secondary-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Variant</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Stock Change</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">By</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Note</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($stockLogs as $log)
                <tr class="hover:bg-secondary-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-primary">{{ $log->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-secondary-500">{{ $log->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-primary">
                            @if($log->product_variant_id)
                            {{ $log->productVariant->variant_name }}
                            @else
                            <span class="text-secondary-500">Base Product</span>
                            @endif
                        </div>
                        @if($log->productVariant && $log->productVariant->sku)
                        <div class="text-xs text-secondary-500">{{ $log->productVariant->sku }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($log->type === 'in')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success-100 text-success">
                            <i class="fas fa-arrow-down mr-1"></i>Stock In
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-danger-100 text-danger">
                            <i class="fas fa-arrow-up mr-1"></i>Stock Out
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-semibold {{ $log->type === 'in' ? 'text-success' : 'text-danger' }}">
                            {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-primary">
                            <span class="text-secondary-500">{{ $log->stock_before }}</span>
                            <i class="fas fa-arrow-right mx-1 text-xs text-secondary-400"></i>
                            <span class="font-semibold">{{ $log->stock_after }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-primary">{{ $log->user->name }}</div>
                        <div class="text-xs text-secondary-500">{{ $log->user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($log->note)
                        <p class="text-sm text-secondary-600 max-w-xs">{{ $log->note }}</p>
                        @else
                        <span class="text-sm text-secondary-400">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($stockLogs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $stockLogs->links() }}
    </div>
    @endif
    @else
    <div class="p-12 text-center">
        <div class="w-16 h-16 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-history text-secondary-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-primary mb-2">No Stock History</h3>
        <p class="text-secondary-500 mb-6">There haven't been any stock transactions for this product yet.</p>
        <a href="{{ route('admin.products.manage-stock', $product) }}"
            class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary-700 transition">
            <i class="fas fa-plus mr-2"></i>Add Stock
        </a>
    </div>
    @endif
</div>

@endsection