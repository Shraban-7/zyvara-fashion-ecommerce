@extends('layouts.app')
@section('title', 'Store Locator')

@section('content')
<div class="min-h-screen bg-light">
    <div class="max-w-7xl mx-auto px-4 py-10 md:py-14">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-primary mb-3">Our Stores & Showrooms</h1>
            <p class="text-secondary max-w-xl mx-auto">Find a location near you. Visit us in person to experience the collection, get styled, or exchange your order.</p>
        </div>

        @if($stores->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($stores as $store)
                    @include('home._store-card', ['store' => $store])
                @endforeach
            </div>
        @else
            <div class="text-center text-secondary-400 py-16">
                <i class="fas fa-map-marker-alt text-4xl mb-3"></i>
                <p>No store locations are available right now.</p>
            </div>
        @endif

        <p class="text-center text-xs text-secondary-400 mt-12">
            Want a map with all pins? An embedded Google Map can be added once a Maps API key is configured (follow-up).
        </p>
    </div>
</div>
@endsection
