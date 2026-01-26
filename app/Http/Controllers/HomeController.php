<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch new arrivals
        $newArrivals = Product::where('is_active', true)
            ->where('is_new_arrival', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Fetch best selling products
        $bestSelling = Product::where('is_active', true)
            ->where('is_best_seller', true)
            ->with('category')
            ->orderBy('review_count', 'desc')
            ->take(8)
            ->get();

        // Fetch men's products
        $menCategory = Category::where('slug', 'like', '%men%')
            ->where('parent_id', null)
            ->first();

        $mensProducts = collect();
        if ($menCategory) {
            $mensProducts = Product::where('is_active', true)
                ->whereHas('category', function ($query) use ($menCategory) {
                    $query->where('parent_id', $menCategory->id)
                        ->orWhere('id', $menCategory->id);
                })
                ->with('category')
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        // Fetch women's products
        $womenCategory = Category::where('slug', 'like', '%women%')
            ->where('parent_id', null)
            ->first();

        $womensProducts = collect();
        if ($womenCategory) {
            $womensProducts = Product::where('is_active', true)
                ->whereHas('category', function ($query) use ($womenCategory) {
                    $query->where('parent_id', $womenCategory->id)
                        ->orWhere('id', $womenCategory->id);
                })
                ->with('category')
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        // Fetch featured categories
        $featuredCategories = Category::where('is_featured', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $banners = Banner::get();

        return view('home', compact(
            'newArrivals',
            'bestSelling',
            'mensProducts',
            'womensProducts',
            'featuredCategories',
            'banners',
        ));
    }
}
