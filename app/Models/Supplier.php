<?php

namespace App\Models;

class Supplier extends BaseModel
{
    protected $fillable = [
        'name',
        'phone'
    ];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
