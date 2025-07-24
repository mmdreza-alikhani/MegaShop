<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, string $trim)
 * @method static pluck(string $string, string $string1)
 */
class Tag extends Model
{
    use HasFactory, SearchableTrait;

    protected $table = "tags";
    protected $fillable = [
        "title"
    ];
}
