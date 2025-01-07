<?php

namespace Celoain\ApiWrapper\Contracts\Resource;

use Celoain\ApiWrapper\Enums\Resource\CastTypes;
use Illuminate\Support\Collection;

interface ResourceInterface
{
    /**
     * @param  array<mixed, mixed>  $data
     */
    public function __construct(array $data = [], bool $exists = false);

    public function __get(string $key): mixed;

    public function __set(string $key, mixed $value): void;

    public function hasAttribute(string $key): bool;

    public function getAttribute(string $key): mixed;

    public function setAttribute(string $key, mixed $value): ResourceInterface;

    /**
     * @param  array<mixed>  $attributes
     */
    public function setAttributes(array $attributes, bool $clear = false): ResourceInterface;

    /**
     * @param  array<mixed>  $attributes
     */
    public function mergeAttributes(array $attributes): ResourceInterface;

    /**
     * @return array<string>
     */
    public function getDirty(): array;

    public function castsAttribute(string $key): bool;

    public function getAttributeCastType(string $key): CastTypes|string|null;

    public function toJson(): string|false;

    public static function fromJson(string $data): ResourceInterface;

    public function getIdField(): string;

    public function getId(): mixed;

    /**
     * @param  array<mixed>  $data
     */
    public static function cast($data): ResourceInterface;

    /**
     * @param  array<mixed>  $data
     * @return Collection<int, covariant self>
     */
    public static function castMany(array $data): Collection;

    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
