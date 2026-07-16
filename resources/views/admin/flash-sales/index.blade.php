@extends('admin.layouts.app')
@section('title', 'Flash Sales')

@section('content')
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Flash Sales</h1>
            <p class="text-sm text-gray-600">Create time-limited sales with their own product sets and countdown timers.</p>
        </div>
        <a href="{{ route('admin.flash-sales.create') }}"
            class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> New Flash Sale
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4">Sale</th>
                        <th class="px-6 py-4">Window</th>
                        <th class="px-6 py-4">Products</th>
                        <th class="px-6 py-4">State</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($flashSales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $sale->title }}</div>
                            <div class="text-xs text-gray-500 line-clamp-1">{{ $sale->subtitle ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">
                            <div>{{ $sale->starts_at->format('M d, Y H:i') }}</div>
                            <div class="text-gray-400">→ {{ $sale->ends_at->format('M d, Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $sale->products_count }}</td>
                        <td class="px-6 py-4">
                            @if($sale->is_expired)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-500 border border-gray-200">Expired</span>
                            @elseif($sale->is_upcoming)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-amber-50 text-amber-700 border border-amber-100">Upcoming</span>
                            @elseif($sale->is_running)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-50 text-green-700 border border-green-100">Running</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-500 border border-gray-200">Paused</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button type="button"
                                onclick="toggleStatus({{ $sale->id }}, this)"
                                class="toggle-btn inline-flex items-center text-xs font-medium {{ $sale->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                <span class="h-1.5 w-1.5 rounded-full mr-1.5 {{ $sale->is_active ? 'bg-green-600' : 'bg-gray-400' }}"></span>
                                <span class="toggle-label">{{ $sale->is_active ? 'Active' : 'Inactive' }}</span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-1">
                                <a href="{{ route('admin.flash-sales.edit', $sale->id) }}"
                                    class="w-8 h-8 flex items-center justify-center text-indigo-600 hover:bg-indigo-50 rounded-md transition-all"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                </a>
                                <form action="{{ route('admin.flash-sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Delete this flash sale?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-md transition-all"
                                        title="Delete">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                            No flash sales yet. Click "New Flash Sale" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleStatus(id, btn) {
        fetch('{{ url("admin/flash-sales") }}/' + id + '/toggle-status', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const dot = btn.querySelector('span');
            const label = btn.querySelector('.toggle-label');
            if (data.is_active) {
                btn.classList.remove('text-gray-400'); btn.classList.add('text-green-600');
                dot.classList.remove('bg-gray-400'); dot.classList.add('bg-green-600');
                label.textContent = 'Active';
            } else {
                btn.classList.remove('text-green-600'); btn.classList.add('text-gray-400');
                dot.classList.remove('bg-green-600'); dot.classList.add('bg-gray-400');
                label.textContent = 'Inactive';
            }
        });
    }
</script>
@endpush
@endsection
