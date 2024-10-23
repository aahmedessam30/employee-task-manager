<?php

namespace App\Models;

class Department extends Model
{
    protected static string $table = 'departments';
    protected static array $appends = ['employees_count', 'employees_salary'];

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function getEmployeesCountAttribute()
    {
        return User::where('department_id', $this->id)->count();
    }

    public function getEmployeesSalaryAttribute()
    {
        return Employee::query()
                ->join('users', fn($join) => $join->on('employees.user_id', '=', 'users.id')->where('users.department_id', $this->id))
                ->sum('salary') ?? 0;
    }
}
