<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Department;
use App\Models\User;
use App\Requests\User\{StoreUserRequest, UpdateUserRequest};

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::employee()->latest()->paginate(20);

        return view('employees.index', compact('employees'));
    }

    public function show($id)
    {
        $employee = User::find($id);

        if (!$employee) {
            return back()->with('error', 'User not found');
        }

        return view('employees.show', compact('employee'));
    }

    public function create()
    {
        $departments = Department::all();

        return view('employees.create', compact('departments'));
    }

    public function store(StoreUserRequest $request)
    {
        $data                   = $request->validated();
        $data['password']       = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['remember_token'] = bin2hex(random_bytes(32));
        $data['role']           = RoleEnum::EMPLOYEE->value;

        User::create($data);

        return redirect(route('employees.index'))->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $employee    = User::find($id);
        $departments = Department::all();

        if (!$employee) {
            return back()->with('error', 'User not found');
        }

        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $employee = User::find($id);

        if (!$employee) {
            return back()->with('error', 'User not found');
        }

        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $employee->update($data);

        return redirect(route('employees.index'))->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $employee = User::find($id);

        if (!$employee) {
            return back()->with('error', 'User not found');
        }

        $employee->delete();

        return redirect(route('employees.index'))->with('success', 'User deleted successfully');
    }
}
