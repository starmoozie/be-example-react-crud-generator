<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ProductCategoryRequest as Request;
use App\Http\Resources\ProductCategory\Collection;
use App\Http\Resources\ProductCategory\Resource;
use App\Models\ProductCategory as Model;

class ProductCategoryController extends BaseController
{
    protected $collection = Collection::class;
    protected $resource = Resource::class;
    protected $request = Request::class;
    protected $model;
    protected $fillable;
    protected $searchable_columns = ['name'];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->fillable = $model->getFillable();
    }
}
