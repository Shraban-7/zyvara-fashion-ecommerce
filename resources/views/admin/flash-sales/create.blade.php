@extends('admin.layouts.app')
@section('title', 'New Flash Sale')

@section('content')
<div>
    <div class="mb-6">
        <a href="{{ route('admin.flash-sales.index') }}" class="text-sm text-secondary-500 hover:text-secondary-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to Flash Sales
        </a>
        <h1 class="text-2xl font-bold text-secondary-800 mt-2">New Flash Sale</h1>
    </div>

    <form action="{{ route('admin.flash-sales.store') }}" method="POST">
        @csrf
        @include('admin.flash-sales._form')
    </form>
</div>
@endsection
