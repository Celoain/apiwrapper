<?php

namespace Celoain\ApiWrapper\Contracts\Api;

use Celoain\ApiWrapper\Enums\Api\HttpMethods;

interface EndpointInterface
{
    public function setUrl(string $url): self;

    /**
     * @param  class-string<ProcessorInterface>[]  $processors
     */
    public function setProcessors(array $processors): self;

    public function getMethod(): HttpMethods;

    public function getUrl(): string;

    /**
     * @return class-string<ProcessorInterface>[]
     */
    public function getProcessors(): array;
}
