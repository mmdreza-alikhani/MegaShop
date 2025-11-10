<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, mixed $id)
 */
class Transaction extends Model
{
    use HasFactory, SearchableTrait;

    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'ref_id',
        'token',
        'description',
        'gateway_name',
        'status',
        'getaway_name',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'order_id' => 'integer',
        'amount' => 'integer',
        'status' => 'integer',
    ];
    protected $attributes = [
        'status' => 0,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
