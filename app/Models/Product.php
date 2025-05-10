<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, sluggable;

    protected $table = "products";
    protected $guarded = [];
    protected $appends = ['quantity_check' , 'sale_price' , 'min_price'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::updating(function ($product) {
            $product->slug = SlugService::createSlug($product, 'slug', $product->name);
        });
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function platform(){
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function getIsActiveAttribute($is_active){
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
                        $query->where('name', $platform);
                    }else{
                        $query->orWhere('name', $platform);
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
                    $query;
                    break;
            }
        }

        return $query;
    }

    public function scopeSearch($query){
        $keyWord = request()->search;
        if (request()->has('search') && trim($keyWord) != ''){
            $query->where('name', 'LIKE', '%'.trim($keyWord).'%');
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

    public function getRouteKeyName(){
        return 'slug';
    }

    public function attributes(){
        return $this->hasMany(ProductAttribute::class);
    }

    public function variations(){
        return $this->hasMany(ProductVariation::class);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    public function rates(){
        return $this->hasMany(ProductRate::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('approved', 1);
    }

    public function checkUserWishlist($userId){
        return $this->hasMany(WishList::class)->where('user_id', $userId)->exists();
    }
}
