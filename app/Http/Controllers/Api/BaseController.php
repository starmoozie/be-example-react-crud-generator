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

        $entries = $this->model
            ->tableActivity($filters, $sortBy, $this->searchable_columns)
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
        $requestValidated = $request->validated();

        // Identity bulk insert
        if (count($requestValidated) === 1 && $request->items) {
            return $this->bulkInsert($request->items);
        } else {
            return $this->singleInsert($requestValidated);
        }
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

    /**
     * Handle bulk insert operation
     */
    protected function bulkInsert($items)
    {
        foreach ($items as $item) {
            $entry = $this->model->create($item);
            $ids[] = $entry->id;
        }

        return new $this->collection(
            $this->model->whereIn('id', $ids)->with($this->with)->get()
        );
    }

    /**
     * Handle single insert operation
     */
    protected function singleInsert($requestValidated)
    {
        $entry = $this->model->create($requestValidated);
        $entry->load($this->with);

        return new $this->resource($entry);
    }
}
