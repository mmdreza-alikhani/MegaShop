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
class ProductFilter extends Model
{
    use HasFactory;

    protected $table = 'product_filters';
    protected $fillable = [
        'attribute_id',
        'product_id',
        'value',
        'is_active',
        'updated_at',
    ];
    protected $casts = [
        'attribute_id' => 'integer',
        'product_id' => 'integer',
        'is_active' => 'boolean',
    ];
    protected $attributes = [
        'is_active' => 1,
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
