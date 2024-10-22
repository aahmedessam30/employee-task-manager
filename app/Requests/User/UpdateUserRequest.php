<?php

namespace App\Requests\User;

use Core\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->route('id') . ',id'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
        ];
    }
}
