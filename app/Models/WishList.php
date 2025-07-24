<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WishList extends Model
{
    use HasFactory;
    protected $table = "wishlists";

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
    ];

    public $incrementing = false; // Since we use a composite key
    protected $primaryKey = ['user_id', 'product_id']; // Composite primary key

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
