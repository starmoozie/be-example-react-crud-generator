<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CustomerRequest as Request;
use App\Http\Resources\Customer\Collection;
use App\Http\Resources\Customer\Resource;
use App\Models\Customer as Model;

class CustomerController extends BaseController
{
    protected $collection = Collection::class;
    protected $resource = Resource::class;
    protected $request = Request::class;
    protected $model;
    protected $fillable;
    protected $searchable_columns = ['name'];
    protected $with = ['sale'];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->fillable = $model->getFillable();
    }
}
