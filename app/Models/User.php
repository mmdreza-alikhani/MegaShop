<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\SearchableTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, mixed $keyword)
 * @method static where(string $string, string $string1)
 */
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SearchableTrait;

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
        'is_active',
        'avatar',
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
    protected $attributes = [
        'email_verified_at' => NULL,
        'avatar' => 'avatar.png',
        'is_active' => true,
        'status' => 1,
        'provider_name' => 'web',
    ];

    protected static function boot(): void
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => cache()->forget('users_count'));
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function getIsValidatedAttribute(): bool
    {
        return $this->first_name && $this->last_name && $this->phone_number && $this->email_verified_at;
    }

    public function getFormattedPhoneAttribute()
    {
        if (empty($this->phone_number)) {
            return 'شماره تلفنی یافت نشد!';
        }

        if (str_starts_with($this->phone_number, '09')) {
            return $this->phone_number;
        }

        if (str_starts_with($this->phone_number, '9')) {
            return '0' . $this->phone_number;
        }

        return $this->phone_number;
    }

    public function getProviderAttribute(): string
    {
        return match ($this->provider_name) {
            'manual' => 'دستی',
            'google' => 'گوگل حساب ',
            default => 'نامشخص',
        };
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
