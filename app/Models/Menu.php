<?php

namespace App\Models;

class Menu extends BaseModel
{
    protected $fillable = [
        'name',
        'path',
        'permission'
    ];

    protected $casts = ['permission' => 'array'];

    public $increment = true;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = \ucwords($value);
    }

    public function setPathAttribute($value)
    {
        $this->attributes['path'] = strtolower($value);
    }
}
