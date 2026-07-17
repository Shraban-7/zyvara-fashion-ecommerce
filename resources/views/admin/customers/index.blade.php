@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary">Customers</h1>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mb-4 border-b border-secondary-200">
        <div class="flex gap-6">
            <button onclick="switchTab('pos')" id="tab-pos"
                class="tab-btn pb-2 text-sm font-semibold border-b-2 text-primary border-primary">
                POS Customers
            </button>

            <button onclick="switchTab('web')" id="tab-web"
                class="tab-btn pb-2 text-sm font-semibold border-b-2 text-secondary-500 border-transparent hover:text-secondary-700">
                Website Customers
            </button>
        </div>
    </div>

    {{-- ================= POS CUSTOMERS ================= --}}
    <div id="posTable">

        {{-- Filter --}}
        <form method="GET" class="bg-white border rounded-xl p-4 mb-4">

            <input type="hidden" name="tab" value="pos">

            <div class="flex flex-col md:flex-row gap-3">

                {{-- SEARCH --}}
                <div class="flex-1">
                    <input type="text" name="pos_search" value="{{ request('pos_search') }}"
                        placeholder="Search POS by name or phone..."
                        class="w-full h-11 px-4 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                {{-- BUTTON GROUP --}}
                <div class="flex gap-2 md:items-end">

                    <button type="submit"
                        class="h-11 px-5 bg-primary text-white rounded-xl hover:bg-primary-700 transition font-medium flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>

                    <a href="{{ route('admin.customers.index') }}"
                        class="h-11 px-4 border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>

                </div>

            </div>

        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-secondary-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-secondary-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($posUsers as $customer)
                            <tr class="hover:bg-secondary-50">

                                <td class="px-6 py-4 flex items-center gap-3">
                                    <img class="w-10 h-10 rounded-full border"
                                        src="{{ $customer->image ? asset('storage/' . $customer->image) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) }}">
                                    <div>
                                        <div class="font-medium text-primary">{{ $customer->name }}</div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div>{{ $customer->phone }}</div>
                                    <div class="text-xs text-secondary-400">{{ $customer->email ?? 'No email' }}</div>
                                </td>

                                {{-- POS always active --}}
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-success-100 text-success">
                                        Active
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-secondary-400">
                                    No POS customers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $posUsers->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

    {{-- ================= WEBSITE CUSTOMERS ================= --}}
    <div id="webTable" class="hidden">

        {{-- Filter --}}
        <form method="GET" class="bg-white border rounded-xl p-4 mb-4 flex gap-3">

            <input type="hidden" name="tab" value="web">

            <input type="text" name="web_search" value="{{ request('web_search') }}" placeholder="Search website users..."
                class="w-full h-10 px-4 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary">

            <button class="px-4 bg-primary text-white rounded-lg">Search</button>

            <a href="{{ route('admin.customers.index') }}" class="px-4 border rounded-lg flex items-center">Reset</a>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-secondary-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-secondary-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($webUsers as $customer)
                            <tr class="hover:bg-secondary-50">

                                <td class="px-6 py-4 flex items-center gap-3">
                                    <img class="w-10 h-10 rounded-full border"
                                        src="{{ $customer->image ? asset('storage/' . $customer->image) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) }}">
                                    <div>
                                        <div class="font-medium text-primary">{{ $customer->name }}</div>
                                        <div class="text-xs text-secondary-400">#{{ $customer->id }}</div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div>{{ $customer->phone }}</div>
                                    <div class="text-xs text-secondary-400">{{ $customer->email ?? 'No email' }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full
                                        {{ $customer->is_active ? 'bg-success-100 text-success' : 'bg-danger-100 text-danger' }}">
                                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-secondary-400">
                                    No website customers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $webUsers->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>

        function switchTab(type) {

            const pos = document.getElementById('posTable');
            const web = document.getElementById('webTable');

            const tabPos = document.getElementById('tab-pos');
            const tabWeb = document.getElementById('tab-web');

            // reset all tabs
            document.querySelectorAll('.tab-btn').forEach(tab => {
                tab.classList.remove('text-primary', 'border-primary');
                tab.classList.add('text-secondary-500', 'border-transparent');
            });

            if (type === 'pos') {

                pos.classList.remove('hidden');
                web.classList.add('hidden');

                tabPos.classList.add('text-primary', 'border-primary');
                tabPos.classList.remove('text-secondary-500', 'border-transparent');

            } else {

                web.classList.remove('hidden');
                pos.classList.add('hidden');

                tabWeb.classList.add('text-primary', 'border-primary');
                tabWeb.classList.remove('text-secondary-500', 'border-transparent');
            }

            // persist tab in URL
            const url = new URL(window.location);
            url.searchParams.set('tab', type);
            window.history.replaceState({}, '', url);
        }


        // load correct tab on refresh
        document.addEventListener('DOMContentLoaded', function () {

            const tab = new URLSearchParams(window.location.search).get('tab') || 'pos';
            switchTab(tab);

        });

    </script>
@endpush