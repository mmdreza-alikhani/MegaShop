<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, string $trim)
 * @method static pluck(string $string, string $string1)
 */
class Tag extends Model
{
    use HasFactory, SearchableTrait;

    protected $table = 'tags';
    protected $fillable = [
        'title',
    ];
    protected $casts = [
        'title' => 'string',
    ];

    protected static function boot(): void
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => cache()->forget('tags'));
        }
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_tag');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }
}
