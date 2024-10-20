<?php

namespace Core\Validation;

use RuntimeException;

class ValidationException extends RuntimeException
{
    protected Validator $validator;
    protected array $errors;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
        $this->errors    = $validator->errors();
        parent::__construct('The given data was invalid.');
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
