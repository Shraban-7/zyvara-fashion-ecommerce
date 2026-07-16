@extends('layouts.app')
@section('title', 'Home')
@section('content')

@if(isset($homeSections) && $homeSections->isNotEmpty())
    @foreach($homeSections as $section)
        @php $viewPath = $section->viewPath(); @endphp
        @if($viewPath && view()->exists($viewPath))
            @include($viewPath, ['section' => $section])
        @endif
    @endforeach
@else
    @include('home.hero-slider-new')
    @include('home.categories')
    @include('home.new-arrivals')
    @include('home.best-selling')
    @include('home.on-sale')
    @include('home.featured')
    @include('home.testimonials')
    @include('home.festive-banner')
    @include('home.mens-collection')
    @include('home.ladies-collection')
    @include('home.our-brands')
    @include('home.why-us')
    @include('home.showroom')
    @include('home.newsletter')
    @include('home.social-feed')
@endif
@endsection
