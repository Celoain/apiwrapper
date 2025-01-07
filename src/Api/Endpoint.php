<?php

namespace Celoain\ApiWrapper\Api;

use /**
 * Interface EndpointInterface
 * An interface for defining API endpoints within the AsanaHub application.
 * The implementing class will define methods for interacting with specific API endpoints,
 * utilizing the application's database connection and queue system.
 */
Celoain\ApiWrapper\Contracts\Api\EndpointInterface;
use /**
 * ProcessorInterface is a contract for handling API processing tasks
 * in the AsanaHub Laravel application.
 * This interface defines the core methods required for processing
 * API requests and responses, ensuring consistent implementation
 * across different processor classes.
 * Implementing classes are expected to interact with a MySQL database
 * and handle queued tasks using a database connection in a Laravel
 * environment.
 */
Celoain\ApiWrapper\Contracts\Api\ProcessorInterface;
use /**
 * Enumeration of HTTP methods used in API requests within the AsanaHub Laravel application.
 * This enum provides a standardized set of HTTP method constants to be used when
 * making API requests. It facilitates consistent and error-free usage of HTTP methods
 * in the application.
 * Supported HTTP methods include:
 * - GET
 * - POST
 * - PUT
 * - DELETE
 * - PATCH
 * - OPTIONS
 * - HEAD
 * Each method corresponds to a commonly used HTTP verb, providing a clear understanding
 * of the intended action to be performed on the resource.
 */
Celoain\ApiWrapper\Enums\Api\HttpMethods;

class Endpoint implements EndpointInterface
{
    protected string $url = '';

    /**
     * @var ProcessorInterface[]
     */
    protected array $processors = [];

    public function __construct(protected HttpMethods $method, protected string $path)
    {
        $this->path = ltrim($this->path, '/');
    }

    public function setUrl(string $url): self
    {
        $this->url = rtrim($url, '/');

        return $this;
    }

    /**
     * @param  ProcessorInterface[]  $processors
     * @return $this
     */
    public function setProcessors(array $processors): self
    {
        $this->processors = array_unique(array_merge($this->processors, $processors));

        return $this;
    }

    public function getMethod(): HttpMethods
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return ltrim(sprintf(
            '%s/%s',
            $this->url,
            $this->path
        ), '/');
    }

    /**
     * @return ProcessorInterface[]
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }
}
