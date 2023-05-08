<?php

namespace App\Models\Resources;

/**
 * Search any columns like (Not relationship)
 */
trait SearchColumnLikeTrait
{
    /**
     * Handle search any columns like (Not relationship)
     */
    public function scopeSearchColumnLike($query, $columns, $search)
    {
        return $query->when($search, fn ($query) => $query->where(function ($query) use ($columns, $search) {
            foreach ($columns as $key => $column) {
                if (!$key) {
                    $query->where($column, 'LIKE', '%' . $search . '%');
                } else {
                    $query->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            }

            return $query;
        }));
    }
}
