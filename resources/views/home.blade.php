@extends('layouts.app')

@section('title', 'SmartFashion - Premium Bangladeshi Clothing Brand')

@section('content')
{{-- Hero Slider --}}
@include('home.hero-slider-new')

{{-- Quick Category Menu --}}
@include('home.categories')

{{-- New Arrivals Section --}}
@include('home.new-arrivals')

{{-- Best Selling Section --}}
@include('home.best-selling')

{{-- Festive Collection Banner --}}
@include('home.festive-banner')

{{-- Men's Collection --}}
@include('home.mens-collection')

{{-- Ladies Collection --}}
@include('home.ladies-collection')

{{-- Why SmartFashion (Trust Section) --}}
@include('home.why-us')

{{-- Showroom Section --}}
@include('home.showroom')

{{-- Delivery & Support Info --}}
@include('home.delivery-info')

{{-- Newsletter Section --}}
@include('home.newsletter')
@endsection