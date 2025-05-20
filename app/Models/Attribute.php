<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    protected $table = "attributes";

    protected $fillable = [
        'title'
    ];

    protected $casts = [
        'title' => 'string'
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class , 'attribute_category');
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductAttribute::class)->select('attribute_id' , 'value')->distinct();
    }
}
