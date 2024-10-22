<?php

namespace Database\seeders;

class DepartmentSeeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'IT', 'description' => 'Information Technology'],
            ['name' => 'HR', 'description' => 'Human Resources'],
            ['name' => 'Finance', 'description' => 'Finance Department'],
            ['name' => 'Marketing', 'description' => 'Marketing Department'],
            ['name' => 'Sales', 'description' => 'Sales Department'],
        ];

        foreach ($departments as $department) {
            \App\Models\Department::create($department);
        }
    }
}
