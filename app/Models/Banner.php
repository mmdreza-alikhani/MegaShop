<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static where(string $string, int $int)
 * @method static search(string $string, string $trim)
 * @method static active()
 */
class Banner extends Model
{
    use HasFactory, SearchableTrait, SoftDeletes;

    protected $table = 'banners';
    protected $fillable = [
        'image',
        'title',
        'text',
        'priority',
        'type',
        'button_text',
        'button_link',
        'status',
        'is_active',
    ];
    protected $casts = [
        'image' => 'string',
        'title' => 'string',
        'text' => 'string',
        'priority' => 'integer',
        'type' => 'string',
        'button_text' => 'string',
        'button_link' => 'string',
        'status' => 'integer',
        'is_active' => 'boolean',
    ];
    protected $attributes = [
        'is_active' => true,
        'status' => '1',
    ];

    protected static function boot(): void
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => cache()->forget('banners'));
        }
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', 1);
    }

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }
}
