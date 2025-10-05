<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static select(string $string)
 * @method static findOrFail(int|string $key)
 * @method static where(string $string, $id)
 * @method static create(array $array)
 * @method static find(mixed $variationId)
 */
class ProductVariation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variations';

    protected $fillable = [
        'attribute_id',
        'product_id',
        'value',
        'price',
        'quantity',
        'sku',
        'sale_price',
        'date_on_sale_from',
        'date_on_sale_to',
    ];

    protected $casts = [
        'attribute_id' => 'integer',
        'product_id' => 'integer',
        'price' => 'integer',
        'quantity' => 'integer',
        'sale_price' => 'integer',
        'date_on_sale_from' => 'datetime',
        'date_on_sale_to' => 'datetime',
    ];

    protected $appends = ['best_price', 'is_discounted'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function scopeAvailable($query, $q = 0): void
    {
        $query->where('quantity', '>=', $q);
    }

    public function getIsDiscountedAttribute(): bool
    {
        return ($this->sale_price != null) && ($this->date_on_sale_from < Carbon::now()) && ($this->date_on_sale_to > Carbon::now());
    }

    public function getBestPriceAttribute()
    {
        return $this->is_discounted ? $this->sale_price : $this->price;
    }
}
