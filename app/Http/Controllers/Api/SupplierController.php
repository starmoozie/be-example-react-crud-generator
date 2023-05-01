<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SupplierRequest as Request;
use App\Http\Resources\Supplier\Collection;
use App\Http\Resources\Supplier\Resource;
use App\Models\Supplier as Model;

class SupplierController extends BaseController
{
    protected $collection = Collection::class;
    protected $resource = Resource::class;
    protected $request = Request::class;
    protected $model;
    protected $fillable;
    protected $searchable_columns = ['name'];
    protected $with = ['product'];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->fillable = $model->getFillable();
    }
}
