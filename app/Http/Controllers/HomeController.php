<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Models\{Department, Employee, Task, User};

class HomeController extends Controller
{
    public function dashboard()
    {
        $departmentsCount    = Department::count();
        $employeesCount      = User::employee()->count();
        $tasksCount          = Task::count();
        $completedTasksCount = Task::where('status', TaskStatusEnum::COMPLETED->value)->count();

        return view('dashboard', compact('departmentsCount', 'employeesCount', 'tasksCount', 'completedTasksCount'));
    }
}
