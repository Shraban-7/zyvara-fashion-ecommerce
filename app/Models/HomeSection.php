<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HomeSection extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_visible' => 'boolean',
        'display_order' => 'integer',
        'item_limit' => 'integer',
    ];

    /**
     * Section keys that map to a blade partial under resources/views/home/.
     * Admin can toggle/reorder these; the homepage renders them in order.
     */
    public const AVAILABLE_SECTIONS = [
        'hero' => 'home.hero-slider-new',
        'categories' => 'home.categories',
        'flash_sale' => 'home.flash-sale',
        'new_arrivals' => 'home.new-arrivals',
        'trending' => 'home.trending',
        'best_selling' => 'home.best-selling',
        'on_sale' => 'home.on-sale',
        'featured' => 'home.featured',
        'bento_events' => 'home.bento-events',
        'testimonials' => 'home.testimonials',
        'festive_banner' => 'home.festive-banner',
        'mens_collection' => 'home.mens-collection',
        'ladies_collection' => 'home.ladies-collection',
        'our_brands' => 'home.our-brands',
        'why_us' => 'home.why-us',
        'showroom' => 'home.showroom',
        'newsletter' => 'home.newsletter',
        'social_feed' => 'home.social-feed',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    public function viewPath(): ?string
    {
        return self::AVAILABLE_SECTIONS[$this->section_key] ?? null;
    }

    /**
     * Returns [eyebrow, title, subtitle] for rendering, falling back to
     * per-section hardcoded defaults when the admin values are empty.
     */
    public function headings(): array
    {
        $defaults = self::DEFAULT_HEADINGS[$this->section_key] ?? [
            'eyebrow' => '',
            'title' => str_replace('_', ' ', ucwords($this->section_key)),
            'subtitle' => '',
        ];

        return [
            'eyebrow' => $this->eyebrow ?? $defaults['eyebrow'],
            'title' => $this->title ?? $defaults['title'],
            'subtitle' => $this->subtitle ?? $defaults['subtitle'],
        ];
    }

    public const DEFAULT_HEADINGS = [
        'new_arrivals' => ['eyebrow' => 'Just Dropped', 'title' => 'New Arrivals', 'subtitle' => 'Fresh styles curated for the season'],
        'trending' => ['eyebrow' => 'Hot Right Now', 'title' => 'Trending Now', 'subtitle' => 'What everyone is adding to cart this week'],
        'best_selling' => ['eyebrow' => 'Trending Now', 'title' => 'Best Selling', 'subtitle' => 'Top picks loved by thousands of customers'],
        'on_sale' => ['eyebrow' => 'Limited Time', 'title' => 'On Sale', 'subtitle' => 'Flash deals — grab them before they are gone'],
        'featured' => ['eyebrow' => "Editor's Pick", 'title' => 'Featured Products', 'subtitle' => 'Handpicked highlights of the season'],
        'mens_collection' => ['eyebrow' => 'Curated For You', 'title' => "Men's Collection", 'subtitle' => 'Stylish picks for the modern man — from casual to formal'],
        'ladies_collection' => ['eyebrow' => 'Elegant Picks', 'title' => "Ladies' Collection", 'subtitle' => 'Elegant styles for every woman — from casual to formal'],
        'our_brands' => ['eyebrow' => 'Trusted Partners', 'title' => 'Our Brands', 'subtitle' => 'Premium labels, exceptional quality'],
        'categories' => ['eyebrow' => 'Collections', 'title' => 'Shop by Category', 'subtitle' => 'Explore our curated edit — crafted for everyday ease and quiet luxury.'],
        'newsletter' => ['eyebrow' => '', 'title' => 'Subscribe for Exclusive Offers', 'subtitle' => 'Stay updated with our latest collections and offers.'],
        'bento_events' => ['eyebrow' => 'Festival', 'title' => 'Festival & Events', 'subtitle' => 'Curated offers, live right now'],
    ];

    public static function clearCache(): void
    {
        Cache::forget('home_sections_visible');
    }
}
