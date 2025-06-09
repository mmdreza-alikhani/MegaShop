<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, sluggable, SoftDeletes, SearchableTrait;

    protected $table = "brands";

    protected $fillable = [
        'title',
        'slug',
        'status',
        'is_active'
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'status' => 'integer',
        'is_active' => 'boolean'
    ];

    // Default Values
    protected $attributes = [
        'is_active' => 1,
        'status' => '1'
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

        static::updating(function ($brand) {
            $brand->slug = SlugService::createSlug($brand, 'slug', $brand->name);
        });
    }

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
