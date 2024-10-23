<?php

namespace App\Requests\Auth;

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
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
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
