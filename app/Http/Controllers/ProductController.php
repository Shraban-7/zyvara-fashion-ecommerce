<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // Category filter
        if ($request->has('categories') && !empty($request->categories)) {
            $categoryIds = is_array($request->categories) ? $request->categories : explode(',', $request->categories);
            $query->whereIn('category_id', $categoryIds);
        } elseif ($request->has('category')) {
            // Handle single category slug
            $category = Category::where('slug', 'like', '%' . $request->category . '%')->first();
            if ($category) {
                // Get category and all its descendants
                $categoryIds = [$category->id];
                $children = Category::where('parent_id', $category->id)->pluck('id')->toArray();
                $categoryIds = array_merge($categoryIds, $children);

                // Get grandchildren
                if (!empty($children)) {
                    $grandChildren = Category::whereIn('parent_id', $children)->pluck('id')->toArray();
                    $categoryIds = array_merge($categoryIds, $grandChildren);
                }

                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Size filter
        if ($request->has('sizes') && !empty($request->sizes)) {
            $sizeIds = is_array($request->sizes) ? $request->sizes : explode(',', $request->sizes);
            $query->whereHas('variants', function ($q) use ($sizeIds) {
                $q->whereIn('size_id', $sizeIds);
            });
        }

        // Color filter
        if ($request->has('colors') && !empty($request->colors)) {
            $colorIds = is_array($request->colors) ? $request->colors : explode(',', $request->colors);
            $query->whereHas('variants', function ($q) use ($colorIds) {
                $q->whereIn('color_id', $colorIds);
            });
        }

        // Brand filter
        if ($request->has('brands') && !empty($request->brands)) {
            $brands = is_array($request->brands) ? $request->brands : explode(',', $request->brands);
            $query->whereIn('brand', $brands);
        }

        // Rating filter
        if ($request->has('min_rating') && $request->min_rating != '') {
            $query->where('average_rating', '>=', $request->min_rating);
        }

        // Special filters
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'new-arrivals':
                    $query->where('is_new_arrival', true);
                    break;
                case 'best-sellers':
                    $query->where('is_best_seller', true);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'on-sale':
                    $query->where('is_on_sale', true);
                    break;
            }
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('brand', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tags', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'featured');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'best_selling':
                $query->where('is_best_seller', true)->orderBy('review_count', 'desc');
                break;
            case 'top_rated':
                $query->orderBy('average_rating', 'desc')->orderBy('review_count', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default: // featured
                $query->orderBy('is_featured', 'desc')
                    ->orderBy('is_new_arrival', 'desc')
                    ->orderBy('review_count', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 24);
        $products = $query->paginate($perPage)->appends($request->query());

        // Get filter data
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::orderBy('name')->get();

        // Get available brands from products
        $brands = Product::where('is_active', true)
            ->distinct()
            ->pluck('brand')
            ->filter()
            ->sort()
            ->values();

        // Get counts for filters
        $categoryCounts = [];
        foreach ($categories as $category) {
            $categoryCounts[$category->id] = Product::where('category_id', $category->id)
                ->where('is_active', true)
                ->count();

            foreach ($category->children as $child) {
                $categoryCounts[$child->id] = Product::where('category_id', $child->id)
                    ->where('is_active', true)
                    ->count();
            }
        }

        return view('products.index', compact(
            'products',
            'categories',
            'sizes',
            'colors',
            'brands',
            'categoryCounts'
        ));
    }

    /**
     * Display the specified product.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        // In the future, fetch product by slug
        return view('products.show');
    }
}
