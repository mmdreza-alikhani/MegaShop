<?php

namespace App\Models;

use App\Traits\SearchableTrait;
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
 * @method static search(string $string, string $trim)
 * @method static create(array $array)
 */
class Product extends Model
{
    use HasFactory, sluggable, SearchableTrait;

    protected $table = "products";

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
        'delivery_amount_per_product',
    ];

    protected $casts = [
        'brand_id' => 'integer',
        'category_id' => 'integer',
        'platform_id' => 'integer',
        'status' => 'integer',
        'is_active' => 'boolean',
        'delivery_amount' => 'integer',
        'delivery_amount_per_product' => 'integer',
    ];

    protected $attributes = [
        'status' => '1',
        'is_active' => '0',
        'delivery_amount' => '0',
    ];

    protected $appends = ['quantity_check' , 'sale_price' , 'min_price'];

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

        static::updating(function ($product) {
            $product->slug = SlugService::createSlug($product, 'slug', $product->title);
        });
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

    public function scopeFilter($query){

        if(request()->has('attribute')){
            foreach (request()->attribute as $attribute){
                $query->whereHas('attributes', function ($query) use ($attribute){
                    foreach (explode('-', $attribute) as $index => $item){
                        if ($index == 0){
                            $query->where('value', $item);
                        }else{
                            $query->orWhere('value', $item);
                        }
                    }
                });
            }
        }

        if(request()->has('variation')){
            $query->whereHas('variations', function ($query){
                foreach (explode('-', request()->variation) as $index => $variation){
                    if ($index == 0){
                        $query->where('value', $variation);
                    }else{
                        $query->orWhere('value', $variation);
                    }
                }
            });
        }

        if(request()->has('platform')){
            $query->whereHas('platform', function ($query){
                foreach (explode('-', request()->platform) as $index => $platform){
                    if ($index == 0){
                        $query->where('title', $platform);
                    }else{
                        $query->orWhere('title', $platform);
                    }
                }
            });
        }

        if(request()->has('sortBy')){
            $sortBy = request()->sortBy;
            switch ($sortBy){
                case 'highest':
                    $query->orderByDesc(
                        ProductVariation::select('price')->whereColumn('product_variations.product_id', 'products.id')->orderBy('price', 'desc')->take(1)
                    );
                    break;
                case 'lowest':
                    $query->orderBy(
                        ProductVariation::select('price')->whereColumn('product_variations.product_id', 'products.id')->orderBy('price', 'asc')->take(1)
                    );
                    break;
                case 'latest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    break;
            }
        }

        return $query;
    }

    public function scopeSearch($query){
        $keyWord = request()->search;
        if (request()->has('search') && trim($keyWord) != ''){
            $query->where('title', 'LIKE', '%'.trim($keyWord).'%');
        }

        return $query;
    }

    public function scopeDiscount($query){
        if (request()->has('discount') || request()->discount === true){
            $query->whereHas('variations', function ($query){$query->where('quantity', '>', '0');})->whereHas('variations', function ($query){$query->where('date_on_sale_from', '<', Carbon::now());})->whereHas('variations', function ($query){$query->where('date_on_sale_to', '>', Carbon::now());});
        }

        return $query;
    }

    public function getQuantityCheckAttribute(){
        return $this->variations()->where('quantity', '>', '0')->first() ?? null;
    }

    public function getSalePriceAttribute(){
        return $this->variations()->where('quantity', '>', '0')->where('sale_price', '!=', null)->where('date_on_sale_from', '<', Carbon::now())->where('date_on_sale_to', '>', Carbon::now())->orderBy('sale_price')->first() ?? null;
    }

    public function getMinPriceAttribute(){
        return $this->variations()->where('quantity', '>', '0')->orderBy('price')->first() ?? null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function attributes(): HasMany
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
        return $this->morphMany(Comment::class, 'commentable')->where('approved', 1);
    }

    public function checkUserWishlist($userId): bool
    {
        return $this->hasMany(WishList::class)->where('user_id', $userId)->exists();
    }
}
