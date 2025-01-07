<?php

namespace Celoain\ApiWrapper\Facades;

use Illuminate\Support\Facades\Facade;

class ApiWrapper extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'api-wrapper';
    }
}
