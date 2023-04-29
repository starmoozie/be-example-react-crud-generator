<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ProductRequest as Request;
use App\Http\Resources\Product\Collection;
use App\Http\Resources\Product\Resource;
use App\Models\Product as Model;

class ProductController extends BaseController
{
    protected $collection = Collection::class;
    protected $resource = Resource::class;
    protected $request = Request::class;
    protected $model;
    protected $fillable;
    protected $searchable_columns = ['name'];
    protected $with = ['productCategory', 'supplier'];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->fillable = $model->getFillable();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $request = app($this->request);

        foreach ($request->items as $item) {
            $entry = $this->model->create($item);
            $ids[] = $entry->id;
        }

        return new $this->collection(
            $this->model->whereIn('id', $ids)->with($this->with)->get()
        );
    }
}
