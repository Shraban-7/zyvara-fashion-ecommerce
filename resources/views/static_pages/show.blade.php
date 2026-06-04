@extends('layouts.app')
@section('title', $page->title)

@push('header')

@if($page->meta_title)
    <meta name="title" content="{{ $page->meta_title }}">    
@endif

@if($page->meta_description)
    <meta name="description" content="{{ $page->meta_description }}">    
@endif

@if($page->meta_keywords)
    <meta name="keywords" content="{{ $page->meta_keywords }}">    
@endif

@section('content')

<div class="min-h-screen max-w-7xl mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl text-center font-bold text-gray-900 mb-3">{{ $page->title }}</h1>
        <p class="text-lg text-gray-600 mb-2">{!! nl2br(e($page->content)) !!}</p>
    </div>
</div>

@endsection