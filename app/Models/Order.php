<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, SearchableTrait;

    protected $table = "orders";

    protected $fillable = [
        'user_id',
        'address_id',
        'coupon_id',
        'status',
        'total_amount',
        'delivery_amount',
        'coupon_amount',
        'paying_amount',
        'payment_type',
        'payment_status',
        'description',
    ];

    protected $casts = [
        'status' => 'integer',
        'total_amount' => 'integer',
        'delivery_amount' => 'integer',
        'coupon_amount' => 'integer',
        'paying_amount' => 'integer',
        'payment_status' => 'integer',
    ];

    protected $attributes = [
        'coupon_amount' => '0',
        'delivery_amount' => '0',
        'status' => '0',
        'payment_status' => '0'
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getPaymentStatusAttribute($payment_status): string
    {
        return $payment_status ? 'پرداخت شده' : 'پرداخت نشده';
    }

    public function getPaymentTypeAttribute($payment_type){
        switch ($payment_type){
            case 'online' :
                $payment_type = 'انلاین';
                break;
            case 'pos' :
                $payment_type = 'پز';
                break;
            case 'cash' :
                $payment_type = 'نقد';
                break;
            case 'shabaNumber' :
                $payment_type = 'شبا';
                break;
            case 'cardToCard' :
                $payment_type = 'کارت به کارت';
                break;
        }
        return $payment_type;
    }
}
