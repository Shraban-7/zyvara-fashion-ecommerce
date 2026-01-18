{{-- Newsletter Section --}}
<section class="py-8 md:py-12 bg-gradient-to-r from-brand-blue to-blue-600">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-xl md:text-2xl font-bold text-white mb-2">Subscribe for Exclusive Offers</h2>
        <p class="text-blue-100 text-sm md:text-base mb-6">Get 10% off on your first order!</p>

        <form class="max-w-md mx-auto flex gap-2">
            @csrf
            <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-white">
            <button type="submit" class="bg-brand-black text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-800 transition tap-effect">Subscribe</button>
        </form>
    </div>
</section>