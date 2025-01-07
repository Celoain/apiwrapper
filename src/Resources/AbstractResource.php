<?php

namespace Celoain\ApiWrapper\Resources;

use Carbon\Carbon;
use Celoain\ApiWrapper\Enums\Resource\CastTypes;
use Celoain\ApiWrapper\Contracts\Resource\ResourceInterface;
use Illuminate\Support\Collection;

abstract class AbstractResource implements ResourceInterface
{
    protected string $idField = 'id';

    /**
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * @var array<string>
     */
    protected array $dirty = [];

    /**
     * @var array<string>
     */
    protected array $casts = [];

    protected ?string $allRoute = null;

    protected ?string $createRoute = null;

    protected ?string $deleteRoute = null;

    protected ?string $getRoute = null;

    protected ?string $updateRoute = null;

    protected ?string $routeHandler = null;

    public function __construct(array $data = [], bool $exists = false)
    {
        $this->mergeAttributes($data);

        if (! $exists) {
            $this->dirty = [];
        }
    }

    public function __get(string $key): mixed
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->setAttribute($key, $value);
    }

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute(string $key, mixed $value): self
    {
        if ($this->castsAttribute($key)) {
            $value = $this->castAs($value, $this->getAttributeCastType($key));
        }

        $this->attributes[$key] = $value;
        $this->dirty[$key] = $value;

        return $this;
    }

    public function setAttributes(array $attributes, bool $clear = false): self
    {
        if ($clear) {
            $this->attributes = [];
        }

        return $this->mergeAttributes($attributes);
    }

    public function mergeAttributes(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function getDirty(): array
    {
        return $this->dirty;
    }

    public function castsAttribute(string $key): bool
    {
        return array_key_exists($key, $this->casts);
    }

    public function getAttributeCastType(string $key): CastTypes|string|null
    {
        return $this->casts[$key] ?? null;
    }

    protected function castAsDate(string|int $value): Carbon
    {
        return $this->castAsDateTime($value)->startOfDay();
    }

    protected function castAsDateTime(string|int $value): Carbon
    {
        if (is_int($value)) {
            return Carbon::createFromTimestamp($value);
        } else {
            return Carbon::create($value);
        }
    }

    /**
     * @param  array<mixed>  $value
     * @param  class-string  $type
     */
    protected function castAsClass(mixed $value, string $type): ?object
    {
        if (class_exists($type) && is_subclass_of($type, AbstractResource::class)) {
            if (collect($value)->every(function ($value, $key) {
                return is_int($key) && is_array($value);
            })) {
                return $type::castMany($value);
            } else {
                return $type::cast($value);
            }
        }

        return null;
    }

    protected function castAs(mixed $value, string|CastTypes $type): mixed
    {
        return match ($type) {
            CastTypes::BOOLEAN => (bool) $value,
            CastTypes::COLLECTION => new Collection($value),
            CastTypes::DATE => $this->castAsDate($value),
            CastTypes::DATETIME => $this->castAsDateTime($value),
            CastTypes::FLOAT => (float) $value,
            CastTypes::INTEGER => (int) $value,
            CastTypes::STRING => (string) $value,
            default => $this->castAsClass($value, $type),
        };
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromJson(string $data): self
    {
        return new static(json_decode($data, true));
    }

    public function getIdField(): string
    {
        return $this->idField;
    }

    public function getId(): mixed
    {
        return $this->getAttribute($this->getIdField());
    }

    public static function cast($data): self
    {
        return new static((array) $data);
    }

    public static function castMany(array $data): Collection
    {
        return collect($data)->map(function ($datapoint) {
            return static::cast((array) $datapoint);
        });
    }

    public function toArray(): array
    {
        $array = [];

        foreach ($this->attributes as $key => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $value = $value->toArray();
            }

            $array[$key] = $value;
        }

        return $array;
    }
}
