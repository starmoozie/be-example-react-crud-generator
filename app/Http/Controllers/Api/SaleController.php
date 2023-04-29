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
                        switch ($filter->id) {

                                // case 'unpaid':
                                //     $query->filterRefundOrUnpaid($filter);
                                //     break;

                                // case 'refund':
                                //     $query->filterRefundOrUnpaid($filter);
                                //     break;

                            default:
                                $query->filterAnyColumns($filter, $this->with);
                                break;
                        }
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
}
