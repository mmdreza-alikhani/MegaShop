<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WishList extends Model
{
    use HasFactory;

    protected $table = 'wishlists';

    protected $fillable = [
        'user_id',
        'product_id',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
    ];
    /**
     * جدول کلید اصلی ترکیبی (user_id, product_id) دارد و Eloquent آرایه را
     * پشتیبانی نمی‌کند؛ پس غیرافزایشی اعلام می‌شود تا INSERT ساده انجام شود.
     */
    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public function scopeUserId($query, $user_id): void
    {
        $query->where('user_id', $user_id);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
