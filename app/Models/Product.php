<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Binafy\LaravelCart\Cartable;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method static whereIn(string $string, string $string1, $getAllCategoryIds)
 * @method static where(string $string, string $string1)
 * @method static latest()
 * @method static create(array $array)
 * @method static active()
 * @method static findOrFail(mixed $input)
 */
class Product extends Model implements Cartable
{
    use HasFactory, SearchableTrait, sluggable;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'brand_id',
        'category_id',
        'platform_id',
        'slug',
        'primary_image',
        'description',
        'status',
        'is_active',
        'delivery_amount',
    ];

    protected $casts = [
        'brand_id' => 'integer',
        'category_id' => 'integer',
        'platform_id' => 'integer',
        'status' => 'integer',
        'is_active' => 'boolean',
        'delivery_amount' => 'integer',
    ];

    protected $attributes = [
        'status' => '1',
        'is_active' => 0,
        'delivery_amount' => 0,
    ];

    protected $appends = ['best_selling_price'];

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

        static::updating(function ($product) {
            $product->slug = SlugService::createSlug($product, 'slug', $product->title);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPrice(): float
    {
        //
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', 1);
    }

    public function scopeCategory($query, $category_id): void
    {
        $query->where('category_id', $category_id);
    }

    public function scopeFilter($query)
    {
        $request = request();

        $applyFilter = function ($relation, $field, $values) use ($query) {
            $query->whereHas($relation, function ($q) use ($field, $values) {
                $values = explode('-', $values);
                $q->where(function ($subQ) use ($field, $values) {
                    foreach ($values as $value) {
                        $subQ->orWhere($field, $value);
                    }
                });
            });
        };

        if ($request->filled('filter')) {
            foreach ($request->filter as $filter) {
                $applyFilter('filters', 'value', $filter);
            }
        }

        if ($request->filled('v')) {
            $applyFilter('variations', 'value', $request->v);
        }

        if ($request->filled('platform')) {
            $applyFilter('platform', 'title', $request->platform);
        }

        if ($request->boolean('discount')) {
            $query->whereHas('variations', function ($q) {
                $q->where('quantity', '>', 0)
                    ->where('date_on_sale_from', '<', now())
                    ->where('date_on_sale_to', '>', now());
            });
        }

        if ($request->filled('sortBy')) {
            match ($request->sortBy) {
                'highest' => $query->orderByDesc(
                    ProductVariation::select('price')
                        ->whereColumn('product_variations.product_id', 'products.id')
                        ->orderByDesc('price')
                        ->limit(1)
                ),
                'lowest' => $query->orderBy(
                    ProductVariation::select('price')
                        ->whereColumn('product_variations.product_id', 'products.id')
                        ->orderBy('price')
                        ->limit(1)
                ),
                'latest' => $query->latest(),
                'oldest' => $query->oldest(),
                default => null,
            };
        }

        return $query;
    }

    public function getBestSellingPriceAttribute()
    {
        return $this->variations()->where('sale_price', '!=', null)->where('date_on_sale_from', '<', Carbon::now())->where('date_on_sale_to', '>', Carbon::now())->orderBy('sale_price')->first() ?? $this->variations()->orderBy('price')->first();
    }

    public function filters(): HasMany
    {
        return $this->hasMany(ProductFilter::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(ProductRate::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->where('is_active', 1);
    }

    public function checkUserWishlist($userId): bool
    {
        return $this->hasMany(WishList::class)->where('user_id', $userId)->exists();
    }
}
