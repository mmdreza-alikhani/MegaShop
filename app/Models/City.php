<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';
    protected $fillable = [];

    protected static function boot(): void
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => cache()->forget('cities'));
        }
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
