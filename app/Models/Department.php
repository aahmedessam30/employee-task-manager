<?php

namespace App\Models;

class Department extends Model
{
    protected static string $table = 'departments';

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }
}
