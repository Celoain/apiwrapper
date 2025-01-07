<?php

use Celoain\ApiWrapper\Api\Endpoint;
use Celoain\ApiWrapper\Enums\Api\HttpMethods;
use Celoain\ApiWrapper\Tests\Helpers\Api\BasicProcessor;
use Celoain\ApiWrapper\Tests\Helpers\Api\PostProcessor;

beforeEach(function () {
    $this->endpoint = new Endpoint(HttpMethods::GET, 'test');
});

test('fluent builder', function () {
    expect($this->endpoint)
        ->toBe($this->endpoint->setUrl('https://www.example.com'))
        ->and($this->endpoint)
        ->toBe($this->endpoint->setProcessors([BasicProcessor::class]));

});

test('get method', function () {
    expect($this->endpoint->getMethod())
        ->toBe(HttpMethods::GET);
});

test('get url', function () {
    expect($this->endpoint->getUrl())
        ->toBe('test');

    $this->endpoint->setUrl('https://www.example.com');

    expect($this->endpoint->getUrl())
        ->toBe('https://www.example.com/test');

});

test('get processors', function () {
    expect($this->endpoint->getProcessors())
        ->toBeEmpty();

    $this->endpoint->setProcessors([BasicProcessor::class]);

    expect($this->endpoint->getProcessors())
        ->toHaveCount(1)
        ->and($this->endpoint->getProcessors())
        ->toContain(BasicProcessor::class);

    $this->endpoint->setProcessors([BasicProcessor::class]);

    expect($this->endpoint->getProcessors())
        ->toHaveCount(1)
        ->and($this->endpoint->getProcessors())
        ->toContain(BasicProcessor::class);

    $this->endpoint->setProcessors([PostProcessor::class]);

    expect($this->endpoint->getProcessors())
        ->toHaveCount(2)
        ->and($this->endpoint->getProcessors())
        ->toContain(BasicProcessor::class)
        ->and($this->endpoint->getProcessors())
        ->toContain(PostProcessor::class);
});
