<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getStatusAttribute($status){
        return $status ? 'فعال' : 'غیرفعال';
    }

    public function rates(){
        return $this->hasMany(ProductRate::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function addresses(){
        return $this->hasMany(UserAddresses::class);
    }

    public function wishlist(){
        return $this->hasMany(WishList::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
