<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';
    protected $fillable = [
        'name'
    ];
    protected $casts = [
        'name' => 'string'
    ];

    protected static function boot(): void
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => cache()->forget('provinces'));
        }
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
