<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\SearchableTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, mixed $keyword)
 * @method static where(string $string, string $string1)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SearchableTrait;

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'email_verified_at',
        'password',
        'provider_name',
        'status',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'integer',
        'is_active' => 'boolean',
    ];

    // Default Values
    protected $attributes = [
        'avatar' => 'avatar.png',
        'is_active' => true,
        'status' => '1'
    ];

    public function scopeActive($query) {
        return $query->where('is_active', 1);
    }

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function rates(): HasMany
    {
        return $this->hasMany(ProductRate::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(WishList::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
