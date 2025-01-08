<?php

use Celoain\ApiWrapper\Api\Route;
use Tests\Helpers\Api\MockGuzzleClient;
use Tests\Helpers\Resources\BasicResource;

it('creates a resource', function () {
    Route::post('basicResources.create', '/basicApiResources');

    $data = ['id' => 1, 'title' => 'Ipsum'];
    $response = BasicResource::create(
        $data,
        client: MockGuzzleClient::getClient(
            body: json_encode($data)
        )
    );

    expect($response->id)
        ->toBe($data['id'])
        ->and($response->title)
        ->toBe($data['title']);

});
