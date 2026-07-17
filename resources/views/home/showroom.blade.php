{{--
    Visit Our Showrooms — dynamic section.
    Pulls active stores from $stores (already cached + ordered in HomeController).
    Gracefully hides the whole section when there are no active stores.
--}}
@php
    $stores = $stores ?? \App\Models\Store::where('is_active', true)->ordered()->get();
@endphp

@if($stores->isNotEmpty())
    <section class="py-8 md:py-12 bg-surface-elevated">
        <div class="max-w-7xl mx-auto px-4">
            {{-- Section Header --}}
            <div class="text-center mb-6 md:mb-8">
                <h2 class="text-xl md:text-3xl font-bold text-primary mb-2">
                    {{ $section->headings()['title'] ?? 'Visit Our Showrooms' }}
                </h2>
                <p class="text-sm md:text-base text-secondary">
                    {{ $section->headings()['subtitle'] ?? 'Experience our collections in person' }}
                </p>
            </div>

            @if($stores->count() <= 2)
                {{-- Simple centered layout for 1-2 stores --}}
                <div class="flex flex-col items-center gap-6 max-w-2xl mx-auto">
                    @foreach($stores as $store)
                        @include('home._store-card', ['store' => $store])
                    @endforeach
                </div>
            @else
                {{-- Horizontal scroll carousel for 3+ stores --}}
                <div class="flex gap-5 overflow-x-auto pb-4 snap-x snap-mandatory -mx-4 px-4">
                    @foreach($stores as $store)
                        <div class="snap-start shrink-0 w-72 sm:w-80">
                            @include('home._store-card', ['store' => $store])
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="text-center mt-8">
                <a href="{{ route('stores.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-primary text-primary font-medium hover:bg-primary hover:text-white transition">
                    <i class="fas fa-map-marked-alt"></i> View All Stores
                </a>
            </div>
        </div>
    </section>
@endif
