<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'order_status_histories';

    protected $fillable = [
        'order_id',
        'user_id',
        'from_status',
        'to_status',
        'note',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'user_id' => 'integer',
        'from_status' => 'integer',
        'to_status' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return Order::statusLabel((int) $this->to_status);
    }
}
