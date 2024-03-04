<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    use HasFactory , sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($category) {
            $category->slug = SlugService::createSlug($category, 'slug', $category->name);
        });
    }

    protected $table = "categories";
    protected $guarded = [];

    public function getIsActiveAttribute($is_active){
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function parent()
    {
        return $this->belongsTo(Category::class , 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class , 'parent_id');
    }

    public function attributes(){
        return $this->belongsToMany(Attribute::class , 'attribute_category');
    }

    public function products(){
        return $this->parent_id != 0 ? $this->hasMany(Product::class) : $this->children()->first()->hasMany(Product::class) ;
    }
}
