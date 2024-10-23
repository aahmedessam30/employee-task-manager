<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Requests\Department\StoreDepartmentRequest;
use App\Requests\Department\UpdateDepartmentRequest;
use Core\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::when($request->filled('search'), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query
                    ->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->join('users', 'departments.id', '=', 'users.department_id')
                            ->join('employees', function ($join) use ($request) {
                                $join->on('users.id', '=', 'employees.user_id')
                                    ->whereBetween('salary', [0, $request->search]);
                            });
                    });
            });
        })
          ->latest('id')->paginate(20);

        return view('departments.index', compact('departments'));
    }

    public function show($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return back()->with('error', 'Department not found');
        }

        return view('departments.show', compact('department'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        try {
            Department::create($request->validated());
            return redirect(route('departments.index'))->with('success', 'Department created successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

     public function edit($id) {
        $department = Department::find($id);

        if (!$department) {
            return back()->with('error', 'Department not found');
        }

        return view('departments.edit', compact('department'));
     }

    public function update(UpdateDepartmentRequest $request, $id)
    {
        try {
            $department = Department::find($id);

            if (!$department) {
                return back()->with('error', 'Department not found');
            }

            $department->update($request->validated());

            return redirect(route('departments.index'))->with('success', 'Department updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $department = Department::find($id);

            if (!$department) {
                return back()->with('error', 'Department not found');
            }

            if (User::where('department_id', $department->id)->exists()) {
                return back()->with('error', 'Department has employees');
            }

            $department->delete();

            return redirect(route('departments.index'))->with('success', 'Department deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
