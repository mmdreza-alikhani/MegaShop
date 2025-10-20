<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, string $trim)
 */
class Coupon extends Model
{
    use HasFactory, SearchableTrait, SoftDeletes;

    protected $table = 'coupons';
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
        'status' => '1',
    ];

    protected static function boot(): void
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => cache()->forget('coupons'));
        }
    }

    public function scopeIsAvailable($query): void
    {
        $query->where('is_active', 1)->where('expired_at', '>', now());
    }

    public function getTypeAttribute($type): string
    {
        return $type == 'amount' ? 'مبلغی' : 'درصدی';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
