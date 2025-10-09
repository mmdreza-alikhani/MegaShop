<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchableTrait
{
    public function scopeSearch(Builder $query, array|string $columns, ?string $keyword): Builder
    {
        if (empty($keyword)) {
            return $query;
        }

        $keyword = trim($keyword);
        $columns = (array) $columns;

        return $query->where(function ($q) use ($columns, $keyword) {
            foreach ($columns as $column) {
                // چک کردن relation
                if (str_contains($column, '.')) {
                    [$relation, $field] = explode('.', $column);
                    $q->orWhereHas($relation, fn($query) =>
                    $query->where($field, 'LIKE', "%{$keyword}%")
                    );
                } else {
                    $q->orWhere($column, 'LIKE', "%{$keyword}%");
                }
            }
        });
    }
}
