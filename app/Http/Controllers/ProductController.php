<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'brand')->where('is_active', true);

        if ($request->has('categories') && !empty($request->categories)) {
            $categorySlugs = is_array($request->categories) ? $request->categories : explode(',', $request->categories);
            $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id')->toArray();
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        } elseif ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = [$category->id];
                $children = Category::where('parent_id', $category->id)->pluck('id')->toArray();
                $categoryIds = array_merge($categoryIds, $children);

                if (!empty($children)) {
                    $grandChildren = Category::whereIn('parent_id', $children)->pluck('id')->toArray();
                    $categoryIds = array_merge($categoryIds, $grandChildren);
                }

                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Brand filter
        if ($request->has('brands') && !empty($request->brands)) {

            $brandSlugs = is_array($request->brands)
                ? $request->brands
                : explode(',', $request->brands);

            $brandIds = Brand::whereIn('slug', $brandSlugs)
                ->pluck('id')
                ->toArray();

            if (!empty($brandIds)) {
                $query->whereIn('brand_id', $brandIds);
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
            $brandIds = Brand::whereIn('slug', $brands)->pluck('id')->toArray();
            $query->whereIn('brand_id', $brandIds);
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
                    //->orWhere('brand', 'like', '%' . $searchTerm . '%')
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
        $products = $query->simplePaginate($perPage)->appends($request->query());

        // Get filter data
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::orderBy('name')->get();

        // Get available brands from products
        $brands = Brand::active()
            ->orderBy('name')
            ->withCount('products')
            ->get();

        $brandCounts = [];
        foreach ($brands as $brand) {
            $brandCounts[$brand->id] = $brand->products_count;
        }

        $cats = Category::whereNull('parent_id')->with('children')->withCount('products')->get();
        $subCats = Category::whereNotNull('parent_id')
            ->select('id', 'parent_id')
            ->withCount('products')
            ->get();

        $categoryCounts = [];
        foreach ($cats as $cat) {
            $productCount = $cat->products_count + $subCats->where('parent_id', $cat->id)->sum('products_count');
            $categoryCounts[$cat->id] = $productCount;
        }

        foreach ($subCats as $cat) {
            $categoryCounts[$cat->id] = $cat->products_count;
        }

        return view('products.index', compact(
            'products',
            'categories',
            'sizes',
            'colors',
            'brands',
            'categoryCounts',
            'brandCounts'
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
        $product = Product::with([
            'category',
            'images',
            'variants.size',
            'variants.color',
            'approvedReviews.user',
            'approvedReviews.images'
        ])
            ->where('slug', $slug)
            ->where('is_active', true)->first();

        if (!$product) {
            abort(404);
        }

        $variantTotalStock = 0;

        foreach ($product->variants as $variant) {
            $variantTotalStock += $variant->currentStock;
        }

        // Increment view count
        $product->incrementViewCount();

        // Get unique sizes and colors from variants
        $availableSizes = $product->variants
            ->pluck('size')
            ->unique('id')
            ->sortBy('sort_order')
            ->values()
            ->filter();

        $availableColors = $product->variants
            ->pluck('color')
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->filter();

        $relatedProducts = Product::with(['images', 'category'])
            //->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        // Calculate rating distribution
        $ratingDistribution = [
            5 => $product->approvedReviews()->where('rating', 5)->count(),
            4 => $product->approvedReviews()->where('rating', 4)->count(),
            3 => $product->approvedReviews()->where('rating', 3)->count(),
            2 => $product->approvedReviews()->where('rating', 2)->count(),
            1 => $product->approvedReviews()->where('rating', 1)->count(),
        ];

        $variantsMap = $product->variants->map(function ($variant) {
            return [
                'size_id' => $variant->size_id,
                'color_id' => $variant->color_id,
                'size_name' => $variant->size?->name,
                'color_name' => $variant->color?->name,
                'stock' => $variant->currentStock,
            ];
        })->values();

        return view('products.show', compact('product', 'relatedProducts', 'ratingDistribution', 'availableSizes', 'availableColors', 'variantTotalStock', 'variantsMap'));
    }

    /**
     * Get product details for (Quick View)
     */
    public function getQuickviewData($id)
    {
        $product = Product::with([
            'category',
            'images',
            'variants.size',
            'variants.color'
        ])
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $haveVariant = $product->variants->count();

        // Transform product data for API
        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'brand' => $product->brand,
            'category' => $product->category,
            'price' => (float) $product->price,
            'compare_price' => $product->compare_price ? (float) $product->compare_price : null,
            'short_description' => $product->short_description,
            'stock_in' => $product->stock_in,
            'stock' => $haveVariant > 0 ?  0 : $product->currentStock,
            'is_new_arrival' => $product->is_new_arrival,
            'is_best_seller' => $product->is_best_seller,
            'is_on_sale' => $product->is_on_sale,
            'is_featured' => $product->is_featured,
            'average_rating' => (float) $product->average_rating,
            'review_count' => $product->review_count,
            'image' => $product->thumbnail,
            'images' => $product->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_url' => storage_url($image->image_path),
                    'is_primary' => $image->is_primary,
                ];
            }),
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'size_id' => $variant->size_id,
                    'color_id' => $variant->color_id,
                    'stock_in' => $variant->stock_in,
                    'stock' => $variant->currentStock,
                    'price' => (float) ($variant->price ?? 0),
                    'size' => $variant->size ? [
                        'id' => $variant->size->id,
                        'name' => $variant->size->name,
                    ] : null,
                    'color' => $variant->color ? [
                        'id' => $variant->color->id,
                        'name' => $variant->color->name,
                        'hex_code' => $variant->color->hex_code,
                    ] : null,
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'product' => $productData
        ]);
    }
}
