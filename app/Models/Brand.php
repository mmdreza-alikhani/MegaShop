<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use mysql_xdevapi\Table;

class Brand extends Model
{
    use HasFactory, sluggable, SoftDeletes;

    protected $table = "brands";
    protected $guarded = [];

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

        static::updating(function ($brand) {
            $brand->slug = SlugService::createSlug($brand, 'slug', $brand->name);
        });
    }

    public function getIsActiveAttribute($is_active){
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
