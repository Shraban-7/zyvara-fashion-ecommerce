<?php
$leftPages = \App\Models\StaticPage::active()->where('footer_position', 1)->orderBy('sort_order')->pluck('title', 'slug');
$rightPages = \App\Models\StaticPage::active()->where('footer_position', 2)->orderBy('sort_order')->pluck('title', 'slug');
?>
<footer class="bg-gray-900 text-white pt-10 pb-24 md:pb-10">
    <div class="max-w-7xl mx-auto px-4">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">

            {{-- Logo & About --}}
            <div class="col-span-2 md:col-span-1">
                <a href="{{ url('/') }}" class="flex items-center mb-4">
                    <span class="text-xl font-bold text-white">Spinner</span>
                    <span class="text-xl font-bold text-primary">Fashion</span>
                </a>
                <p class="text-gray-400 text-sm mb-4">Premium Bangladeshi clothing brand offering quality fashion at
                    affordable prices. Your trusted partner for modern style.</p>

                {{-- Social Links --}}
                <div class="flex gap-3">
                    @if($settings['facebook_url'] ?? false)
                    <a href="{{ $settings['facebook_url'] }}" target="_blank"
                        class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if($settings['instagram_url'] ?? false)
                    <a href="{{ $settings['instagram_url'] }}" target="_blank"
                        class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                    @if($settings['tiktok_url'] ?? false)
                    <a href="{{ $settings['tiktok_url'] }}" target="_blank"
                        class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="font-semibold text-base mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    @foreach ($leftPages as $leftPageslug => $leftPagetitle)
                    <li>
                        <a href="{{ route('static_page.show', $leftPageslug) }}"
                            class="text-gray-400 text-sm hover:text-primary transition">{{ $leftPagetitle }}
                        </a>
                    </li>
                    @endforeach
                    <li><a href="{{ route('track-order.index') }}" class="text-gray-400 text-sm hover:text-primary transition">Track Order</a></li>
                </ul>
            </div>

            {{-- Categories --}}
            <div>
                <h4 class="font-semibold text-base mb-4">Categories</h4>
                <ul class="space-y-2">
                    @foreach ($allMenuCategories->take(5) as $category)
                    <li><a href="{{ route('products.index') }}?category={{ $category->slug }}"
                            class="text-gray-400 text-sm hover:text-primary transition">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Policies --}}
            <div>
                <h4 class="font-semibold text-base mb-4">Policies</h4>
                <ul class="space-y-2">
                    @foreach ($rightPages as $rightPageslug => $rightPagetitle)
                    <li>
                        <a href="{{ route('static_page.show', $rightPageslug) }}"
                            class="text-gray-400 text-sm hover:text-primary transition">{{ $rightPagetitle }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>

        {{-- Payment Methods --}}
        <div class="border-t border-gray-800 pt-6 mb-6">
            <div class="flex flex-wrap items-center justify-center gap-4">
                <span class="text-gray-500 text-sm">We Accept:</span>
                <div class="flex items-center gap-3">
                    <div class="bg-white rounded px-3 py-1.5">
                        <span class="text-xs font-bold text-green-600">bKash</span>
                    </div>
                    <div class="bg-white rounded px-3 py-1.5">
                        <span class="text-xs font-bold text-orange-500">Nagad</span>
                    </div>
                    <div class="bg-white rounded px-3 py-1.5">
                        <span class="text-xs font-bold text-blue-600">VISA</span>
                    </div>
                    <div class="bg-white rounded px-3 py-1.5">
                        <span class="text-xs font-bold text-red-500">Master</span>
                    </div>
                    <div class="bg-white rounded px-3 py-1.5">
                        <span class="text-xs font-bold text-gray-700">COD</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-gray-800 pt-6 text-center">
            <p class="text-gray-500 text-sm">© {{ date('Y') }} {{ $siteName }}. All Rights Reserved.</p>
            <p class="text-gray-600 text-xs mt-1">Designed & Developed by <a href="https://spinnertech.dev"
                    target="_blank">Spinner Tech</a></p>
        </div>

    </div>
</footer>