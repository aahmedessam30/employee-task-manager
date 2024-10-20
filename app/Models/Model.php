<?php

namespace App\Models;

use Core\Support\Model as BaseModel;

abstract class Model extends BaseModel
{
    protected static array $hidden = [];
    protected static array $appends = [];
    protected static array $casts = [];

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }
}
