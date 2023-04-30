<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'buy_price',
        'supplier_id',
        'product_category_id',
        'is_sold'
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopeFilterBoolean($query, $filter)
    {
        $column = $filter->id;
        $value = filter_var($filter->value, FILTER_VALIDATE_BOOLEAN);

        return $query->where($column, $value);
    }

    public function scopeInStock($query)
    {
        return $query->where('is_sold', false);
    }
}
