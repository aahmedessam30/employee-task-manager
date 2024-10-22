<?php

namespace Core\Database;

class Column
{
    protected string $name;
    protected string $type;
    protected bool $nullable = false;
    protected $default = null;
    protected bool $unique = false;
    protected string $table;
    protected ?string $constraint = null;
    protected array $foreignKeyActions = [];
    protected bool $autoIncrement = false;
    protected bool $primaryKey = false;

    public function __construct(string $name, string $type, string $table)
    {
        $this->name = $name;
        $this->type = $type;
        $this->table = $table;
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

    public function autoIncrement()
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function primary()
    {
        $this->primaryKey = true;
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

    public function virtualAs($expression)
    {
        $this->type .= " GENERATED ALWAYS AS ({$expression}) VIRTUAL";
        return $this;
    }

    public function references(string $column)
    {
        $this->type .= " REFERENCES {$column}";
        return $this;
    }

    public function on(string $table)
    {
        $this->type .= " ON {$table}";
        return $this;
    }

    public function constrained(string $table = null, string $column = 'id')
    {
        $table                     = $table ?? str_replace('_id', 's', $this->name);
        $this->constraint          = "{$this->table}_{$this->name}_foreign";
        $this->foreignKeyActions[] = "CONSTRAINT {$this->constraint} FOREIGN KEY ({$this->name}) REFERENCES {$table}({$column})";
        return $this;
    }

    public function cascadeOnDelete()
    {
        $this->foreignKeyActions[] = "ON DELETE CASCADE";
        return $this;
    }

    public function cascadeOnUpdate()
    {
        $this->foreignKeyActions[] = "ON UPDATE CASCADE";
        return $this;
    }

    public function onDelete(string $action)
    {
        $this->foreignKeyActions[] = "ON DELETE {$action}";
        return $this;
    }

    public function onUpdate(string $action)
    {
        $this->foreignKeyActions[] = "ON UPDATE {$action}";
        return $this;
    }

    public function nullOnDelete()
    {
        $this->foreignKeyActions[] = "ON DELETE SET NULL";
        return $this;
    }

    public function getDefinition(): string
    {
        $definition = "{$this->name} {$this->type}";

        if ($this->autoIncrement) {
            $definition .= ' AUTO_INCREMENT';
        }

        if ($this->primaryKey) {
            $definition .= ' PRIMARY KEY';
        }

        if (!$this->nullable) {
            $definition .= ' NOT NULL';
        }

        if ($this->default !== null) {
            $definition .= ' DEFAULT ' . $this->quoteDefault($this->default);
        }

        if ($this->unique) {
            $definition .= ' UNIQUE';
        }

        if (!empty($this->foreignKeyActions)) {
            $definition .= ', ' . implode(' ', $this->foreignKeyActions);
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
