<?php

namespace App\Models;

class User extends Model
{
    protected static array $hidden = ['remember_token'];

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }
}
