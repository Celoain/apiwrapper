<?php

namespace Celoain\ApiWrapper\Resources\Traits;

use Celoain\ApiWrapper\Contracts\Resource\ResourceInterface;
use Celoain\ApiWrapper\Exceptions\EndpointNotDefinedException;
use GuzzleHttp\Client;

trait Create
{
    use Base;

    public function createRoute(): string
    {
        return $this->createRoute ??
            $this->getRoutePrefix().'.create';
    }

    /**
     * @param  array<mixed>  $attributes
     * @param  array<mixed>  $options
     *
     * @throws EndpointNotDefinedException
     */
    public static function create(array $attributes, array $options = [], ?Client $client = null): ResourceInterface
    {
        /** @var self $instance */
        $instance = static::cast([]);

        return $instance->store($options, $client);
    }

    /**
     * @param  array<mixed>  $options
     *
     * @throws EndpointNotDefinedException
     */
    public function store(array $options = [], ?Client $client = null): ResourceInterface
    {
        $response = $this->getRequestHandler()::route($this->createRoute(), $client)
            ->json($this->toArray())
            ->options($options)
            ->send();

        return $this->mergeAttributes($response->json() ?? []);
    }
}
