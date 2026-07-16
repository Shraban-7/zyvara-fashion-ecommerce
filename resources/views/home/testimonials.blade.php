@php
    $testimonials = \App\Models\Testimonial::active()->ordered()->take(isset($section) ? (int) $section->item_limit : 6)->get();
@endphp

@if($testimonials->isNotEmpty())
<section class="bg-bg py-16 md:py-24">
    <div class="mx-auto w-full max-w-[1320px] px-5 sm:px-8">
        <div class="mb-12 text-center md:mb-16">
            <p class="mb-3 font-body text-xs font-medium uppercase tracking-[0.25em] text-accent">
                Loved By Thousands
            </p>
            <h2 class="font-heading text-3xl font-semibold text-primary sm:text-4xl md:text-5xl">
                {{ isset($section) && $section->title ? $section->title : 'What Our Customers Say' }}
            </h2>
            <p class="mx-auto mt-4 max-w-md font-body text-sm text-secondary sm:text-base">
                Real words from real wardrobes — the pieces our community reaches for again and again.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($testimonials as $review)
                <figure class="flex h-full flex-col rounded-2xl border border-surface-muted bg-surface-elevated p-7 shadow-sm transition-shadow duration-300 hover:shadow-lg">
                    <div class="mb-4 flex items-center gap-1 text-accent" aria-label="{{ $review->rating }} out of 5 stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-sm"></i>
                        @endfor
                    </div>

                    <blockquote class="flex-1">
                        <p class="font-body text-[15px] leading-relaxed text-primary/85">
                            "{{ $review->quote }}"
                        </p>
                    </blockquote>

                    <figcaption class="mt-6 flex items-center gap-3">
                        @if($review->avatar)
                            <img src="{{ storage_url($review->avatar) }}" alt="{{ $review->name }}" class="h-11 w-11 rounded-full object-cover bg-surface-muted">
                        @else
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary font-body text-sm font-semibold text-surface-elevated">
                                {{ strtoupper(substr($review->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-body text-sm font-semibold text-primary">{{ $review->name }}</p>
                            <p class="font-body text-xs text-secondary">{{ $review->location }}</p>
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>

        {{-- Submit your own testimonial --}}
        <div class="mx-auto mt-14 max-w-2xl">
            <div class="rounded-2xl border border-surface-muted bg-surface-elevated p-8 shadow-sm">
                <h3 class="font-heading text-xl font-semibold text-primary">Share your experience</h3>
                <p class="mt-1 font-body text-sm text-secondary">Loved something? Tell us about it — your review may appear here after a quick check.</p>

                @if(session('testimonial_success'))
                    <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 font-body text-sm text-green-700">
                        {{ session('testimonial_success') }}
                    </div>
                @else
                    <form action="{{ route('testimonials.store') }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block font-body text-xs font-medium text-secondary">Your name *</label>
                                <input type="text" name="name" value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}"
                                    class="w-full rounded-lg border border-surface-muted bg-surface px-4 py-2.5 font-body text-sm text-primary focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="e.g. Ayesha Rahman" required>
                            </div>
                            <div>
                                <label class="mb-1 block font-body text-xs font-medium text-secondary">Location</label>
                                <input type="text" name="location" value="{{ old('location') }}"
                                    class="w-full rounded-lg border border-surface-muted bg-surface px-4 py-2.5 font-body text-sm text-primary focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="e.g. Dhaka">
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block font-body text-xs font-medium text-secondary">Rating *</label>
                            <select name="rating"
                                class="w-full rounded-lg border border-surface-muted bg-surface px-4 py-2.5 font-body text-sm text-primary focus:border-accent focus:ring-1 focus:ring-accent">
                                @for($i=5;$i>=1;$i--)<option value="{{ $i }}" {{ old('rating', 5)==$i?'selected':'' }}>{{ $i }} Star{{ $i>1?'s':'' }}</option>@endfor
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block font-body text-xs font-medium text-secondary">Your review *</label>
                            <textarea name="quote" rows="3" required
                                class="w-full rounded-lg border border-surface-muted bg-surface px-4 py-2.5 font-body text-sm text-primary focus:border-accent focus:ring-1 focus:ring-accent"
                                placeholder="Tell us what you loved…">{{ old('quote') }}</textarea>
                        </div>

                        @error('quote')
                            <p class="font-body text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 font-body text-sm font-semibold text-surface-elevated transition-colors hover:bg-accent">
                            Submit Review
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
