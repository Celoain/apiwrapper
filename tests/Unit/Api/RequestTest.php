<?php

use Celoain\ApiWrapper\Api\Endpoint;
use Celoain\ApiWrapper\Api\Request;
use Celoain\ApiWrapper\Api\Route;
use Celoain\ApiWrapper\Contracts\Api\RequestInterface;
use Celoain\ApiWrapper\Enums\Api\HttpMethods;
use Tests\Helpers\Api\BasicProcessor;
use Tests\Helpers\Api\MockGuzzleClient;
use Tests\Helpers\Api\PostProcessor;

it('can define and pull a route by name', function () {
    Route::get('requestRouteTest', 'requestRouteTest');

    $request = Request::route('requestRouteTest');

    expect($request)
        ->toBeInstanceOf(RequestInterface::class)
        ->and($request->getUrl())
        ->toBe('requestRouteTest');
});

describe('testing Route features', function () {

    beforeEach(function () {
        $this->request = new Request(new Endpoint(HttpMethods::GET, 'test'));
    });

    test('fluent builders', function () {
        expect($this->request)
            ->toBe($this->request->pathParams([]))
            ->and($this->request)
            ->toBe($this->request->auth(['username', 'password']))
            ->and($this->request)
            ->toBe($this->request->headers([]))
            ->and($this->request)
            ->toBe($this->request->queryParams([]))
            ->and($this->request)
            ->toBe($this->request->body(''))
            ->and($this->request)
            ->toBe($this->request->json([]))
            ->and($this->request)
            ->toBe($this->request->formParams([]))
            ->and($this->request)
            ->toBe($this->request->multipart([]))
            ->and($this->request)
            ->toBe($this->request->options([]));
    });

    test('all options are passed correctly', function () {
        $auth = ['username', 'password'];
        $this->request->auth($auth);

        $headers = ['header' => 'value'];
        $this->request->headers($headers);

        $query = ['query' => 'value'];
        $this->request->queryParams($query);

        $body = 'body';
        $this->request->body($body);

        $json = ['json' => 'value'];
        $this->request->json($json);

        $form = ['form' => 'value'];
        $this->request->formParams($form);

        $multipart = ['multipart' => 'value'];
        $this->request->multipart($multipart);

        $extraOptions = ['option' => 'value'];
        $this->request->options($extraOptions);

        $options = $this->request->getOptions();

        expect($auth)
            ->toBe($options['auth'])
            ->and($headers)
            ->toBe($options['headers'])
            ->and($query)
            ->toBe($options['query'])
            ->and($body)
            ->toBe($options['body'])
            ->and($json)
            ->toBe($options['json'])
            ->and($form)
            ->toBe($options['form_params'])
            ->and($multipart)
            ->toBe($options['multipart'])
            ->and($extraOptions['option'])
            ->toBe($options['option']);
    });
});

it('sends', function () {

    $endpoint = (new Endpoint(HttpMethods::GET, 'test'))
        ->setUrl('https://www.example.com')
        ->setProcessors([BasicProcessor::class, PostProcessor::class]);

    (new Request($endpoint, MockGuzzleClient::getClient()))->send();

    expect(BasicProcessor::$called)
        ->toBeTrue()
        ->and(PostProcessor::$called)
        ->toBeTrue();

});
