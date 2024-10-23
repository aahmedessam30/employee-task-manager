<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Department;
use App\Models\Employee;
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
        $db = db();

        try {
            $db->beginTransaction();

            $data                   = $request->except('salary', 'image', 'password_confirmation');
            $data['password']       = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['remember_token'] = bin2hex(random_bytes(32));
            $data['role']           = RoleEnum::EMPLOYEE->value;
            $data['image']          = upload_image($request->file('image'), 'employees');
            $user                   = User::create($data);

            Employee::create(['user_id' => $user->id, 'salary'  => $request->salary]);

            $db->commit();
            return redirect(route('employees.index'))->with('success', 'User created successfully');
        } catch (\Exception $e) {
            $db->rollBack();
            return back()->with('error', $e->getMessage());
        }
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
        try {
            $db = db();
            $db->beginTransaction();

            $user = User::find($id);

            if (!$user) {
                return back()->with('error', 'Employee not found');
            }

            $data = $request->except('salary', 'image', 'password_confirmation');

            if ($request->filled('password')) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $user->update($data);

            $employee = Employee::where('user_id', $user->id)->first();

            if (!$employee) {
                return back()->with('error', 'Employee not found');
            }

            $employeeData = $request->only('salary', 'image');

            if ($request->hasFile('image')) {
                $employeeData['image'] = upload_image($request->file('image'), 'employees');
            }

            $employee->update($employeeData);

            $db->commit();
            return redirect(route('employees.index'))->with('success', 'Employee updated successfully');
        } catch (\Exception $e) {
            $db->rollBack();
            return back()->with('error', "Error updating employee");
        }
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
