<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'roles';
    protected $fillable = [
        'name',
        'display_name',
        'guard_name'
    ];
    protected $casts = [
        'name' => 'string',
        'display_name' => 'string',
        'guard_name' => 'string',
    ];

    protected static function boot(): void
    {
        parent::boot();

        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn () => cache()->forget('roles'));
        }
    }
}
