<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchableTrait
{
    public function scopeSearch(Builder $query, string $column, ?string $keyword): Builder
    {
        return $query->when($keyword, fn ($q) => $q->where($column, 'LIKE', "%{$keyword}%"));
    }
}
