<?php

namespace Core\Http;

abstract class JsonResource implements \JsonSerializable
{
    protected $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
        static::sendJsonHeaders();
    }

    abstract public function toArray(): array;

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public static function collection($collection): string
    {
        $result = array_map(fn ($resource) => static::make($resource)->toArray(), $collection);
        static::sendJsonHeaders();
        return json_encode($result);
    }

    public static function make($resource)
    {
        return new static($resource);
    }

    public function merge($data): self
    {
        $this->resource = array_merge($this->resource, $data);
        return $this;
    }

    public function mergeWhen($condition, $data): self
    {
        if ($condition) {
            $this->resource = array_merge($this->resource, $data);
        }
        return $this;
    }

    protected function when($condition, $value, $default = null)
    {
        if ($condition) {
            return is_callable($value) ? $value() : $value;
        }
        return is_callable($default) ? $default() : $default;
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    protected static function sendJsonHeaders(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
    }

    public function __get($name)
    {
        return $this->resource->{$name};
    }

    public function __isset($name)
    {
        return isset($this->resource->{$name});
    }

    public function __call($name, $arguments)
    {
        return $this->resource->{$name}(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return (new static(...$arguments))->$name();
    }

    public function __invoke()
    {
        return $this->resource;
    }

    public function __destruct()
    {
        unset($this->resource);
    }
}
