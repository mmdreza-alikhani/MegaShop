<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, sluggable;

    protected $table = "articles";
    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($article) {
            $article->slug = SlugService::createSlug($article, 'slug', $article->title);
        });
    }

    public function getIsActiveAttribute($is_active){
        return $is_active ? 'فعال' : 'غیرفعال';
    }
    public function tags(){
        return $this->belongsToMany(Tag::class, 'article_tag');
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->where('approved', 1);
    }
    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
