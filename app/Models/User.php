<?php

namespace App\Models;

use App\Enums\RoleEnum;

class User extends Model
{
    protected static array $hidden = ['remember_token'];

    protected static array $appends = ['department_name'];

    public function scopeEmployee($query)
    {
        return $query->where('role', RoleEnum::EMPLOYEE->value);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', RoleEnum::ADMIN->value);
    }

    public function getDepartmentNameAttribute()
    {
        return Department::find($this->department_id)->name ?? null;
    }

    public function isEmployee()
    {
        return $this->role === RoleEnum::EMPLOYEE->value;
    }

    public function isManager()
    {
        return $this->role === RoleEnum::MANAGER->value;
    }

    public function isAdmin()
    {
        return $this->role === RoleEnum::ADMIN->value;
    }

    public function isNotEmployee()
    {
        return !$this->isEmployee();
    }
}
