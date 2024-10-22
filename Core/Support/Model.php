<?php

namespace Core\Support;

use Core\Pagination\Paginator;
use PDO;
use Core\Database\{QueryBuilder, Connection};
use Closure;

abstract class Model
{
    protected static ?PDO $pdo = null;
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected array $attributes = [];
    protected array $original = [];
    protected static array $hidden = [];
    protected static array $appends = [];
    protected static array $casts = [];

    protected static array $globalScopes = [];

    public static function initialize(): void
    {
        if (is_null(static::$pdo)) {
            static::$pdo = Connection::getInstance();
        }
    }

    public static function getTable(): string
    {
        return static::$table ?? strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', (new \ReflectionClass(static::class))->getShortName())) . 's';
    }

    public static function find($id): ?static
    {
        $array = static::query()->where(static::$primaryKey, $id)->first();

        return $array ?? null;
    }

    public static function all(): array
    {
        $models = static::query()->get();

        return $models ?? [];
    }

    public static function paginate($perPage = 10, $page = 1)
    {
        $total = static::query()->count();
        $items = static::query()->limit($perPage)->offset(($page - 1) * $perPage)->get();

        if (empty($items)) {
            return new Paginator($perPage, $page, $total, []);
        }

        return new Paginator($perPage, $page, $total, array_map(fn($item) => static::createModelInstance($item), $items));
    }

    public static function create(array $attributes): static
    {
        $model = new static();
        $model->fill($attributes);
        $model->save();
        return $model;
    }

    public function save(): bool
    {
        if ($this->isDirty()) {
            $this->isNewRecord() ? $this->insert() : $this->update();
            $this->syncOriginal();
            return true;
        }
        return false;
    }

    public function delete(): bool
    {
        return (bool)static::query()
                           ->where(static::$primaryKey, $this->getAttribute(static::$primaryKey))
                           ->delete();
    }

    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function prepare(array $attributes): void
    {
        $this->fill($attributes);
        $this->syncOriginal();
    }

    public function getAttribute(string $key): mixed
    {
        if (in_array($key, static::$hidden)) {
            return null;
        }

        $value = $this->attributes[$key] ?? null;

        return in_array($key, static::$appends) ? $this->getAppendedAttribute($key) : $this->castAttribute($key, $value);
    }

    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $this->castAttribute($key, $value);
    }

    public function castAttribute(string $key, mixed $value): mixed
    {
        if (array_key_exists($key, static::$casts)) {
            $value = match (static::$casts[$key]) {
                'int'    => (int)$value,
                'float'  => (float)$value,
                'string' => (string)$value,
                'bool'   => (bool)$value,
                'object' => (object)$value,
                'array'  => (array)$value,
                'json'   => json_decode($value, true),
                'date'   => date_create($value),
                default  => $value
            };
        }

        return $value;
    }

    public function syncOriginal(): void
    {
        $this->syncAttributes();
        $this->original = $this->attributes;
    }

    public function syncAttributes(): void
    {
        $attributes = [];

        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, static::$hidden)) {
                $attributes[$key] = $this->castAttribute($key, $value);
            }
        }

        if (!empty(static::$appends)) {
            foreach (static::$appends as $key) {
                $attributes[$key] = $this->getAppendedAttribute($key);
            }
        }

        $this->attributes = $attributes;
    }

    public function isDirty(): bool
    {
        return $this->attributes !== $this->original;
    }

    protected function isNewRecord(): bool
    {
        return empty($this->original);
    }

    protected function insert(): void
    {
        $id = static::query()->insert($this->attributes);
        $this->setAttribute(static::$primaryKey, $id);
    }

    protected function update($columns = []): void
    {
        if (empty($columns)) {
            $columns = array_intersect_key(
                $this->attributes,
                array_filter($this->attributes, function ($key) {
                    return !array_key_exists($key, $this->original) || $this->attributes[$key] !== $this->original[$key];
                }, ARRAY_FILTER_USE_KEY)
            );
        }

        unset($columns[static::$primaryKey]);

        if (!empty($columns)) {
            static::query()
                  ->where(static::$primaryKey, $this->getAttribute(static::$primaryKey))
                  ->update($columns);
        }
    }

    protected static function createModelInstance(array $attributes): static
    {
        $model = new static();
        $model->fill($attributes);
        $model->syncOriginal();
        return $model;
    }

    protected function getAppendedAttribute(string $key): mixed
    {
        $name   = str_replace('_', '', ucwords($key, '_'));
        $method = 'get' . ucfirst($name) . 'Attribute';

        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException("Method $method does not exist in " . static::class . " class.");
        }

        return $this->$method();
    }

    public function toArray(): array
    {
        $this->syncAttributes();
        return $this->attributes;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __get(string $key): mixed
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->setAttribute($key, $value);
    }

    public static function query(): QueryBuilder
    {
        static::initialize();

        $query = (new QueryBuilder(static::$pdo, static::class))->table(static::getTable());

        foreach (static::$globalScopes as $scope) {
            $scope($query);
        }

        return $query;
    }

    public static function addGlobalScope(Closure $scope): void
    {
        static::$globalScopes[] = $scope;
    }

    protected static function callScope($scope, $parameters, QueryBuilder $query = null)
    {
        if (is_null($query)) {
            $query = static::query();
        }

        if (is_string($scope)) {
            $scope = [new static, str_starts_with($scope, 'scope') ? $scope : 'scope' . ucfirst($scope)];
        }

        return $scope($query, ...$parameters);
    }

    public static function __callStatic(string $method, array $parameters): mixed
    {
        if (method_exists(static::class, $scope = 'scope' . ucfirst($method))) {
            return static::callScope($scope, $parameters);
        }

        return static::query()->$method(...$parameters);
    }

    public function __call(string $method, array $parameters): mixed
    {
        if (method_exists($this, $scope = 'scope' . ucfirst($method))) {
            return static::callScope([$this, $scope], $parameters);
        }

        if (method_exists($this, $method)) {
            return $this->$method(...$parameters);
        }

        return static::query()->$method(...$parameters);
    }
}
