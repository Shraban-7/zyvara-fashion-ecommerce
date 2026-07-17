@extends('admin.layouts.app')

@section('title', 'Returns & Exchanges')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Returns & Exchanges</h1>
            <p class="text-sm text-secondary-500 mt-1">Review and process customer return & exchange requests</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-success-50 border border-success-200 px-4 py-3 text-sm text-success-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl bg-danger-50 border border-danger-200 px-4 py-3 text-sm text-danger-700">{{ session('error') }}</div>
    @endif

    <!-- Filter tabs -->
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.returns.index') }}"
            class="rounded-lg px-3.5 py-2 text-sm font-medium transition {{ !$status ? 'bg-primary text-white' : 'bg-white border border-secondary-200 text-secondary-600 hover:bg-secondary-50' }}">
            All <span class="ml-1 opacity-70">{{ $requests->total() }}</span>
        </a>
        @foreach(\App\Enums\ReturnStatus::cases() as $s)
            @php $count = $statusCounts->get($s->value, 0); @endphp
            <a href="{{ route('admin.returns.index', ['status' => $s->value]) }}"
                class="rounded-lg px-3.5 py-2 text-sm font-medium transition {{ $status && $status->value === $s->value ? 'bg-primary text-white' : 'bg-white border border-secondary-200 text-secondary-600 hover:bg-secondary-50' }}">
                {{ $s->label() }} <span class="ml-1 opacity-70">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-secondary-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-secondary-50 text-secondary-500 text-left">
                    <tr>
                        <th class="px-5 py-3 font-semibold">#</th>
                        <th class="px-5 py-3 font-semibold">Customer</th>
                        <th class="px-5 py-3 font-semibold">Order</th>
                        <th class="px-5 py-3 font-semibold">Type</th>
                        <th class="px-5 py-3 font-semibold">Reason</th>
                        <th class="px-5 py-3 font-semibold">Requested</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-100">
                    @forelse($requests as $req)
                        @php $item = $req->orderItem; @endphp
                        <tr class="hover:bg-secondary-50/60 transition">
                            <td class="px-5 py-3 font-medium text-secondary-800">#{{ $req->id }}</td>
                            <td class="px-5 py-3 text-secondary-700">{{ $req->user->name ?? 'Guest' }}</td>
                            <td class="px-5 py-3 text-secondary-700">{{ $req->order->order_number ?? 'N/A' }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $req->isExchange ? 'bg-accent-50 text-accent-700' : 'bg-secondary-100 text-secondary-700' }}">
                                    {{ $req->type->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-secondary-600">{{ $req->reason->label() }}</td>
                            <td class="px-5 py-3 text-secondary-500">{{ $req->requested_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $req->status->colorClass() }}">
                                    {{ $req->status->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.returns.show', $req->id) }}"
                                    class="inline-flex items-center gap-1 rounded-lg bg-primary px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-primary-700">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-secondary-400">No requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-secondary-100">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
