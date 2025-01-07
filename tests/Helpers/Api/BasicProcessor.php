<?php

namespace Celoain\ApiWrapper\Tests\Helpers\Api;

use Celoain\ApiWrapper\Contracts\Api\ProcessorInterface;
use Celoain\ApiWrapper\Contracts\Api\ResponseInterface;
use Celoain\ApiWrapper\Contracts\Api\RequestInterface;

class BasicProcessor implements ProcessorInterface
{
    public static bool $called = false;

    public static function handle(RequestInterface $request, callable $next): ResponseInterface
    {
        self::$called = true;

        return $next($request);
    }
}
