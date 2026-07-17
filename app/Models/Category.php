<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'show_in_menu' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'level' => 'integer',
    ];

    public const MAX_DEPTH = 2; // levels 0, 1, 2

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function subCatProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    public function subSubCatProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'sub_subcategory_id');
    }

    // Scopes
    public function scopeCategory($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helpers
    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * The depth of this category in the tree (0, 1 or 2).
     * Prefers the stored `level` column, falling back to parent-chain computation.
     */
    public function getDepth(): int
    {
        if (! is_null($this->level)) {
            return $this->level;
        }

        return $this->computeLevel();
    }

    protected function computeLevel(): int
    {
        $depth = 0;
        $parent = $this->parent;
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }

    /**
     * A category can only have children if it is not at the maximum depth.
     */
    public function canHaveChildren(): bool
    {
        return $this->getDepth() < self::MAX_DEPTH;
    }

    /**
     * Detect whether setting $newParentId as this category's parent would
     * create a cycle (i.e. the new parent is this category or one of its descendants).
     */
    public function wouldCreateCycle(int $newParentId): bool
    {
        if ($newParentId === $this->id) {
            return true;
        }

        $ancestor = static::find($newParentId);
        while ($ancestor) {
            if ($ancestor->id === $this->id) {
                return true;
            }
            $ancestor = $ancestor->parent;
        }

        return false;
    }

    /**
     * Full ancestor chain including this category, ordered root -> leaf.
     */
    public function ancestorsWithSelf()
    {
        $chain = collect([$this]);
        $parent = $this->parent;
        while ($parent) {
            $chain->prepend($parent);
            $parent = $parent->parent;
        }

        return $chain;
    }

    public function getActiveProductsCountAttribute(): int
    {
        return $this->products()->where('is_active', true)->count();
    }

    /**
     * Total products linked to this category across all three relationship columns.
     */
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count()
            + $this->subCatProducts()->count()
            + $this->subSubCatProducts()->count();
    }

    public static function clearCache()
    {
        cache()->forget('categories_menu');
        cache()->forget('all_categories_menu');
        cache()->forget('category_parent_options');
    }

    protected static function booted(): void
    {
        static::saving(function (Category $cat) {
            $parentLevel = $cat->parent_id
                ? (static::find($cat->parent_id)?->level ?? 0)
                : -1;

            $cat->level = $parentLevel + 1;

            // Enforce the 3-level ceiling at the data layer.
            if ($cat->level > self::MAX_DEPTH) {
                throw new \RuntimeException('A sub-subcategory cannot have children (max depth exceeded).');
            }
        });

        static::saved(function (Category $cat) {
            // Propagate any level change to descendants.
            $cat->children()->get()->each(function ($child) {
                $expected = ($child->parent?->level ?? 0) + 1;
                if ($child->level !== $expected) {
                    $child->saveQuietly();
                }
            });
        });
    }
}
