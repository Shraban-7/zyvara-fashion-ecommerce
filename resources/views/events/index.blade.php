@extends('layouts.app')
@section('title', 'Events & Offers')

@section('content')
<div class="min-h-screen bg-light">
    <div class="max-w-7xl mx-auto px-4 py-10 md:py-14">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-primary mb-3 font-heading">Festival & Running Events</h1>
            <p class="text-secondary max-w-xl mx-auto">All live offers and seasonal sales — updated automatically as events go live and expire.</p>
        </div>

        @if($events->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    @php $img = $event->image ? storage_url($event->image) : null; @endphp
                    <a href="{{ $event->link_url ?: '#' }}"
                       class="group relative block h-72 rounded-2xl overflow-hidden bg-secondary/20 border border-secondary/30 shadow-sm transition-shadow hover:shadow-lg">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $event->title }}" loading="lazy"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.06]">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/25 to-transparent"></div>

                        @if($event->badge_text)
                            <span class="absolute top-3 left-3 inline-flex items-center rounded-full bg-accent px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-primary shadow-sm">
                                {{ $event->badge_text }}
                            </span>
                        @endif

                        <div class="relative flex h-full flex-col justify-end p-5">
                            @if($event->subtitle)
                                <span class="text-[11px] font-semibold uppercase tracking-[0.12em] text-accent mb-1">{{ $event->subtitle }}</span>
                            @endif
                            <h3 class="font-heading font-semibold text-xl leading-tight text-background">{{ $event->title }}</h3>
                            <p class="mt-1 text-xs text-background/70">
                                {{ $event->start_date?->format('M d') ?? 'Now' }}
                                <span class="opacity-60">→</span>
                                {{ $event->end_date?->format('M d') ?? 'Ongoing' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center text-secondary-400 py-16">
                <i class="fas fa-calendar-star text-4xl mb-3"></i>
                <p>No active events right now. Check back soon for the next big sale.</p>
            </div>
        @endif
    </div>
</div>
@endsection
