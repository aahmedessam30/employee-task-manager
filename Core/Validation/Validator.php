<?php

namespace Core\Validation;

use Core\Validation\Concerns\ValidatesAttributes;

class Validator
{
    use ValidatesAttributes;

    protected array $data;
    protected array $rules;
    protected array $messages;
    protected array $attributes;
    protected array $errors = [];

    public function __construct(array $data, array $rules, array $messages = [], array $attributes = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->attributes = $attributes;
    }

    /**
     * @throws ValidationException
     */
    public function validate(): array
    {
        $this->errors = [];

        foreach ($this->rules as $field => $rule) {
            $this->validateField($field, is_array($rule) ? $rule : explode('|', $rule));
        }

        if (!empty($this->errors)) {
            throw new ValidationException($this);
        }

        return $this->validated();
    }

    protected function validateField(string $field, array $rules): void
    {
        if (in_array('nullable', $rules)) {
            $this->validateNullable($field, []);
            if (!array_key_exists($field, $this->data)) {
                return;
            }
        }

        foreach ($rules as $rule) {
            if ($rule !== 'nullable') {
                $this->validateRule($field, $rule);
            }
        }
    }

    protected function validateRule(string $field, string $rule): void
    {
        $parameters = [];

        if (str_contains($rule, ':')) {
            [$rule, $paramString] = explode(':', $rule, 2);
            $parameters           = explode(',', $paramString);
        }

        $rule = str_replace(' ', '', ucwords(str_replace('_', ' ', $rule)));

        $method = 'validate' . ucfirst($rule);

        if (method_exists($this, $method)) {
            $this->{$method}($field, $parameters);
        } else {
            throw new \InvalidArgumentException("The $rule validation rule is not supported.");
        }
    }

    public function fails(): bool
    {
        try {
            $this->validate();
        } catch (ValidationException $e) {
            return true;
        }

        return false;
    }

    public function validated(): array
    {
        return array_intersect_key($this->data, $this->rules);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $this->formatMessage($field, $message);
    }

    protected function formatMessage(string $field, string $message): string
    {
        $customMessage = $this->messages[$field] ?? $this->messages[$message] ?? null;

        if ($customMessage) {
            $message = $customMessage;
        }

        return str_replace(
            [':attribute', ':field'],
            [$this->attributes[$field] ?? $field, $field],
            $message
        );
    }
}
