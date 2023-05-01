<?php

namespace App\Models\Resources;

use App\Constants\Inc;

/**
 * 
 */
trait FilterScope
{
    public function scopeFilterAnyColumns($query, $filter)
    {
        $contains_dot = \str_contains($filter->id, ".");
        if ($contains_dot) {
            $split = \explode(".", $filter->id);
        }
        $is_bool = \in_array($filter->value, Inc::BOOL_STRING);
        $is_object = \is_object($filter->value);

        return $query
            ->when(
                // If column is from current table
                !$contains_dot && !$is_bool && !$is_object,
                fn ($q) => $q->where($filter->id, "LIKE", "%{$filter->value}%")
            )
            ->when(
                // If column is related model N - 1
                $contains_dot,
                fn ($q) => $q->whereHas(reset($split), fn ($q) => $q->where(\end($split), 'like', "%{$filter->value}%"))
            )
            ->when(
                // Indicate if range {min: "", max: ""}
                $is_object,
                fn ($q) => $q->whereBetween($filter->id, [$filter->value->min, $filter->value->max])
            )
            ->when(
                // Indicate if less or more than, from boolean value
                $is_bool,
                fn ($q) => $q->filterBoolean($filter)
            );
    }
}
