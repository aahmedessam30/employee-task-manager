<?php

namespace App\Models;

class User extends Model
{
    protected static array $hidden = ['password', 'remember_token'];

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }
}
