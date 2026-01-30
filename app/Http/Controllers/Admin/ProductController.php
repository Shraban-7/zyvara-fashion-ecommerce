<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FitType;
use App\Enums\Occasion;
use App\Enums\Pattern;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Search by name or SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'low_stock':
                    $query->whereColumn('stock_in', '<=', 'low_stock_threshold')
                        ->where('stock_in', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('stock_in', '<=', 0);
                    break;
            }
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest('id');
                break;
            default:
                $query->latest('id');
                break;
        }

        $products = $query->paginate(15)->appends($request->query());

        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::orderBy('name')->get();
        $fitTypes = FitType::cases();
        $patterns = Pattern::cases();
        $occasions = Occasion::cases();

        return view('admin.products.create', compact(
            'categories',
            'sizes',
            'colors',
            'fitTypes',
            'patterns',
            'occasions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'fit_type' => 'nullable|string|in:' . implode(',', FitType::values()),
            'pattern' => 'nullable|string|in:' . implode(',', Pattern::values()),
            'occasion' => 'nullable|string|in:' . implode(',', Occasion::values()),
            'stock_in' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_on_sale' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // Generate slug from name
            $validated['slug'] = Str::slug($validated['name']);

            // Ensure unique slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Auto-generate SKU if not provided
            if (empty($validated['sku'])) {
                $validated['sku'] = 'PRD-' . strtoupper(Str::random(8));
            }

            // Convert tags string to array
            if (!empty($validated['tags'])) {
                $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
            }

            // Handle boolean fields (checkboxes not sent when unchecked)
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_new_arrival'] = $request->has('is_new_arrival');
            $validated['is_best_seller'] = $request->has('is_best_seller');
            $validated['is_on_sale'] = $request->has('is_on_sale');

            // Create the product
            $product = Product::create($validated);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');

                    $product->images()->create([
                        'image_path' => $path,
                    ]);
                }
            }
            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded images if product creation fails
            if (isset($product) && $product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::orderBy('name')->get();
        $fitTypes = FitType::cases();
        $patterns = Pattern::cases();
        $occasions = Occasion::cases();

        // Load relationships
        $product->load(['images', 'variants.size', 'variants.color']);

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'sizes',
            'colors',
            'fitTypes',
            'patterns',
            'occasions'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'fit_type' => 'nullable|string|in:' . implode(',', FitType::values()),
            'pattern' => 'nullable|string|in:' . implode(',', Pattern::values()),
            'occasion' => 'nullable|string|in:' . implode(',', Occasion::values()),
            'stock_in' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_on_sale' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:product_images,id',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size_id' => 'nullable|exists:sizes,id',
            'variants.*.color_id' => 'nullable|exists:colors,id',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.stock_in' => 'nullable|integer|min:0',
            'variants.*.price_adjustment' => 'nullable|numeric',
            'delete_variants' => 'nullable|array',
            'delete_variants.*' => 'exists:product_variants,id',
        ]);

        DB::beginTransaction();

        try {
            // Update slug if name changed
            if ($validated['name'] !== $product->name) {
                $validated['slug'] = Str::slug($validated['name']);

                // Ensure unique slug
                $originalSlug = $validated['slug'];
                $counter = 1;
                while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
                    $validated['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Convert tags string to array
            if (!empty($validated['tags'])) {
                $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
            }

            // Handle boolean fields
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_new_arrival'] = $request->has('is_new_arrival');
            $validated['is_best_seller'] = $request->has('is_best_seller');
            $validated['is_on_sale'] = $request->has('is_on_sale');

            // Update the product
            $product->update($validated);

            // Handle image deletions
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = $product->images()->find($imageId);
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                    ]);
                }
            }

            // Handle variant deletions
            if ($request->has('delete_variants')) {
                $product->variants()->whereIn('id', $request->delete_variants)->delete();
            }

            // Handle variants (create/update)
            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    // Skip if no size and no color selected
                    if (empty($variantData['size_id']) && empty($variantData['color_id'])) {
                        continue;
                    }

                    $variantData['product_id'] = $product->id;
                    $variantData['stock_in'] = $variantData['stock_in'] ?? 0;
                    $variantData['price_adjustment'] = $variantData['price_adjustment'] ?? 0;

                    if (!empty($variantData['id'])) {
                        // Update existing variant
                        $variant = $product->variants()->find($variantData['id']);
                        if ($variant) {
                            $variant->update($variantData);
                        }
                    } else {
                        // Create new variant
                        $product->variants()->create($variantData);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete all product images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Soft delete the product (cascades to images and variants)
            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}
