<?php

namespace Celoain\ApiWrapper\Exceptions;

use Exception;

class EndpointNotDefinedException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Endpoint '{$name}' not defined");
    }
}
