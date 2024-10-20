<?php

namespace Core\Validation\Concerns;

trait ValidatesAttributes
{
    protected function validateRequired(string $field, array $parameters): void
    {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            $this->addError($field, 'The :attribute field is required.');
        }
    }

    protected function validateString(string $field, array $parameters): void
    {
        if (!is_string($this->data[$field] ?? null)) {
            $this->addError($field, 'The :attribute field must be a string.');
        }
    }

    protected function validateEmail(string $field, array $parameters): void
    {
        if (!filter_var($this->data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'The :attribute field must be a valid email address.');
        }
    }

    protected function validateMin(string $field, array $parameters): void
    {
        $length = is_string($this->data[$field] ?? null) ? mb_strlen($this->data[$field]) : count($this->data[$field] ?? []);
        if ($length < $parameters[0]) {
            $this->addError($field, "The :attribute field must be at least {$parameters[0]} characters.");
        }
    }

    protected function validateMax(string $field, array $parameters): void
    {
        $length = is_string($this->data[$field] ?? null) ? mb_strlen($this->data[$field]) : count($this->data[$field] ?? []);
        if ($length > $parameters[0]) {
            $this->addError($field, "The :attribute field may not be greater than {$parameters[0]} characters.");
        }
    }

    protected function validateUnique(string $field, array $parameters): void
    {
        $this->addError($field, 'The :attribute has already been taken.');
    }

    protected function validateConfirmed(string $field, array $parameters): void
    {
        if ($this->data[$field] !== ($this->data[$field . '_confirmation'] ?? null)) {
            $this->addError($field, 'The :attribute confirmation does not match.');
        }
    }

    protected function validateArray(string $field, array $parameters): void
    {
        if (!is_array($this->data[$field] ?? null)) {
            $this->addError($field, 'The :attribute field must be an array.');
        }
    }
}
