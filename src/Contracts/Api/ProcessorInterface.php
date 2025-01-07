<?php

namespace Celoain\ApiWrapper\Contracts\Api;

interface ProcessorInterface
{
    public static function handle(RequestInterface $request, callable $next): ResponseInterface;
}
