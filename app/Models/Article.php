<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, string $trim)
 */
class Article extends Model
{
    use HasFactory, sluggable, SoftDeletes, SearchableTrait;

    protected $table = "articles";

    protected $fillable = [
        'title',
        'slug',
        'user_id',
        'primary_image',
        'text',
        'status',
        'is_active'
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'user_id' => 'integer',
        'primary_image' => 'string',
        'text' => 'string',
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

        static::updating(function ($article) {
            $article->slug = SlugService::createSlug($article, 'slug', $article->title);
        });
    }



    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->where('approved', 1);
    }
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
