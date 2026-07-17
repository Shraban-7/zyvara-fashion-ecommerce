@extends('admin.layouts.app')
@section('title', 'Coupon Details')

@section('content')
<div>
    <div class="mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-sm text-secondary-500 hover:text-primary transition mb-2 inline-flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Back to Coupons
        </a>
        <div class="flex items-center gap-3 flex-wrap">
            <h1 class="text-2xl font-bold text-secondary-800 font-mono">{{ $coupon->code }}</h1>
            @php
                $badge = match($coupon->status) {
                    'active' => ['label' => 'Active', 'class' => 'bg-success-50 text-success border-success-100'],
                    'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-warning-50 text-warning border-warning-100'],
                    'expired' => ['label' => 'Expired', 'class' => 'bg-secondary-100 text-secondary-500 border-secondary-200'],
                    'inactive' => ['label' => 'Inactive', 'class' => 'bg-danger-50 text-danger border-danger-100'],
                };
            @endphp
            <span class="px-2 py-1 text-xs font-semibold rounded border {{ $badge['class'] }}">{{ $badge['label'] }}</span>
        </div>
        <p class="text-sm text-secondary-600">{{ $coupon->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-secondary-500">Discount</p>
            <p class="text-xl font-bold text-primary mt-1">{{ $coupon->type->label() }} &middot; {{ $coupon->formatted_value }}</p>
            @if($coupon->maximum_discount)
            <p class="text-xs text-secondary-400 mt-1">Capped at {{ money($coupon->maximum_discount) }}</p>
            @endif
        </div>
        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-secondary-500">Usage</p>
            <p class="text-xl font-bold text-primary mt-1">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</p>
            <p class="text-xs text-secondary-400 mt-1">{{ $coupon->usage_limit_per_user }} per user</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-secondary-500">Total Discount Given</p>
            <p class="text-xl font-bold text-accent mt-1">{{ money($coupon->usages->sum('discount_amount')) }}</p>
            <p class="text-xs text-secondary-400 mt-1">
                {{ $coupon->starts_at ? $coupon->starts_at->format('M d, Y') : '—' }}
                &rarr; {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'No end' }}
            </p>
        </div>
    </div>

    @if($coupon->isRestricted())
    <div class="bg-white p-5 rounded-xl border border-secondary-200 shadow-sm mb-6">
        <h3 class="text-sm font-bold text-secondary-800 uppercase tracking-wide mb-3">Applies To</h3>
        <div class="flex flex-wrap gap-2">
            @if($coupon->hasCategoryRestrictions())
                @foreach($coupon->applicable_categories as $catId)
                    @php $cat = $categories->firstWhere('id', $catId); @endphp
                    @if($cat)
                    <span class="px-2.5 py-1 text-xs rounded-full bg-primary-50 text-primary border border-primary-100">{{ $cat->name }}</span>
                    @endif
                @endforeach
            @endif
            @if($coupon->hasProductRestrictions())
                @foreach($coupon->applicable_products as $prodId)
                    @php $prod = $products->firstWhere('id', $prodId); @endphp
                    @if($prod)
                    <span class="px-2.5 py-1 text-xs rounded-full bg-accent-50 text-accent border border-accent-100">{{ $prod->name }}</span>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-secondary-200">
        <div class="px-6 py-4 border-b border-secondary-200">
            <h3 class="text-sm font-bold text-secondary-800 uppercase tracking-wide">Redemptions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-secondary-500">
                <thead class="text-xs text-secondary-700 uppercase bg-secondary-50 border-b">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Discount</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($usages as $usage)
                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-6 py-4">{{ $usage->user?->name ?? 'Guest' }}</td>
                        <td class="px-6 py-4">
                            @if($usage->order)
                            <a href="{{ route('admin.orders.show', $usage->order->order_number) }}" class="text-primary hover:underline">{{ $usage->order->order_number }}</a>
                            @else
                            <span class="text-secondary-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-accent">{{ money($usage->discount_amount) }}</td>
                        <td class="px-6 py-4 text-xs text-secondary-500">{{ $usage->used_at ? $usage->used_at->format('M d, Y H:i') : '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-secondary-400 italic">No redemptions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usages->hasPages())
        <div class="px-6 py-4 border-t border-secondary-200">
            {{ $usages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
