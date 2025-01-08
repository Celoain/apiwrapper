<?php

namespace Celoain\ApiWrapper\Resources\Traits;

use Celoain\ApiWrapper\Contracts\Resource\ResourceInterface;
use Celoain\ApiWrapper\Exceptions\EndpointNotDefinedException;
use GuzzleHttp\Client;

trait Get
{
    use Base;

    public function getRoute(): string
    {
        return $this->getRoute ??
            $this->getRoutePrefix().'.get';
    }

    /**
     * @param  array<mixed>  $options
     */
    public static function get(mixed $id, array $options = [], ?Client $client = null): ?ResourceInterface
    {
        /** @var self $instance */
        $instance = static::cast([]);

        $response = $instance->getRequestHandler()::route($instance->getRoute(), $client)
            ->pathParams([static::cast([])->getIdField() => $id])
            ->options($options)
            ->send();

        return static::cast($response->json());
    }

    /**
     * @param  array<mixed>  $options
     */
    public function fresh(array $options = [], ?Client $client = null): ?ResourceInterface
    {
        return static::get($this->getId(), $options, $client);
    }

    /**
     * @param  array<mixed>  $options
     *
     * @throws EndpointNotDefinedException
     */
    public function refresh(array $options = [], ?Client $client = null): ResourceInterface
    {
        $response = $this->getRequestHandler()::route($this->getRoute(), $client)
            ->pathParams([$this->getIdField() => $this->getId()])
            ->options($options)
            ->send();

        return $this->setAttributes($response->json(), true);
    }
}
