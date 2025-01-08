<?php

namespace Celoain\ApiWrapper\Resources;

use Carbon\Carbon;
use Celoain\ApiWrapper\Contracts\Resource\ResourceInterface;
use Celoain\ApiWrapper\Enums\Resource\CastTypes;
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

    protected ?string $requestHandler = null;

    protected ?string $routePrefix = null;

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

    /**
     * @param  class-string|CastTypes  $type
     */
    protected function castAs(mixed $value, string|CastTypes $type): mixed
    {
        if ($type instanceof CastTypes) {
            $type = $type->value;
        }

        return match ($type) {
            CastTypes::BOOLEAN->value => (bool) $value,
            CastTypes::COLLECTION->value => new Collection($value),
            CastTypes::DATE->value => $this->castAsDate($value),
            CastTypes::DATETIME->value => $this->castAsDateTime($value),
            CastTypes::FLOAT->value => (float) $value,
            CastTypes::INTEGER->value => (int) $value,
            CastTypes::STRING->value => (string) $value,
            default => $this->castAsClass($value, $type),
        };
    }

    public function toJson(): string|false
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
            if ($value instanceof Collection) {
                $collectionValues = [];
                foreach ($value as $collectionValue) {
                    $collectionValues[] = $collectionValue->toArray();
                }
                $value = $collectionValues;
            } elseif (
                is_object($value) && method_exists($value, 'toArray')
            ) {
                $value = $value->toArray();
            }

            $array[$key] = $value;
        }

        return $array;
    }
}
