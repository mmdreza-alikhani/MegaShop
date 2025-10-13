<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * @method static latest()
 * @method static where(string $string, string $string1, int $int)
 * @method static create(array $array)
 * @method static active()
 * @method static parents()
 * @method static search(string $string, string $trim)
 * @method static findOrFail(mixed $category_id)
 *
 * @property mixed $parent_id
 */
class Category extends Model
{
    use HasFactory, SearchableTrait, sluggable;

    protected $table = 'categories';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'parent_id',
        'status',
        'is_active',
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'parent_id' => 'integer',
        'status' => 'integer',
        'is_active' => 'boolean',
    ];

    // Default Values
    protected $attributes = [
        'is_active' => 1,
        'status' => '1',
        'parent_id' => '0',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::updating(function ($category) {
            $category->slug = SlugService::createSlug($category, 'slug', $category->name);
        });
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', 1);
    }

    public function scopeParents($query): void
    {
        $query->where('parent_id', 0);
    }

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function isParent(): bool
    {
        return $this->parent_id == 0;
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes');
    }

    public function filters(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes')->where('type', 'filter');
    }

    public function variation(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes')->where('type', 'variation');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getMergedFilters(): Collection
    {
        $categoryIds = $this->getAllDescendantIds();
        $cacheKey = 'filters_categories_' . md5(implode(',', $categoryIds));

        return Cache::remember($cacheKey, now()->addHour(), function () use ($categoryIds) {
            // ✅ یک Query برای گرفتن ID فیلترها
            $filterIds = CategoryAttribute::whereIn('category_id', $categoryIds)
                ->filter()
                ->distinct()
                ->pluck('attribute_id');

            if ($filterIds->isEmpty()) {
                return collect();
            }

            // ✅ گرفتن فیلترها با مقادیرشون
            return Attribute::whereIn('id', $filterIds)
                ->with(['filterValues' => function ($query) {
                    $query->select('id', 'attribute_id', 'value')
                        ->orderBy('value');
                }])
                ->select('id', 'title')
                ->orderBy('title')
                ->get()
                ->map(function ($filter) {
                    $filter->filterValues = $filter->filterValues
                        ->unique('value')
                        ->values();

                    return $filter;
                });
        });
    }

    /**
     * گرفتن ID تمام دسته‌بندی‌های فرزند
     */
    public function getAllDescendantIds(): array
    {
        return Cache::remember("category_{$this->id}_descendants", now()->addDay(), function () {
            return $this->getDescendantIdsRecursive($this->id);
        });
    }

    /**
     * Recursive method
     */
    private function getDescendantIdsRecursive(int $categoryId): array
    {
        $ids = [$categoryId];

        $children = self::where('parent_id', '=', $categoryId)->pluck('id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getDescendantIdsRecursive($childId));
        }

        return $ids;
    }

    /**
     * پاک کردن Cache فیلترها
     */
    public function clearFiltersCache(): void
    {
        $categoryIds = $this->getAllDescendantIds();
        $cacheKey = 'filters_categories_' . md5(implode(',', $categoryIds));
        Cache::forget($cacheKey);

        // پاک کردن Cache والدین
        $parent = $this->parent;
        while ($parent) {
            $parentIds = $parent->getAllDescendantIds();
            $parentCacheKey = 'filters_categories_' . md5(implode(',', $parentIds));
            Cache::forget($parentCacheKey);
            $parent = $parent->parent;
        }
    }

    /**
     * Model Events
     */
    protected static function booted(): void
    {
        static::saved(function ($category) {
            Cache::forget("category_{$category->id}_descendants");
            $category->clearFiltersCache();
        });

        static::deleted(function ($category) {
            Cache::forget("category_{$category->id}_descendants");
            $category->clearFiltersCache();
        });
    }
}
