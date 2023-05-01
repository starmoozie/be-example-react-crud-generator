<?php

namespace App\Models;

class Customer extends BaseModel
{
    protected $fillable = [
        'name',
        'phone'
    ];

    public function sale()
    {
        return $this->hasMany(Sale::class);
    }
}
