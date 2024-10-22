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
        $table  = $parameters[0];
        $column = $parameters[1] ?? $field;

        if (db()->table($table)->where($column, $this->data[$field])->exists()) {
            $this->addError($field, 'The :attribute has already been taken.');
        }
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

    protected function validateInteger(string $field, array $parameters): void
    {
        if (!is_int($this->data[$field] ?? null)) {
            $this->addError($field, 'The :attribute field must be an integer.');
        }
    }

    protected function validateNumeric(string $field, array $parameters): void
    {
        if (!is_numeric($this->data[$field] ?? null)) {
            $this->addError($field, 'The :attribute field must be a number.');
        }
    }

    protected function validateExists(string $field, array $parameters): void
    {
        $table  = $parameters[0];
        $column = $parameters[1] ?? $field;

        if (!db()->table($table)->where($column, $this->data[$field])->exists()) {
            $this->addError($field, 'The selected :attribute is invalid.');
        }
    }

    protected function validateNullable(string $field, array $parameters): void
    {
        if (!array_key_exists($field, $this->data) || $this->data[$field] === null) {
            unset($this->data[$field]);
        }
    }

    protected function valuidateSometimes(string $field, array $parameters): void
    {
        if (array_key_exists($field, $this->data)) {
            return;
        }

        $this->data[$field] = null;
    }

    protected function validateRequiredIf(string $field, array $parameters): void
    {
        $otherField      = $parameters[0];
        $expectedValues  = array_slice($parameters, 1);
        $otherFieldValue = $this->data[$otherField] ?? null;

        if (in_array($otherFieldValue, $expectedValues, true)) {
            if (!isset($this->data[$field]) || $this->data[$field] === '') {
                $this->addError($field, 'The :attribute field is required when :other is :value.');
            }
        }
    }

    protected function validateIn(string $field, array $parameters): void
    {
        if (!in_array($this->data[$field] ?? null, $parameters, true)) {
            $this->addError($field, 'The selected :attribute is invalid.');
        }
    }
}
