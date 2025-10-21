<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function getFullAddressAttribute(): string
    {
        return "{$this->province->name}, {$this->city->name}, {$this->address}";
    }

    public function scopeUser($query, $user_id){
        return $query->where('user_id', $user_id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
