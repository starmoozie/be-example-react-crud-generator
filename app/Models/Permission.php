<?php

namespace App\Models;

use App\Constants\Permission as Position;

class Permission extends BaseModel
{
    protected $fillable = [
        'name',
        'key',
        'position',
        'method',
        'type'
    ];

    public $increment = true;

    public function getPositionAttribute($value)
    {
        return Position::POSITION[$value];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = \ucwords($value);
    }

    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = \strtolower($value);
    }
}
