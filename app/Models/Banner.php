<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static create(array $array)
 */
class Banner extends Model
{
    use HasFactory, SoftDeletes, SearchableTrait;

    protected $table = "banners";

    protected $fillable = [
        'image',
        'title',
        'text',
        'priority',
        'type',
        'button_text',
        'button_link',
        'button_icon',
        'status',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'image' => 'string',
        'title' => 'string',
        'text' => 'string',
        'priority' => 'integer',
        'type' => 'string',
        'button_text' => 'string',
        'button_link' => 'string',
        'button_icon' => 'string',
        'status' => 'integer',
        'is_active' => 'boolean'
    ];

    // Default Values
    protected $attributes = [
        'is_active' => true,
        'status' => '1'
    ];

    public function getIsActiveAttribute($is_active): string
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }
}
