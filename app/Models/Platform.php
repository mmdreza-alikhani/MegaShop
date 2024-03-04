<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use HasFactory, sluggable, SoftDeletes;

    protected $table = "platforms";
    protected $guarded = [];

    public function getRouteKeyName(){
        return 'slug';
    }

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

        static::updating(function ($platform) {
            $platform->slug = SlugService::createSlug($platform, 'slug', $platform->name);
        });
    }

    public function getIsActiveAttribute($is_active){
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
