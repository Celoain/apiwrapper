<?php

namespace Celoain\ApiWrapper\Contracts\Api;

use Celoain\ApiWrapper\Enums\Api\HttpMethods;
use Celoain\ApiWrapper\Exceptions\EndpointNotDefinedException;

interface RouteInterface
{
    /**
     * @param  array<ProcessorInterface>  $processors
     */
    public static function group(string $baseUrl, array $processors = [], ?callable $callback = null): void;

    /**
     * @throws EndpointNotDefinedException
     */
    public static function find(string $name): EndpointInterface;

    public static function endpoint(HttpMethods $method, string $name, string $path): EndpointInterface;

    public static function options(string $name, string $path): EndpointInterface;

    public static function head(string $name, string $path): EndpointInterface;

    public static function get(string $name, string $path): EndpointInterface;

    public static function post(string $name, string $path): EndpointInterface;

    public static function put(string $name, string $path): EndpointInterface;

    public static function patch(string $name, string $path): EndpointInterface;

    public static function delete(string $name, string $path): EndpointInterface;
}
