<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SaleRequest as Request;
use App\Http\Resources\Sale\Collection;
use App\Http\Resources\Sale\Resource;
use App\Models\Sale as Model;
use App\Models\Product;

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
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $request = app($this->request);
        $requestValidated = $request->validated();

        $this->updateProductStatus(
            collect($request->items)->pluck('product.id')->toArray(),
            true
        );

        $entry = $this->model->create($requestValidated);
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

        $this->handleOldNewItems($request, $entry);

        $entry->update($request->validated());
        $entry->load($this->with);

        return new $this->resource($entry);
    }

    /**
     * Handle old/new items is_sold or unsold
     */
    private function handleOldNewItems($request, $entry): void
    {
        $old_items = collect($entry->items)->pluck('product.id')->toArray();
        $new_items = collect($request->items)->pluck('product.id')->toArray();
        $diffs     = array_merge(array_diff($old_items, $new_items), array_diff($new_items, $old_items));

        foreach ($diffs as $diff) {
            if (\in_array($diff, $old_items)) {
                $unsoldIds[] = $diff;
            }

            if (\in_array($diff, $new_items)) {
                $soldIds[] = $diff;
            }
        }

        if (isset($unsoldIds)) {
            $this->updateProductStatus($unsoldIds, false);
        }

        if (isset($soldIds)) {
            $this->updateProductStatus($soldIds, true);
        }
    }

    /**
     * Update Product status
     */
    protected function updateProductStatus($ids, $status): void
    {
        Product::whereIn('id', $ids)->update(['is_sold' => $status]);
    }
}
