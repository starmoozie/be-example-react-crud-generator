<?php

namespace App\Models\Resources;

/**
 * Handle list table activity, like search, sort, filter, paginations, etc.
 */
trait TableActivityScopeTrait
{
    /**
     * Handle list table activity, like search, sort, filter, paginations, etc.
     */
    public function scopeTableActivity($query, $filters, $sortBy, $searchable_columns)
    {
        return $query->when(
                $filters && count($filters),
                function ($query) use ($filters) {
                    foreach ($filters as $filter) {
                        $query->filterAnyColumns($filter);
                    }

                    return $query;
                }
            )
            ->when(
                $sortBy && count($sortBy),
                function ($query) use ($sortBy) {
                    foreach ($sortBy as $sort) {
                        $split = explode('.', $sort->id);
                        $name = $split[0];
                        $relationship = in_array($name, $this->model->getRelationship());
                        $appends      = in_array($name, $this->model->getAppends());

                        if ($relationship) {
                            $model_name    = \ucwords($name);
                            $related_model = "\\App\\Models\\{$model_name}";
                            $model         = new $related_model();

                            $fk           = \Str::snake($name);
                            $related_table = \Str::plural($fk);

                            $query->orderBy(
                                $model->select($split[1])->whereColumn("{$this->model->getTable()}.{$fk}_id", "{$related_table}.id"),
                                $sort->desc ? 'desc' : 'asc'
                            );
                        } elseif (!$relationship && !$appends) {
                            $column = $name;
                            $query->orderBy($column, $sort->desc ? 'desc' : 'asc');
                        }
                    }
                },
                fn ($query) => $query->orderBy($searchable_columns[0], 'asc')
            );
    }
}
