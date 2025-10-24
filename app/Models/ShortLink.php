<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    use HasFactory;

    protected $table = 'short_links';

    protected $fillable = [
        'code',
        'type',
        'target_id'
    ];
    protected $casts = [
        'code' => 'string',
        'type' => 'string',
        'target_id' => 'integer'
    ];

    public function target()
    {
        return match ($this->type) {
            'product' => Product::find($this->target_id),
            'post' => Post::find($this->target_id),
            default => null,
        };
    }
}
