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

    protected function validateDate(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format('Y-m-d', $date);

        if (!$date) {
            $this->addError($field, 'The :attribute field must be a valid date.');
        }
    }

    protected function validateDatetime(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format('Y-m-d H:i:s', $date);

        if (!$date) {
            $this->addError($field, 'The :attribute field must be a valid date and time.');
        }
    }

    protected function validateTime(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format('H:i:s', $date);

        if (!$date) {
            $this->addError($field, 'The :attribute field must be a valid time.');
        }
    }

    protected function validateBefore(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format('Y-m-d', $date);
        $before = date_create_from_format('Y-m-d', $parameters[0]);

        if ($date >= $before) {
            $this->addError($field, 'The :attribute field must be a date before :date.');
        }
    }

    protected function validateAfter(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format('Y-m-d', $date);
        $after = date_create_from_format('Y-m-d', $parameters[0]);

        if ($date <= $after) {
            $this->addError($field, 'The :attribute field must be a date after :date.');
        }
    }

    protected function validateBeforeOrEqual(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format('Y-m-d', $date);
        $before = date_create_from_format('Y-m-d', $parameters[0]);

        if ($date > $before) {
            $this->addError($field, 'The :attribute field must be a date before or equal to :date.');
        }
    }

    protected function validateAfterOrEqual(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format('Y-m-d', $date);
        $after = $parameters[0] === 'today' ? date_create('today') : date_create_from_format('Y-m-d', $parameters[0]);

        if ($date < $after) {
            $this->addError($field, 'The :attribute field must be a date after or equal to ' . $parameters[0] . '.');
        }
    }

    protected function validateDateEquals(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format('Y-m-d', $date);
        $equals = date_create_from_format('Y-m-d', $parameters[0]);

        if ($date != $equals) {
            $this->addError($field, 'The :attribute field must be a date equal to :date.');
        }
    }

    protected function validateDateFormat(string $field, array $parameters): void
    {
        $date = $this->data[$field] ?? null;
        $date = date_create_from_format($parameters[0], $date);

        if (!$date) {
            $this->addError($field, 'The :attribute field must be a date with format :format.');
        }
    }

    protected function validateDifferent(string $field, array $parameters): void
    {
        $otherField = $parameters[0];

        if ($this->data[$field] === $this->data[$otherField]) {
            $this->addError($field, 'The :attribute and :other must be different.');
        }
    }

    protected function validateSame(string $field, array $parameters): void
    {
        $otherField = $parameters[0];

        if ($this->data[$field] !== $this->data[$otherField]) {
            $this->addError($field, 'The :attribute and :other must match.');
        }
    }
}
