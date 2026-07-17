@extends('admin.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
<div>
    <div class="mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-sm text-secondary-500 hover:text-primary transition mb-2 inline-flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Back to Coupons
        </a>
        <h1 class="text-2xl font-bold text-secondary-800">Edit Coupon</h1>
        <p class="text-sm text-secondary-600">Update discount code <span class="font-mono text-primary">{{ $coupon->code }}</span>.</p>
    </div>

    @include('admin.coupons._form')
</div>
@endsection
