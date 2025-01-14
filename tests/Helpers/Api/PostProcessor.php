<?php

namespace Tests\Helpers\Api;

use Celoain\ApiWrapper\Contracts\Api\ProcessorInterface;
use Celoain\ApiWrapper\Contracts\Api\RequestInterface;
use Celoain\ApiWrapper\Contracts\Api\ResponseInterface;

class PostProcessor implements ProcessorInterface
{
    public static bool $called = false;

    public static function handle(RequestInterface $request, callable $next): ResponseInterface
    {
        $response = $next($request);

        self::$called = true;

        return $response;

    }
}
