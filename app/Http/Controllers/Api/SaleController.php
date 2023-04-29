<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SaleRequest as Request;
use App\Http\Resources\Sale\Collection;
use App\Http\Resources\Sale\Resource;
use App\Models\Sale as Model;

class SaleController extends BaseController
{
    protected $collection = Collection::class;
    protected $resource = Resource::class;
    protected $request = Request::class;
    protected $model;
    protected $fillable;
    protected $searchable_columns = ['date'];
    protected $with = ['customer', 'paymentMethod'];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->fillable = $model->getFillable();
    }
}
