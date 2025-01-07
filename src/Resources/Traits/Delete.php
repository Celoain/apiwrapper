<?php

namespace Celoain\ApiWrapper\Resources\Traits;

use Celoain\ApiWrapper\Exceptions\EndpointNotDefinedException;
use GuzzleHttp\Client;

trait Delete
{
    use Base;

    public function deleteRoute(): string
    {
        return $this->deleteRoute ??
            $this->getRoutePrefix().'.delete';
    }

    /**
     * @param  mixed  $id
     * @param  array<mixed>  $options
     *
     * @throws EndpointNotDefinedException
     */
    public static function delete($id, array $options = [], ?Client $client = null): void
    {
        /** @var self $instance */
        $instance = static::cast([]);
        $instance->setAttribute($instance->getIdField(), $id);
        $instance->destroy($options, $client);
    }

    /**
     * @param  array<mixed>  $options
     *
     * @throws EndpointNotDefinedException
     */
    public function destroy(array $options = [], ?Client $client = null): void
    {
        $this->getRequestHandler()::route($this->deleteRoute(), $client)
            ->pathParams([$this->getIdField() => $this->getId()])
            ->options($options)
            ->send();
    }
}
