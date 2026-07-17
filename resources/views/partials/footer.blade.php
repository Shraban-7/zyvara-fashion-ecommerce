<?php
$leftPages = \App\Models\StaticPage::active()->where('footer_position', 1)->orderBy('sort_order')->pluck('title', 'slug');
$rightPages = \App\Models\StaticPage::active()->where('footer_position', 2)->orderBy('sort_order')->pluck('title', 'slug');
?>
<footer id="mainFooter" class="bg-primary text-surface-elevated pt-16 pb-24 md:pb-10 relative overflow-hidden">
    {{-- Subtle top accent line --}}
    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-secondary-400 to-transparent opacity-40"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12 mb-12">

            {{-- Logo & About --}}
            <div class="col-span-2 md:col-span-1">
                <a href="{{ url('/') }}" class="flex items-center mb-5 group">
                    <span class="text-2xl font-bold text-surface-elevated tracking-tight">{{ $siteName }}</span>
                </a>
                <p class="text-secondary-300 text-sm leading-relaxed mb-6 max-w-xs">
                    {{ $settings['site_tagline'] ?? 'Your trusted partner for modern style. Premium fashion at affordable prices.' }}
                </p>

                {{-- Social Links --}}
                <div class="flex gap-3">
                    @if($settings['facebook_url'] ?? false)
                    <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-surface-elevated/5 border border-surface-elevated/10 rounded-full flex items-center justify-center hover:bg-secondary hover:border-secondary transition-all duration-300 hover:-translate-y-0.5">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    @endif
                    @if($settings['instagram_url'] ?? false)
                    <a href="{{ $settings['instagram_url'] }}" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-surface-elevated/5 border border-surface-elevated/10 rounded-full flex items-center justify-center hover:bg-secondary hover:border-secondary transition-all duration-300 hover:-translate-y-0.5">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    @endif
                    @if($settings['tiktok_url'] ?? false)
                    <a href="{{ $settings['tiktok_url'] }}" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-surface-elevated/5 border border-surface-elevated/10 rounded-full flex items-center justify-center hover:bg-secondary hover:border-secondary transition-all duration-300 hover:-translate-y-0.5">
                        <i class="fab fa-tiktok text-sm"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="font-semibold text-sm uppercase tracking-wider text-surface-elevated mb-5 border-l-2 border-secondary-400 pl-3">Quick Links</h4>
                <ul class="space-y-3">
                    @foreach ($leftPages as $leftPageslug => $leftPagetitle)
                    <li>
                        <a href="{{ route('static_page.show', $leftPageslug) }}"
                            class="text-secondary-300 text-sm hover:text-surface-elevated transition-colors duration-200 flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-secondary-600 group-hover:bg-secondary-400 transition-colors"></span>
                            {{ $leftPagetitle }}
                        </a>
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ route('track-order.index') }}" class="text-secondary-300 text-sm hover:text-surface-elevated transition-colors duration-200 flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-secondary-600 group-hover:bg-secondary-400 transition-colors"></span>
                            Track Order
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Categories --}}
            <div>
                <h4 class="font-semibold text-sm uppercase tracking-wider text-surface-elevated mb-5 border-l-2 border-secondary-400 pl-3">Categories</h4>
                <ul class="space-y-3">
                    @foreach ($allMenuCategories->take(5) as $category)
                    <li>
                        <a href="{{ route('products.index') }}?category={{ $category->slug }}"
                            class="text-secondary-300 text-sm hover:text-surface-elevated transition-colors duration-200 flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-secondary-600 group-hover:bg-secondary-400 transition-colors"></span>
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Policies --}}
            <div>
                <h4 class="font-semibold text-sm uppercase tracking-wider text-surface-elevated mb-5 border-l-2 border-secondary-400 pl-3">Policies</h4>
                <ul class="space-y-3">
                    @foreach ($rightPages as $rightPageslug => $rightPagetitle)
                    <li>
                        <a href="{{ route('static_page.show', $rightPageslug) }}"
                            class="text-secondary-300 text-sm hover:text-surface-elevated transition-colors duration-200 flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-secondary-600 group-hover:bg-secondary-400 transition-colors"></span>
                            {{ $rightPagetitle }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>

        {{-- Payment Methods --}}
        <div class="border-t border-surface-elevated/10 pt-8 mb-8">
            <div class="flex flex-wrap items-center justify-center">
                <img src="{{ asset('assets/sslcommerz.png') }}" alt="SSLCommerz" class="w-20 h-auto object-contain">
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-surface-elevated/10 pt-6 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-secondary-400 text-sm">© {{ date('Y') }} {{ $siteName }}. All Rights Reserved.</p>
            <p class="text-secondary-600 text-xs">Designed & Developed by <a href="https://spinnertech.dev" target="_blank" rel="noopener noreferrer" class="text-secondary-400 hover:text-surface-elevated transition-colors">Spinner Tech</a></p>
        </div>

    </div>

    {{-- Background decoration --}}
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-20 left-0 w-64 h-64 bg-secondary/3 rounded-full blur-3xl pointer-events-none"></div>
</footer>