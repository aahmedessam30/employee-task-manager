<?php

namespace Core\Database;

class JoinClause
{
    /**
     * The type of join being performed.
     *
     * @var string
     */
    protected $type;

    /**
     * The table the join clause is joining to.
     *
     * @var string
     */
    protected $table;

    /**
     * The conditions for the join clause.
     *
     * @var array
     */
    protected $clauses = [];

    /**
     * The bindings for the join conditions.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * Create a new join clause instance.
     *
     * @param string $type
     * @param string $table
     */
    public function __construct($type, $table)
    {
        $this->type = strtoupper($type);
        $this->table = $table;
    }

    /**
     * Add an "on" clause to the join.
     *
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @param string $boolean
     * @return $this
     */
    public function on($first, $operator = null, $second = null, $boolean = 'AND')
    {
        if ($second === null) {
            $second = $operator;
            $operator = '=';
        }

        $this->clauses[] = [
            'type'      => 'ON',
            'boolean'   => $boolean,
            'first'     => $first,
            'operator'  => $operator,
            'second'    => $second,
            'isBinding' => false
        ];

        return $this;
    }

    /**
     * Add an "or on" clause to the join.
     *
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return $this
     */
    public function orOn($first, $operator = null, $second = null)
    {
        return $this->on($first, $operator, $second, 'OR');
    }

    /**
     * Add a "where" clause to the join.
     *
     * @param string $first
     * @param string $operator
     * @param mixed $second
     * @param string $boolean
     * @return $this
     */
    public function where($first, $operator = null, $second = null, $boolean = 'AND')
    {
        if ($second === null) {
            $second   = $operator;
            $operator = '=';
        }

        $this->clauses[] = [
            'type'      => 'WHERE',
            'boolean'   => $boolean,
            'first'     => $first,
            'operator'  => $operator,
            'second'    => $second,
            'isBinding' => true
        ];

        if ($second !== null) {
            $this->bindings[] = $second;
        }

        return $this;
    }

    /**
     * Add an "or where" clause to the join.
     *
     * @param string $first
     * @param string $operator
     * @param mixed $second
     * @return $this
     */
    public function orWhere($first, $operator = null, $second = null)
    {
        return $this->where($first, $operator, $second, 'OR');
    }

    // whereBetween
    public function whereBetween($column, $values, $boolean = 'AND', $not = false)
    {
        $type = $not ? 'NOT BETWEEN' : 'BETWEEN';

        $this->clauses[] = [
            'type'      => 'WHERE',
            'boolean'   => $boolean,
            'first'     => $column,
            'operator'  => $type,
            'second'    => [$values[0], $values[1]],
            'isBinding' => true
        ];

        $this->bindings[] = $values[0];
        $this->bindings[] = $values[1];

        return $this;
    }

    /**
     * Get the bindings for the join clause.
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * Convert the join clause to its string representation.
     *
     * @return string
     */
    public function toSql()
    {
        $sql = [$this->type . ' JOIN ' . $this->table];
        $conditions = [];

        foreach ($this->clauses as $index => $clause) {
            $condition = '';

            // Don't add boolean for first clause
            if ($index > 0) {
                $condition .= $clause['boolean'] . ' ';
            }

            if ($clause['type'] === 'ON') {
                $condition .= "{$clause['first']} {$clause['operator']} {$clause['second']}";
            } else {
                $condition .= "{$clause['first']} {$clause['operator']} ?";
            }

            $conditions[] = $condition;
        }

        if (!empty($conditions)) {
            $sql[] = 'ON ' . implode(' ', $conditions);
        }

        return implode(' ', $sql);
    }

    /**
     * Get the join type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the join table.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}
