<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';
    protected $fillable = [];

    protected static function boot(): void
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => cache()->forget('provinces'));
        }
    }
}
