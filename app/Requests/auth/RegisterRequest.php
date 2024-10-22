<?php

namespace App\Requests\auth;

use Core\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'role'          => ['required', 'string', 'in:admin,employee'],
            'department_id' => ['required_if:role,employee', 'nullable', 'numeric', 'exists:departments,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('role') !== 'employee') {
            $this->merge(['department_id' => null]);
        }
    }
}
