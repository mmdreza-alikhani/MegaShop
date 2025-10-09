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
 * @method static where(string $string, int $int)
 * @method static whereHas(string $string, \Closure $param)
 * @method static active()
 */
class Post extends Model
{
    use HasFactory, SearchableTrait, sluggable, SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'user_id',
        'image',
        'text',
        'status',
        'is_active',
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'user_id' => 'integer',
        'image' => 'string',
        'text' => 'string',
        'status' => 'integer',
        'is_active' => 'boolean',
    ];

    // Default Values
    protected $attributes = [
        'is_active' => 1,
        'status' => '1',
    ];

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

        static::updating(function ($post) {
            $post->slug = SlugService::createSlug($post, 'slug', $post->title);
        });
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', 1);
    }

    public function scopeArticle($query): void
    {
        $query->where('type', 'article');
    }

    public function scopeNews($query): void
    {
        $query->where('type', 'news');
    }

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->where('is_active', 1);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
