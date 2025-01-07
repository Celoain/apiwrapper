<?php

namespace Celoain\ApiWrapper\Api;

use Celoain\ApiWrapper\Contracts\Api\EndpointInterface;
use Celoain\ApiWrapper\Contracts\Api\ProcessorInterface;
use Celoain\ApiWrapper\Enums\Api\HttpMethods;

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
