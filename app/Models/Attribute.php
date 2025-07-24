<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, string $trim)
 * @method static findOrFail(mixed $attribute_id)
 * @method static pluck(string $string, string $string1)
 */
class Attribute extends Model
{
    use HasFactory, SearchableTrait;

    protected $table = "attributes";

    protected $fillable = [
        'title'
    ];

    protected $casts = [
        'title' => 'string'
    ];

    public function scopeFilter($query): void
    {
        $query->where('type', 'filter');
    }

    public function scopeVariation($query): void
    {
        $query->where('type', 'variation');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class , 'category_attributes');
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductFilter::class)->select('attribute_id' , 'value')->distinct();
    }
}
