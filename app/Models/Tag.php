<?php

namespace App\Models;

use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static search(string $string, string $trim)
 */
class Tag extends Model
{
    use HasFactory, SearchableTrait;

    protected $table = "tags";
    protected $fillable = [
        "title"
    ];
}
