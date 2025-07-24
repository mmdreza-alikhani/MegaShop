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

/**
 * @method static latest()
 * @method static where(string $string, string $string1, int $int)
 * @method static create(array $array)
 * @method static active()
 * @method static parents()
 * @method static search(string $string, string $trim)
 * @method static findOrFail(mixed $category_id)
 * @property mixed $parent_id
 */
class Category extends Model
{
    use HasFactory, sluggable, SearchableTrait;

    protected $table = "categories";

    protected $fillable = [
        'title',
        'slug',
        'description',
        'parent_id',
        'status',
        'is_active'
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'parent_id' => 'integer',
        'status' => 'integer',
        'is_active' => 'boolean'
    ];

    // Default Values
    protected $attributes = [
        'is_active' => 1,
        'status' => '1',
        'parent_id' => '0'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
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
        return $this->belongsTo(Category::class , 'parent_id');
    }

    public function isParent(): bool
    {
        return $this->parent_id == 0;
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class , 'parent_id');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class , 'category_attributes');
    }

    public function filters(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class , 'category_attributes')->where('type', 'filter');
    }

    public function variation(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class , 'category_attributes')->where('type', 'variation');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function allProducts()
    {
        return Product::whereIn('category_id', '=', $this->getAllCategoryIds())->get();
    }
}
