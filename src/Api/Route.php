<?php

namespace Celoain\ApiWrapper\Api;

use Celoain\ApiWrapper\Contracts\Api\EndpointInterface;
use Celoain\ApiWrapper\Contracts\Api\ProcessorInterface;
use Celoain\ApiWrapper\Contracts\Api\RouteInterface;
use Celoain\ApiWrapper\Enums\Api\HttpMethods;
use Celoain\ApiWrapper\Exceptions\EndpointNotDefinedException;

class Route implements RouteInterface
{
    /**
     * @var string[]
     */
    protected static array $urls = [];

    /**
     * @var array<ProcessorInterface|array<ProcessorInterface>>
     */
    protected static array $processors = [];

    /**
     * @var array<EndpointInterface>
     */
    protected static array $endpoints = [];

    /**
     * @param  array<class-string ProcessorInterface|array<class-string ProcessorInterface>>  $processors
     */
    public static function group(string $baseUrl, array $processors = [], ?callable $callback = null): void
    {
        static::push($baseUrl, $processors);

        if ($callback) {
            $callback();
        }

        static::pop();

    }

    /**
     * @throws EndpointNotDefinedException
     */
    public static function find(string $name): EndpointInterface
    {
        if (array_key_exists($name, static::$endpoints)) {
            return static::$endpoints[$name];
        }

        throw new EndpointNotDefinedException('Endpoint not found');
    }

    public static function endpoint(HttpMethods $method, string $name, string $path): EndpointInterface
    {
        $endpoint = (new Endpoint($method, $path))
            ->setUrl(self::groupUrl())
            ->setProcessors(self::groupProcessors());

        static::$endpoints[$name] = $endpoint;

        return $endpoint;
    }

    public static function options(string $name, string $path): EndpointInterface
    {
        return static::endpoint(HttpMethods::OPTIONS, $name, $path);
    }

    public static function head(string $name, string $path): EndpointInterface
    {
        return static::endpoint(HttpMethods::HEAD, $name, $path);
    }

    public static function get(string $name, string $path): EndpointInterface
    {
        return static::endpoint(HttpMethods::GET, $name, $path);
    }

    public static function post(string $name, string $path): EndpointInterface
    {
        return static::endpoint(HttpMethods::POST, $name, $path);
    }

    public static function put(string $name, string $path): EndpointInterface
    {
        return static::endpoint(HttpMethods::PUT, $name, $path);
    }

    public static function patch(string $name, string $path): EndpointInterface
    {
        return static::endpoint(HttpMethods::PATCH, $name, $path);
    }

    public static function delete(string $name, string $path): EndpointInterface
    {
        return static::endpoint(HttpMethods::DELETE, $name, $path);
    }

    /**
     * @param  array<ProcessorInterface|array<ProcessorInterface>>  $processors
     */
    protected static function push(string $baseUrl, array $processors): void
    {
        static::$urls[] = $baseUrl;
        static::$processors[] = $processors;
    }

    protected static function pop(): void
    {
        array_pop(static::$urls);
        array_pop(static::$processors);
    }

    protected static function groupUrl(): string
    {
        $urls = static::$urls;
        $url = null;

        while (is_null($url) && count($urls) > 0) {
            $url = array_pop($urls);
        }

        return $url ?? '';
    }

    /**
     * @return class-string<ProcessorInterface>[]
     */
    protected static function groupProcessors(): array
    {
        return array_merge(...static::$processors);
    }
}
