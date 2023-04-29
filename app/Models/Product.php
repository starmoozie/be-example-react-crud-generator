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
        'product_category_id'
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
