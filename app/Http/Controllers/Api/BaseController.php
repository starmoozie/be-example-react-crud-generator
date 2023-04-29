<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Constants\HttpCode;

class BaseController extends Controller
{
    use \App\Traits\ResponseMessageTrait;

    protected $collection;
    protected $resource;
    protected $request;
    protected $model;
    protected $fillable;
    protected $searchable_columns = [];
    protected $with = [];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = json_decode(request()->filters);
        $sortBy  = json_decode(request()->sort);
        $entries = $this->model->SearchColumnLike(
            $this->searchable_columns,
            request()->search
        )
            ->when(
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
                        $relationship = in_array($sort->id, $this->model->getRelationship());
                        $appends      = in_array($sort->id, $this->model->getAppends());

                        if ($relationship) {
                            $model_name    = \ucwords($sort->id);
                            $related_model = "\\App\\Models\\{$model_name}";
                            $model         = new $related_model();
                            $column        = $model->select(['name'])->whereColumn("{$this->model->getTable()}.{$sort->id}_id", "{$sort->id}s.id")->take(1);
                            $query->orderBy($column, $sort->desc ? 'desc' : 'asc');
                        } elseif (!$relationship && !$appends) {
                            $column = $sort->id;
                            $query->orderBy($column, $sort->desc ? 'desc' : 'asc');
                        }
                    }
                },
                fn ($query) => $query->orderBy($this->searchable_columns[0], 'asc')
            )
            ->with($this->with)
            ->paginate(request()->per_page ?? config('base.pagination'));

        return new $this->collection($entries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $request = app($this->request);
        $entry = $this->model->create($request->validated());
        $entry->load($this->with);

        return new $this->resource($entry);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(string $id)
    {
        $entry = $this->model->findOrFail($id);
        $entry->load($this->with);

        return new $this->resource($entry);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $entry = $this->model->findOrFail($id);

        $request = app($this->request);

        $entry->update($request->validated());
        $entry->load($this->with);

        return new $this->resource($entry);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $ids)
    {
        $this->model->whereIn('id', json_decode($ids, true))->delete();

        return $this->success($ids, null, HttpCode::SUCCESS);
    }
}
