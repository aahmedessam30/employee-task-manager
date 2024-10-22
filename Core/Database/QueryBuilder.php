<?php

namespace Core\Database;

use PDO;
use Core\Pagination\Paginator;
use PDOException;

class QueryBuilder
{
    protected PDO $connection;
    protected string $table;
    protected mixed $columns = ['*'];
    protected array $wheres = [];
    protected $limit;
    protected $offset;
    protected array $orders = [];
    protected array $groups = [];
    protected array $joins = [];
    protected array $bindings = [];
    protected ?string $model;
    protected bool $inTransaction = false;

    public function __construct(PDO $connection, string $model = null)
    {
        $this->connection = $connection;
        $this->model      = $model;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function select($columns = ['*'])
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function where($column, $operator = '=', $value = null)
    {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orders[] = "$column $direction";
        return $this;
    }

    public function groupBy($columns)
    {
        $this->groups = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function join($table, $first, $operator, $second, $type = 'inner')
    {
        $this->joins[] = "$type join $table on $first $operator $second";
        return $this;
    }

    protected function compileSelect()
    {
        $sql = "SELECT " . implode(', ', $this->columns) . " FROM $this->table";

        if ($this->joins) {
            $sql .= " " . implode(' ', $this->joins);
        }

        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        if ($this->groups) {
            $sql .= " GROUP BY " . implode(', ', $this->groups);
        }

        if ($this->orders) {
            $sql .= " ORDER BY " . implode(', ', $this->orders);
        }

        if ($this->limit) {
            $sql .= " LIMIT $this->limit";
        }

        if ($this->offset) {
            $sql .= " OFFSET $this->offset";
        }

        return $sql;
    }

    public function get()
    {
        $sql = $this->compileSelect();
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->parseToModel($results);
    }

    public function first()
    {
        $this->limit(1);
        $result = $this->get();
        return $result ? $this->parseToModel([$result[0]])[0] : null;
    }

    public function insert(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_values($data));

        return $this->connection->lastInsertId();
    }

    public function update(array $data)
    {
        $columns = implode(' = ?, ', array_keys($data)) . ' = ?';

        $sql = "UPDATE $this->table SET $columns";

        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_merge(array_values($data), $this->bindings));

        return $stmt->rowCount();
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table";

        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->rowCount();
    }

    public function exists()
    {
        return $this->count() > 0;
    }

    public function paginate($perPage = 10, $page = 1)
    {
        $total = $this->count();
        $items = $this->limit($perPage)->offset(($page - 1) * $perPage)->get();

        return new Paginator($perPage, $page, $total, $items);
    }

    public function latest($column = 'created_at')
    {
        return $this->orderBy($column, 'desc');
    }

    public function count()
    {
        return $this->aggregate('COUNT', '*');
    }

    public function sum($column)
    {
        return $this->aggregate('SUM', $column);
    }

    public function avg($column)
    {
        return $this->aggregate('AVG', $column);
    }

    public function max($column)
    {
        return $this->aggregate('MAX', $column);
    }

    public function min($column)
    {
        return $this->aggregate('MIN', $column);
    }

    protected function aggregate($function, $column)
    {
        $sql = "SELECT $function($column) AS aggregate FROM $this->table";

        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->fetchColumn();
    }

    public function parseToModel($results)
    {
        if (!$this->model) {
            return $results;
        }

        return array_map(function ($result) {
            $instance = new $this->model;

            if ($result instanceof $this->model) {
                return $result;
            }

            $instance->prepare($result);

            return $instance;
        }, $results);
    }

    /**
     * Start a new database transaction
     * @return bool
     * @throws PDOException
     */
    public function beginTransaction(): bool
    {
        if ($this->inTransaction) {
            throw new PDOException('Transaction already in progress');
        }

        if ($this->connection->beginTransaction()) {
            $this->inTransaction = true;
            return true;
        }

        return false;
    }

    /**
     * Commit the active database transaction
     * @return bool
     * @throws PDOException
     */
    public function commit(): bool
    {
        if (!$this->inTransaction) {
            throw new PDOException('There is no active transaction');
        }

        if ($this->connection->commit()) {
            $this->inTransaction = false;
            return true;
        }

        return false;
    }

    /**
     * Rollback the active database transaction
     * @return bool
     * @throws PDOException
     */
    public function rollBack(): bool
    {
        if (!$this->inTransaction) {
            throw new PDOException('There is no active transaction');
        }

        if ($this->connection->rollBack()) {
            $this->inTransaction = false;
            return true;
        }

        return false;
    }

    /**
     * Check if there is an active transaction
     * @return bool
     */
    public function hasActiveTransaction(): bool
    {
        return $this->inTransaction;
    }

    /**
     * Execute a callback within a transaction
     * @param callable $callback
     * @return mixed
     * @throws \Throwable
     */
    public function transaction(callable $callback)
    {
        $this->beginTransaction();

        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Throwable $e) {
            $this->rollBack();
            throw $e;
        }
    }
}

