@extends('admin.layouts.app')
@section('title', 'Create Coupon')

@section('content')
<div>
    <div class="mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-sm text-secondary-500 hover:text-primary transition mb-2 inline-flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Back to Coupons
        </a>
        <h1 class="text-2xl font-bold text-secondary-800">New Coupon</h1>
        <p class="text-sm text-secondary-600">Define a discount code and its rules.</p>
    </div>

    @include('admin.coupons._form')
</div>
@endsection
