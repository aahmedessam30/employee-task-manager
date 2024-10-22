<?php

namespace App\Requests\Department;

use Core\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }
}
