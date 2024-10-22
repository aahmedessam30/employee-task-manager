<?php

namespace App\Models;

class Department extends Model
{
    protected static string $table = 'departments';
    protected static array $appends = ['employees_count'];

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function getEmployeesCountAttribute()
    {
        return db()->table('employees')->where('department_id', $this->id)->count();
    }
}
