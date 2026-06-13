{{-- Showroom Section --}}
<section class="py-8 md:py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center mb-6 md:mb-8">
            <h2 class="text-xl md:text-3xl font-bold text-primary mb-2">Visit Our Showroom</h2>
            <p class="text-sm md:text-base text-secondary">Experience our collection in person</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 md:gap-8 items-stretch">

            {{-- Map / Image --}}
            <div class="rounded-2xl overflow-hidden shadow-lg shadow-primary/5 h-full min-h-[300px] md:min-h-[400px] showroom-map border border-primary-100">
                @if(!empty($settings['google_maps_embed']))
                    <div class="w-full h-full">
                        {!! $settings['google_maps_embed'] !!}
                    </div>
                @else
                    {{-- Fallback to embedded Google Map with default location --}}
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.0977878278893!2d90.4124!3d23.7808!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDQ2JzUxLjAiTiA5MMKwMjQnNDQuNiJF!5e0!3m2!1sen!2sbd!4v1234567890"
                        class="w-full h-full border-0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                @endif
            </div>

            {{-- Showroom Details --}}
            <div class="bg-light rounded-2xl p-5 md:p-8 flex flex-col justify-center border border-primary-100">
                <h3 class="text-lg md:text-xl font-bold text-primary mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-xl text-primary"></i>
                    Our Location
                </h3>

                <div class="space-y-4">
                    {{-- Address --}}
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                            <i class="fas fa-store text-primary text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary text-sm md:text-base">Address</h4>
                            <p class="text-secondary text-xs md:text-sm mt-0.5">{{ $settings['contact_address'] ?? '' }}</p>
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-primary-100">
                            <i class="fas fa-phone text-primary text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary text-sm md:text-base">Contact Us</h4>
                            <p class="text-secondary text-xs md:text-sm mt-0.5">
                                {{ $settings['contact_phone'] ?? '' }}<br>{{ $settings['contact_email'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    /* Ensure map iframes fill the container properly */
    .showroom-map iframe {
        width: 100%;
        height: 100%;
        min-height: 300px;
        border: 0;
        filter: grayscale(0.15) contrast(1.05);
    }

    @media (min-width: 768px) {
        .showroom-map iframe {
            min-height: 400px;
        }
    }
</style>