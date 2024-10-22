<?php

namespace App\Requests\Task;

use Core\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority'    => ['required', 'string', 'in:low,medium,high'],
            'due_date'    => ['required', 'date', 'after_or_equal:today'],
            'assigned_to' => ['required', 'numeric', 'exists:users,id'],
        ];
    }
}
