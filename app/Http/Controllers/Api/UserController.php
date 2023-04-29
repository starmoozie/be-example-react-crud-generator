<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest as Request;
use App\Http\Resources\User\Collection;
use App\Http\Resources\User\Resource;
use App\Models\User as Model;

class UserController extends BaseController
{
    protected $collection = Collection::class;
    protected $resource = Resource::class;
    protected $request = Request::class;
    protected $model;
    protected $fillable;
    protected $searchable_columns = ['name'];
    protected $with = ['photos'];

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->fillable = $model->getFillable();
    }
}
