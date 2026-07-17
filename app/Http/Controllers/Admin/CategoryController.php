<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $parentId = $request->filled('parent') ? (int) $request->parent : null;
        $parent = $parentId ? Category::findOrFail($parentId) : null;
        $breadcrumb = $parent ? $parent->ancestorsWithSelf() : collect();

        // One level of children eager-loaded to render subcategory counts (no N+1).
        $categories = Category::query()
            ->where('parent_id', $parentId)
            ->withCount(['products', 'subCatProducts', 'subSubCatProducts'])
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')->orderBy('name')])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(30);

        $parentOptions = $this->validParentOptions($parentId);

        return view('admin.categories.index', compact('categories', 'parent', 'breadcrumb', 'parentOptions'));
    }

    /**
     * All categories, cached for the create/edit parent dropdown.
     * Filtered appropriately at render time (depth + self/descendant exclusion).
     */
    protected function validParentOptions(?int $currentParentId): \Illuminate\Support\Collection
    {
        return Cache::remember('category_parent_options', 3600, function () {
            return Category::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|string',
            'is_featured' => 'nullable|string',
        ]);

        // Enforce max depth: a parent may be at most level 1, so the new
        // category is at most level 2 (sub-subcategory).
        if ($request->filled('parent_id')) {
            $parent = Category::find($request->parent_id);
            abort_if($parent && $parent->level >= Category::MAX_DEPTH, 422,
                'A sub-subcategory cannot have children. Choose a higher-level parent.');
        }

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'categories');
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'parent_id' => $validated['parent_id'],
            'icon' => $validated['icon'],
            'image' => $imagePath,
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
        ]);

        Category::clearCache();

        return redirect()->back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'required|integer|min:0',
        ]);

        // Block setting a category as its own ancestor (cycle).
        if ($request->filled('parent_id') && $category->wouldCreateCycle((int) $request->parent_id)) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own ancestor.'])->withInput();
        }

        // Enforce max depth on the resulting parent.
        if ($request->filled('parent_id')) {
            $parent = Category::find($request->parent_id);
            abort_if($parent && $parent->level >= Category::MAX_DEPTH, 422,
                'A sub-subcategory cannot have children. Choose a higher-level parent.');
        }

        if ($category->name !== $request->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $category->slug = $slug;
        }

        if ($request->hasFile('image')) {
            if (! is_null($category->image)) {
                delete_file($category->image);
            }
            $category->image = upload_file($request->file('image'), 'categories');
        }

        $category->update([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'],
            'icon' => $validated['icon'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
        ]);

        Category::clearCache();

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function delete(Category $category)
    {
        // Reparent direct children to the root (preserving the rest of their subtree
        // positions; their levels are recomputed via the model's saved event).
        Category::where('parent_id', $category->id)->update(['parent_id' => null]);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        Category::clearCache();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}
