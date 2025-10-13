<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static where(string $string, int $int)
 * @method static active()
 * @method static pluck(string $string, string $string1)
 */
class Platform extends Model
{
    use HasFactory, SearchableTrait, sluggable, SoftDeletes;

    protected $table = 'platforms';

    protected $fillable = [
        'title',
        'slug',
        'image',
        'status',
        'is_active',
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'image' => 'string',
        'status' => 'integer',
        'is_active' => 'boolean',
    ];

    // Default Values
    protected $attributes = [
        'is_active' => 1,
        'status' => '1',
    ];

    //    public function getRouteKeyName(): string
    //    {
    //        return 'slug';
    //    }

    public function scopeActive($query): void
    {
        $query->where('is_active', 1);
    }

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

        static::updating(function ($platform) {
            $platform->slug = SlugService::createSlug($platform, 'slug', $platform->name);
        });

        static::saved(function () {
            Cache::forget("platforms_list");
        });

        static::deleted(function () {
            Cache::forget("platforms_list");
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
