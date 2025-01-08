<?php

namespace Celoain\ApiWrapper\Resources\Traits;

use Celoain\ApiWrapper\Exceptions\EndpointNotDefinedException;
use Celoain\ApiWrapper\Contracts\Resource\ResourceInterface;
use GuzzleHttp\Client;

trait Update
{
    use Base;

    public function updateRoute(): string
    {
        return $this->updateRoute ??
            $this->getRoutePrefix().'.update';
    }

    /**
     * @param  array<mixed>  $attributes
     * @param  array<mixed>  $options
     *
     * @throws EndpointNotDefinedException
     */
    public static function update(mixed $id, array $attributes, array $options = [], ?Client $client = null): ResourceInterface
    {
        /** @var self $instance */
        $instance = static::cast([]);
        $instance->setAttribute($instance->getIdField(), $id);

        return $instance->updateAttributes($attributes, $options, $client);
    }

    /**
     * @param  array<mixed>  $attributes
     * @param  array<mixed>  $options
     *
     * @throws EndpointNotDefinedException
     */
    public function updateAttributes(array $attributes, array $options = [], ?Client $client = null): ResourceInterface
    {
        $response = $this->getRequestHandler()::route($this->updateRoute(), $client)
            ->pathParams([$this->getIdField() => $this->getId()])
            ->json($attributes)
            ->options($options)
            ->send();

        return $this->mergeAttributes($response->json() ?? []);
    }

    /**
     * @param  array<mixed>  $options
     *
     * @throws EndpointNotDefinedException
     */
    public function saveChanges(array $options = [], ?Client $client = null): ResourceInterface
    {
        return $this->updateAttributes($this->getDirty(), $options, $client);
    }
}
