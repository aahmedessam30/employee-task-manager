<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Requests\Department\StoreDepartmentRequest;
use App\Requests\Department\UpdateDepartmentRequest;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest('id')->paginate(20);

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

            $department->delete();

            return redirect(route('departments.index'))->with('success', 'Department deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
