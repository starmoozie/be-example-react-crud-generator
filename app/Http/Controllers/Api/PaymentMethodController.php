<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PaymentMethodRequest as Request;
use App\Http\Resources\PaymentMethod\Collection;
use App\Http\Resources\PaymentMethod\Resource;
use App\Models\PaymentMethod as Model;

class PaymentMethodController extends BaseController
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
