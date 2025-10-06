<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static latest()
 * @method static search(string $string, string $trim)
 */
class Comment extends Model
{
    use HasFactory, SearchableTrait, SoftDeletes;

    protected $table = 'comments';

    protected $appends = ['rates'];

    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'reply_of',
        'text',
        'status',
        'is_active',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'commentable_id' => 'integer',
        'commentable_type' => 'string',
        'reply_of' => 'integer',
        'slug' => 'string',
        'text' => 'string',
        'status' => 'integer',
        'is_active' => 'boolean',
    ];

    // Default Values
    protected $attributes = [
        'reply_of' => '0',
        'is_active' => 0,
        'status' => '1',
    ];

    public function statusCondition(): string
    {
        return match ($this->status) {
            1 => 'در دست بررسی',
            2 => 'رد شده',
            3 => 'منتشر شده',
            default => 'نامشخص',
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function rates(): BelongsTo
    {
        return $this->belongsTo(ProductRate::class);
    }

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'منتشر شده' : 'منتشر نشده';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'reply_of');
    }

    public function child(): HasMany
    {
        return $this->hasMany(Comment::class, 'reply_of');
    }

    public function scopeUserId($query, $user_id): void
    {
        $query->where('user_id', $user_id);
    }

    public function scopeAwaitingReview($query)
    {
        return $query->where('status', 1);
    }

    public function getCommentableLabel(): string
    {
        $type = $this->commentable_type;

        return match ($type) {
            Post::class => 'پست',
            Product::class => 'محصول',
            default => 'نامشخص',
        };
    }
}
