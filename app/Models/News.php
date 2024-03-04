<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, sluggable;

    protected $table = "news";
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

        static::updating(function ($news) {
            $news->slug = SlugService::createSlug($news, 'slug', $news->title);
        });
    }

    public function getIsActiveAttribute($is_active){
        return $is_active ? 'فعال' : 'غیرفعال';
    }
    public function tags(){
        return $this->belongsToMany(Tag::class, 'news_tag');
    }
    public function comments(){
        return $this->hasMany(Comment::class)->where('approved', 1);
    }
    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
