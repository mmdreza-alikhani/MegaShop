<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static findOrFail(int|string $key)
 * @method static where(string $string, $product_id)
 */
class AttributeCategory extends Model
{
    use HasFactory;

    protected $table = "attribute_category";

    protected $fillable = [
        'attribute_id',
        'category_id',
        'type',
    ];

    protected $casts = [
        'attribute_id' => 'integer',
        'category_id' => 'integer',
        'type' => 'string',
    ];

    public function scopeFilter($query): void
    {
        $query->where('type', 'filter');
    }

    public function scopeVariation($query): void
    {
        $query->where('type', 'variation');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
