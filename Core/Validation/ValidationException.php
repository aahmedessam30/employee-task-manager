<?php

namespace Core\Validation;

use RuntimeException;

class ValidationException extends RuntimeException
{
    protected Validator $validator;
    protected array $errors;

    public function __construct(Validator $validator)
    {
        parent::__construct('The given data was invalid.');

        $this->validator = $validator;
        $this->errors    = $validator->errors();

        if (request()->expectsJson()) {
            throw $this;
        }

        back()->withInput()->withErrors($this->errors)->send();
    }

    public function validator(): Validator
    {
        return $this->validator;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
