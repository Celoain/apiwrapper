<?php

namespace Tests\Helpers\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class MockGuzzleClient
{
    public static function getClient(int $status = 200, ?string $body = null): Client
    {
        return new Client([
            'handler' => HandlerStack::create(
                new MockHandler([
                    new Response($status, [], $body),
                ])
            ),
        ]);
    }
}
