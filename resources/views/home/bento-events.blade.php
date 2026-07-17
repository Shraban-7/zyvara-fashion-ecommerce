@php
    // $bentoCells comes from HomeController (BentoLayoutService::cached()).
    // Each cell: ['type' => 'event'|'view-all', 'event' => Event|null, 'size', 'span']
    $cells = $bentoCells ?? [];
    if (empty($cells)) {
        return; // auto-hide entire section when no active events
    }

    $headings = isset($section) ? $section->headings() : ['eyebrow' => 'Festival', 'title' => 'Running Events', 'subtitle' => ''];
    $title = $headings['title'];
    $eyebrow = $headings['eyebrow'];
@endphp

<section class="home-section py-12 md:py-16 bg-[var(--color-background)]" aria-label="Festival and running events">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        @if($eyebrow || $title)
        <div class="mb-7">
            @if($eyebrow)
                <span class="inline-block text-[11px] font-bold uppercase tracking-[0.14em] text-[var(--color-accent)] mb-2">{{ $eyebrow }}</span>
            @endif
            @if($title)
                <h2 class="text-2xl md:text-3xl font-semibold tracking-tight text-[var(--color-primary)] font-heading">{{ $title }}</h2>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 auto-rows-[180px] md:auto-rows-[200px] gap-3 md:gap-4">
            @foreach($cells as $cell)
                @if($cell['type'] === 'view-all')
                    <a href="{{ route('events.index') }}"
                       class="group relative flex items-center justify-center rounded-2xl border border-dashed border-[var(--color-primary)]/30 bg-[var(--color-primary)]/5 {{ $cell['span'] }} overflow-hidden transition-colors hover:bg-[var(--color-primary)]/10">
                        <span class="text-sm font-semibold text-[var(--color-primary)] flex items-center gap-2">
                            View All Offers
                            <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </a>
                    @continue
                @endif

                @php
                    $event = $cell['event'];
                    $img = $event->image ? storage_url($event->image) : null;
                @endphp

                <a href="{{ $event->link_url ?: '#' }}"
                   class="group relative block rounded-2xl overflow-hidden bg-[var(--color-secondary)]/20 border border-[var(--color-secondary)]/30 {{ $cell['span'] }} transition-shadow hover:shadow-lg"
                   aria-label="{{ $event->title }}">
                    @if($img)
                        <img src="{{ $img }}" alt="{{ $event->title }}" loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.06]">
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-primary)]/80 via-[var(--color-primary)]/25 to-transparent transition-opacity duration-300 group-hover:from-[var(--color-primary)]/90"></div>

                    @if($event->badge_text)
                        <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-full bg-[var(--color-accent)] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-[var(--color-primary)] shadow-sm">
                            {{ $event->badge_text }}
                        </span>
                    @endif

                    <div class="relative flex h-full flex-col justify-end p-4 md:p-5">
                        @if($event->subtitle)
                            <span class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[var(--color-accent)] mb-1">{{ $event->subtitle }}</span>
                        @endif
                        <h3 class="font-heading font-semibold leading-tight text-[var(--color-background)] {{ $cell['size'] === 'large' ? 'text-xl md:text-2xl' : 'text-base md:text-lg' }}">
                            {{ $event->title }}
                        </h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
