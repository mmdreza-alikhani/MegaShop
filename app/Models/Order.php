<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";
    protected $guarded = [];

    public function address(){
        return $this->belongsTo(UserAddresses::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transaction(){
        return $this->hasOne(Transaction::class);
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function getPaymentStatusAttribute($payment_status){
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
