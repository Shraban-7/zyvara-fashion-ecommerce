@extends('admin.layouts.app')
@section('title', 'Coupons')

@section('content')
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-secondary-800">Coupons</h1>
            <p class="text-sm text-secondary-600">Create and manage discount codes for your store.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}"
            class="px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> New Coupon
        </a>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-secondary-500">Total Active Coupons</p>
                    <p class="text-2xl font-bold text-primary mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-success-50 flex items-center justify-center">
                    <i data-lucide="ticket" class="w-5 h-5 text-success"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-secondary-500">Total Discount Given</p>
                    <p class="text-2xl font-bold text-accent mt-1">{{ money($stats['total_discount']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-accent-50 flex items-center justify-center">
                    <i data-lucide="tag" class="w-5 h-5 text-accent"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wide text-secondary-500">Most Used Coupon</p>
                    <p class="text-2xl font-bold text-primary mt-1 truncate">
                        {{ $stats['most_used'] ? $stats['most_used']->code : '—' }}
                    </p>
                    @if($stats['most_used'])
                    <p class="text-xs text-secondary-400">{{ $stats['most_used']->usages_count }} redemptions</p>
                    @endif
                </div>
                <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                    <i data-lucide="trophy" class="w-5 h-5 text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-secondary-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-secondary-500">
                <thead class="text-xs text-secondary-700 uppercase bg-secondary-50 border-b">
                    <tr>
                        <th class="px-6 py-4">Code</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Usage</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Start &ndash; End</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-primary font-mono">{{ $coupon->code }}</div>
                            <div class="text-xs text-secondary-500 line-clamp-1">{{ $coupon->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-secondary-700">{{ $coupon->type->label() }}</span>
                            <div class="text-xs text-accent font-semibold">{{ $coupon->formatted_value }}</div>
                        </td>
                        <td class="px-6 py-4 text-secondary-600 text-xs">
                            {{ $coupon->used_count }}
                            @if($coupon->usage_limit)
                                / {{ $coupon->usage_limit }}
                            @else
                                / &infin;
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badge = match($coupon->status) {
                                    'active' => ['label' => 'Active', 'class' => 'bg-success-50 text-success border-success-100'],
                                    'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-warning-50 text-warning border-warning-100'],
                                    'expired' => ['label' => 'Expired', 'class' => 'bg-secondary-100 text-secondary-500 border-secondary-200'],
                                    'inactive' => ['label' => 'Inactive', 'class' => 'bg-danger-50 text-danger border-danger-100'],
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded border {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-secondary-600">
                            <div>{{ $coupon->starts_at ? $coupon->starts_at->format('M d, Y') : '—' }}</div>
                            <div class="text-secondary-400">&rarr; {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'No end' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-1">
                                <a href="{{ route('admin.coupons.show', $coupon->id) }}"
                                    class="w-8 h-8 flex items-center justify-center text-secondary-500 hover:bg-secondary-100 rounded-md transition-all" title="View">
                                    <i class="fa-solid fa-eye text-base"></i>
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                    class="w-8 h-8 flex items-center justify-center text-primary hover:bg-accent-50 rounded-md transition-all" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                </a>
                                <form action="{{ route('admin.coupons.duplicate', $coupon->id) }}" method="POST" onsubmit="return confirm('Duplicate this coupon?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center text-secondary-500 hover:bg-secondary-100 rounded-md transition-all" title="Duplicate">
                                        <i class="fa-solid fa-copy text-base"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.coupons.delete', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center text-danger hover:bg-danger-50 rounded-md transition-all" title="Delete">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-secondary-400 italic">
                            No coupons yet. Click "New Coupon" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
        <div class="px-6 py-4 border-t border-secondary-200">
            {{ $coupons->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
