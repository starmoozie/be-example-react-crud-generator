<?php

namespace App\Models;

use App\Constants\Permission as Position;

class Permission extends BaseModel
{
    protected $fillable = [
        'name',
        'position'
    ];

    public function getPositionAttribute($value)
    {
        return Position::POSITION[$value];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = \ucwords($value);
    }
}
