<?php

namespace Core\Database;

class Column
{
    protected string $name;
    protected string $type;
    protected bool $nullable = false;
    protected $default = null;
    protected bool $unique = false;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function nullable()
    {
        $this->nullable = true;
        return $this;
    }

    public function notNullable()
    {
        $this->nullable = false;
        return $this;
    }

    public function primary()
    {
        $this->type .= ' PRIMARY KEY';
        return $this;
    }

    public function index()
    {
        $this->type .= ' INDEX';
        return $this;
    }

    public function default($value)
    {
        $this->default = $value;
        return $this;
    }

    public function unique()
    {
        $this->unique = true;
        return $this;
    }

    public function foreign(string $column)
    {
        $this->type .= " FOREIGN KEY ({$column})";
        return $this;
    }

    public function foriegnIdFor($model)
    {
        $this->type .= " FOREIGN KEY ({$model->getTable()}_id)";
        return $this;
    }

    public function references(string $column)
    {
        $this->type .= " REFERENCES {$column}";
        return $this;
    }

    public function constrainted(string $constraint = null)
    {
        $constraint  = $constraint ?? "{$this->name}_fk";
        $this->type .= " CONSTRAINT {$constraint}";
        return $this;
    }

    public function onDelete(string $action)
    {
        $this->type .= " ON DELETE {$action}";
        return $this;
    }

    public function onUpdate(string $action)
    {
        $this->type .= " ON UPDATE {$action}";
        return $this;
    }

    public function cascadeOnDelete()
    {
        $this->type .= " ON DELETE CASCADE";
        return $this;
    }

    public function cascadeOnUpdate()
    {
        $this->type .= " ON UPDATE CASCADE";
        return $this;
    }

    public function nullOnDelete()
    {
        $this->type .= " ON DELETE SET NULL";
        return $this;
    }

    public function getDefinition(): string
    {
        $definition = "{$this->name} {$this->type}";

        if (!$this->nullable) {
            $definition .= ' NOT NULL';
        }

        if ($this->default !== null) {
            $definition .= ' DEFAULT ' . $this->quoteDefault($this->default);
        }

        if ($this->unique) {
            $definition .= ' UNIQUE';
        }

        return $definition;
    }

    protected function quoteDefault($value): string
    {
        if (is_null($value)) {
            return 'NULL';
        }
        if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        return "'" . addslashes($value) . "'";
    }
}
