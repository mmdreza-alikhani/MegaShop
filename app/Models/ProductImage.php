<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class ProductImage extends Model
{
    use HasFactory;

    protected $table = "product_images";

    protected $fillable = [
        'product_id',
        'image',
    ];

    protected $casts = [
        'product_id' => 'integer',
    ];
}
