<?php

namespace Celoain\ApiWrapper\Resources\Traits;

use Celoain\ApiWrapper\Api\Request;
use Celoain\ApiWrapper\Contracts\Api\RequestInterface;
use Symfony\Component\String\Inflector\EnglishInflector;

use function Symfony\Component\String\u;

trait Base
{
    public function getRoutePrefix(): string
    {
        $class = explode('\\', static::class);
        $resource = (new EnglishInflector)->pluralize(array_pop($class))[0];

        return $this->allRoute ??
            u($resource)->camel();
    }

    /**
     * @return class-string<RequestInterface>
     */
    public function getRequestHandler(): string
    {
        return $this->requestHandler ?? Request::class;
    }
}
