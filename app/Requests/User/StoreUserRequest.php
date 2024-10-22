<?php

namespace App\Requests\User;

use Core\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
            'salary'        => ['required', 'numeric'],
            'image'         => ['nullable', 'image'],
        ];
    }
}
