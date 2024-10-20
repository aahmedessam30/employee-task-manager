<?php

namespace Core\Http;

use Core\Validation\ValidationException;
use Core\Validation\Validator;

abstract class FormRequest extends Request
{
    protected array $rules = [];
    protected array $messages = [];
    protected array $attributes = [];
    protected ?Validator $validator = null;

    public function __construct(Request $request)
    {
        parent::__construct();

        $this->prepareForValidation();
    }

    abstract public function authorize(): bool;

    abstract public function rules(): array;

    public function messages(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [];
    }

    protected function prepareForValidation(): void
    {
        //
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function validateResolved(): void
    {
        if (!$this->authorize()) {
            throw new \Exception('This action is unauthorized.');
        }

        $this->validator = new Validator(
            $this->all(),
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        if ($this->validator->fails()) {
            $this->failedValidation($this->validator);
        }
    }

    /**
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator);
    }

    public function validated(): array
    {
        return $this->validator->validated();
    }

    public function safe(): array
    {
        return $this->validator->validated();
    }
}
