@extends('admin.layouts.app')

@section('title', 'Sales Returns')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sales Returns</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and track all returned orders</p>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fas fa-print"></i>
                <span class="hidden sm:inline">Print</span>
            </button>

            <button onclick="exportReturns()"
                class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition flex items-center gap-2">
                <i class="fas fa-download"></i>
                <span class="hidden sm:inline">Export</span>
            </button>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">

        <form method="GET" action="{{ route('admin.saleReturns.index') }}" class="space-y-4">
            {{-- SEARCH + FILTERS --}}
            <div class="grid md:grid-cols-4 gap-4">

                {{-- SEARCH (GLOBAL) --}}
                <div class="md:col-span-2 relative">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search customer, phone, order #..."
                        class="w-full h-11 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">

                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                </div>

                {{-- FROM DATE --}}
                <div>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full h-11 px-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- TO DATE --}}
                <div>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="w-full h-11 px-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex items-center gap-2">

                <button type="submit" class="h-11 px-6 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>

                <a href="{{ route('admin.saleReturns.index') }}"
                    class="h-11 px-6 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>

            </div>

        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Return</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Items</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Refund</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Method</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Returned By</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($returns as $return)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- RETURN ID --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="font-semibold text-blue-600">
                                        #{{ $return->return_number }}
                                    </p>
                                    <p class="text-xs text-gray-500">ID: {{ $return->id }}</p>
                                </div>
                            </td>

                            {{-- ORDER --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $return->sale_id) }}"
                                    class="font-medium text-blue-600 hover:underline">
                                    {{ $return->order_number }}
                                </a>
                            </td>

                            {{-- CUSTOMER --}}
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $return->order?->customer?->name }}</p>
                                <p class="text-sm text-gray-500">{{ $return->order?->customer?->phone }}</p>
                            </td>

                            {{-- ITEMS --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $return->items_count }} item(s)
                                </span>
                            </td>

                            {{-- REFUND --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-base font-bold text-green-600">
                                    {{ money($return->refund_amount) }}
                                </span>
                            </td>

                            {{-- METHOD --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                    {{ ucfirst($return->refund_method) }}
                                </span>
                            </td>

                            {{-- Returned by --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $return->employee->name }}
                                </span>
                            </td>

                            {{-- DATE --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $return->created_at->format('M d, Y') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $return->created_at->format('h:i A') }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.saleReturns.show', $return->id) }}"
                                    class="w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-50 rounded-lg">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty

                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-undo text-3xl mb-2"></i>
                                    <p class="font-semibold">No returns found</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($returns->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $returns->links() }}
        </div>
    @endif

    </div>

@endsection

@push('scripts')
    <script>


        window.open(`/admin/returns/${id}/print`, '_blank');
        }

        function exportReturns() {
            const params = new URLSearchParams(window.location.search);
            params.append('export', 'csv');
            window.location.href = `{{ route('admin.saleReturns.index') }}?${params.toString()}`;
        }

    </script>
@endpush