<?php

namespace Core\Database;

class Blueprint
{
    protected string $table;
    protected string $engine = 'InnoDB';
    protected string $charset = 'utf8mb4';
    protected string $collation = 'utf8mb4_general_ci';
    protected array $columns = [];
    protected array $indexes = [];
    protected array $foreignKeys = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function __toString(): string
    {
        $columnDefinitions = array_map(fn ($column) => $column->getDefinition(), $this->columns);

        return implode(', ', array_merge($columnDefinitions, $this->indexes, $this->foreignKeys));
    }

    public function engine(string $engine)
    {
        $this->engine = $engine;
        return $this;
    }

    public function charset(string $charset)
    {
        $this->charset = $charset;
        return $this;
    }

    public function collation(string $collation)
    {
        $this->collation = $collation;
        return $this;
    }

    protected function addColumn(string $name, string $type, $table = null)
    {
        $this->columns[] = new Column($name, $type, $table ?? $this->table);
        return end($this->columns);
    }

    public function increments(string $column = 'id')
    {
        return $this->addColumn($column, 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY');
    }

    public function id($column = 'id')
    {
        return $this->unsignedBigInteger($column)->autoIncrement()->primary();
    }

    public function string(string $column, int $length = 255)
    {
        return $this->addColumn($column, "VARCHAR($length)");
    }

    public function char(string $column, int $length = 255)
    {
        return $this->addColumn($column, "CHAR($length)");
    }

    public function decimal(string $column, int $precision = 8, int $scale = 2)
    {
        return $this->addColumn($column, "DECIMAL($precision, $scale)");
    }

    public function double(string $column, int $precision = 8, int $scale = 2)
    {
        return $this->addColumn($column, "DOUBLE($precision, $scale)");
    }

    public function float(string $column)
    {
        return $this->addColumn($column, 'FLOAT');
    }

    public function boolean(string $column)
    {
        return $this->addColumn($column, 'BOOLEAN');
    }

    public function tinyInteger(string $column)
    {
        return $this->addColumn($column, 'TINYINT');
    }

    public function integer(string $column)
    {
        return $this->addColumn($column, 'INT');
    }

    public function bigInteger(string $column)
    {
        return $this->addColumn($column, 'BIGINT');
    }

    public function unsignedTinyInteger(string $column)
    {
        return $this->addColumn($column, 'TINYINT UNSIGNED');
    }

    public function unsignedInteger(string $column)
    {
        return $this->addColumn($column, 'INT UNSIGNED');
    }

    public function unsignedBigInteger(string $column)
    {
        return $this->addColumn($column, 'BIGINT UNSIGNED');
    }

    public function date(string $column)
    {
        return $this->addColumn($column, 'DATE');
    }

    public function time(string $column)
    {
        return $this->addColumn($column, 'TIME');
    }

    public function dateTime(string $column)
    {
        return $this->addColumn($column, 'DATETIME');
    }

    public function timestamp(string $column)
    {
        return $this->addColumn($column, 'TIMESTAMP');
    }

    public function binary(string $column)
    {
        return $this->addColumn($column, 'BINARY');
    }

    public function json(string $column)
    {
        return $this->addColumn($column, 'JSON');
    }

    public function longText(string $column)
    {
        return $this->addColumn($column, 'LONGTEXT');
    }

    public function text(string $column)
    {
        return $this->addColumn($column, 'TEXT');
    }

    public function mediumText(string $column)
    {
        return $this->addColumn($column, 'MEDIUMTEXT');
    }

    public function tinyText(string $column)
    {
        return $this->addColumn($column, 'TINYTEXT');
    }

    public function morphs(string $column)
    {
        $this->unsignedBigInteger("{$column}_id");
        $this->string("{$column}_type");
        return $this;
    }

    public function rememberToken()
    {
        return $this->addColumn('remember_token', 'VARCHAR(100)');
    }

    public function foreignId(string $column)
    {
        return $this->unsignedBigInteger($column);
    }

    public function foreign(string $column, string $table, string $foreignColumn = 'id')
    {
        $constraintName = "{$this->table}_{$column}_fk";
        $this->foreignKeys[] = "FOREIGN KEY ($column) REFERENCES $table($foreignColumn) ON DELETE CASCADE ON UPDATE CASCADE CONSTRAINT $constraintName";
        return $this;
    }

    public function index(string $column)
    {
        $indexName = "{$this->table}_{$column}_index";
        $this->indexes[] = "INDEX $indexName ($column)";
        return $this;
    }

    public function unique(string $column)
    {
        $indexName = "{$this->table}_{$column}_unique";
        $this->indexes[] = "UNIQUE $indexName ($column)";
        return $this;
    }

    public function default(string $column, $value)
    {
        $this->columns[] = "$column DEFAULT " . $this->quote($value);
        return $this;
    }

    public function timestamps()
    {
        $this->addColumn('created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->addColumn('updated_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    public function softDeletes()
    {
        $this->addColumn('deleted_at', 'TIMESTAMP NULL');
    }

    public function dropColumn(string $column)
    {
        $this->columns[] = "DROP COLUMN $column";
        return $this;
    }

    public function dropIndex(string $indexName)
    {
        $this->indexes[] = "DROP INDEX $indexName";
        return $this;
    }

    public function dropForeignKey(string $constraintName)
    {
        $this->foreignKeys[] = "DROP FOREIGN KEY $constraintName";
        return $this;
    }

    protected function quote($value): string
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
