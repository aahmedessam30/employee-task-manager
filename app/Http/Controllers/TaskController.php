<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Requests\Task\{StoreTaskRequest, UpdateTaskRequest};

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::createdByMe()->latest()->paginate();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $employees = User::employee()->get();

        return view('tasks.create', compact('employees'));
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            $data               = $request->validated();
            $data['created_by'] = auth()->id();

            Task::create($data);

            return redirect(route('tasks.index'))->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create task.');
        }
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return back()->with('error', 'Task not found.');
        }

        return view('tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return back()->with('error', 'Task not found.');
        }

        $employees = User::employee()->get();

        return view('tasks.edit', compact('task', 'employees'));
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return back()->with('error', 'Task not found.');
        }

        try {
            $task->update($request->validated());

            return redirect(route('tasks.index'))->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update task.');
        }
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return back()->with('error', 'Task not found.');
        }

        try {
            $task->delete();

            return redirect(route('tasks.index'))->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete task.');
        }
    }
}
