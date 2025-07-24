<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use function PHPUnit\Framework\returnArgument;

/**
 * @method static select(string $string)
 * @method static findOrFail(int|string $key)
 * @method static where(string $string, $id)
 * @method static create(array $array)
 */
class ProductVariation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "product_variations";

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

    protected $appends = ['is_sale','sale_percent'];

    public function getIsSaleAttribute(): bool
    {
        return $this->sale_price =! null && $this->date_on_sale_from < Carbon::now() && $this->date_on_sale_to > Carbon::now();
    }

    public function getSalePercentAttribute(): ?float
    {
        return $this->is_sale ? round((($this->price - $this->sale_price) / $this->price) * 100) : null;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
