<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'title',
        'address',
        'postal_code',
        'phone_number',
        'user_id',
        'province_id',
        'city_id',
        'longitude',
        'latitude',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'province_id' => 'integer',
        'city_id' => 'integer',
        'longitude' => 'string',
        'latitude' => 'string',
    ];

    public function scopeUser($query, $user_id){
        return $query->where('user_id', $user_id);
    }
}
