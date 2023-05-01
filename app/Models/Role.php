<?php

namespace App\Models;

class Role extends BaseModel
{
    protected $fillable = [
        'name',
        'menu'
    ];

    protected $casts = [
        'menu' => 'array'
    ];
}
