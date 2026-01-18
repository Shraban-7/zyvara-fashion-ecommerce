{{-- Showroom Section --}}
<section class="py-8 md:py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center mb-6 md:mb-8">
            <h2 class="text-xl md:text-3xl font-bold text-brand-black mb-2">Visit Our Showroom</h2>
            <p class="text-sm md:text-base text-brand-gray">Experience our collection in person</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 md:gap-8 items-center">

            {{-- Map / Image --}}
            <div class="rounded-2xl overflow-hidden shadow-lg">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&h=500&fit=crop" alt="SmartFashion Showroom" class="w-full h-48 sm:h-64 md:h-80 object-cover">
            </div>

            {{-- Showroom Details --}}
            <div class="bg-brand-light rounded-2xl p-5 md:p-8">
                <h3 class="text-lg md:text-xl font-bold text-brand-black mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-xl text-brand-blue"></i>
                    Our Location
                </h3>

                <div class="space-y-4">
                    {{-- Address --}}
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-store text-brand-blue"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-brand-black text-sm md:text-base">Main Showroom</h4>
                            <p class="text-brand-gray text-xs md:text-sm">Shop No: 245, Level 3<br>Bashundhara City Shopping Complex<br>Panthapath, Dhaka 1215</p>
                        </div>
                    </div>

                    {{-- Hours --}}
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-brand-blue"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-brand-black text-sm md:text-base">Business Hours</h4>
                            <p class="text-brand-gray text-xs md:text-sm">Saturday - Thursday: 10:00 AM - 9:00 PM<br>Friday: 3:00 PM - 9:00 PM</p>
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-brand-blue"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-brand-black text-sm md:text-base">Contact Us</h4>
                            <p class="text-brand-gray text-xs md:text-sm">+880 1712-345678<br>info@smartfashion.com.bd</p>
                        </div>
                    </div>
                </div>

                <a href="#" class="mt-6 inline-flex items-center gap-2 bg-brand-blue text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-blue-600 transition tap-effect">
                    <i class="fas fa-directions"></i>
                    Get Directions
                </a>
            </div>

        </div>
    </div>
</section>