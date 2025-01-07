<?php

namespace Celoain\ApiWrapper\Resources\Traits;

use Celoain\ApiWrapper\Exceptions\EndpointNotDefinedException;
use Celoain\ApiWrapper\Resources\AbstractResource;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

trait GetAll
{
    use Base;

    public function allRoute(): string
    {
        return $this->allRoute ??
            $this->getRoutePrefix().'.all';
    }

    /**
     * @param  array<mixed>  $options
     * @return Collection<int, covariant AbstractResource>
     *
     * @throws EndpointNotDefinedException
     */
    public static function all(array $options = [], ?Client $client = null): Collection
    {
        /** @var self $self */
        $self = static::cast([]);

        $response = $self->getRequestHandler()::route($self->allRoute(), $client)
            ->options($options)
            ->send();

        return static::castMany($response->json());
    }
}
