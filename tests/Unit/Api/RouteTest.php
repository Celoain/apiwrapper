<?php

use Celoain\ApiWrapper\Api\Route;
use Celoain\ApiWrapper\Contracts\Api\EndpointInterface;
use Celoain\ApiWrapper\Enums\Api\HttpMethods;
use Tests\Helpers\Api\BasicProcessor;

test('basic Endpoint', function () {

    $endpoint = Route::endpoint(HttpMethods::GET, 'test', 'test')
        ->setProcessors([BasicProcessor::class]);

    expect($endpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($endpoint->getMethod())
        ->toBe(HttpMethods::GET)
        ->and($endpoint->getUrl())
        ->toBe('test')
        ->and($endpoint->getProcessors())
        ->toHaveCount(1);

    $endpoint->setUrl('https://www.example.com');

    expect($endpoint->getUrl())
        ->toBe('https://www.example.com/test');

});

test('HEAD Endpoint', function () {

    $endpoint = Route::head('test', 'test');

    expect($endpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($endpoint->getMethod())
        ->toBe(HttpMethods::HEAD);

});

test('GET Endpoint', function () {

    $endpoint = Route::get('test', 'test');

    expect($endpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($endpoint->getMethod())
        ->toBe(HttpMethods::GET);

});

test('POST Endpoint', function () {

    $endpoint = Route::post('test', 'test');

    expect($endpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($endpoint->getMethod())
        ->toBe(HttpMethods::POST);

});

test('PUT Endpoint', function () {

    $endpoint = Route::put('test', 'test');

    expect($endpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($endpoint->getMethod())
        ->toBe(HttpMethods::PUT);

});

test('PATCH Endpoint', function () {

    $endpoint = Route::patch('test', 'test');

    expect($endpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($endpoint->getMethod())
        ->toBe(HttpMethods::PATCH);

});

test('DELETE Endpoint', function () {

    $endpoint = Route::delete('test', 'test');

    expect($endpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($endpoint->getMethod())
        ->toBe(HttpMethods::DELETE);

});

test('OPTIONS Endpoint', function () {

    $endpoint = Route::options('test', 'test');

    expect($endpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($endpoint->getMethod())
        ->toBe(HttpMethods::OPTIONS);
});

test('Grouped Endpoints', function () {

    $groupedEndpoint = null;

    Route::group('https://example.com', [BasicProcessor::class], function () use (&$groupedEndpoint) {
        $groupedEndpoint = Route::get('test', 'test');
    });

    expect($groupedEndpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($groupedEndpoint->getUrl())
        ->toBe('https://example.com/test')
        ->and($groupedEndpoint->getProcessors())
        ->toHaveCount(1);

    $basicEndpoint = Route::post('test', 'test');

    expect($basicEndpoint)
        ->toBeInstanceOf(EndpointInterface::class)
        ->and($basicEndpoint->getProcessors())
        ->toHaveCount(0);
});
