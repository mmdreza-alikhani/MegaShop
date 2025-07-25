<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, string $trim)
 */
class Coupon extends Model
{
    use HasFactory, SoftDeletes, SearchableTrait;

    protected $table = "coupons";

    protected $fillable = [
        'title',
        'code',
        'type',
        'amount',
        'percentage',
        'max_percentage_amount',
        'expired_at',
        'description',
        'status',
        'is_active',
    ];

    protected $casts = [
        'title' => 'string',
        'code' => 'string',
        'type' => 'string',
        'amount' => 'integer',
        'percentage' => 'integer',
        'max_percentage_amount' => 'integer',
        'expired_at' => 'datetime',
        'status' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => 1,
        'status' => '1'
    ];

    public function getTypeAttribute($type){
        return $type == 'amount' ? 'مبلغی' : 'درصدی';
    }

}
