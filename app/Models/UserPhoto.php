<?php

namespace App\Models;

class UserPhoto extends BaseModel
{
    protected $fillable = [
        'user_id',
        'active',
        'location'
    ];
}
