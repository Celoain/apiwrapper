<?php

use Celoain\ApiWrapper\Api\Route;
use Illuminate\Support\Collection;
use Tests\Helpers\Api\MockGuzzleClient;
use Tests\Helpers\Resources\BasicResource;

it('get all resources', function () {
    $data = [
        [
            'id' => 1,
        ],
        [
            'id' => 2,
        ],
    ];
    $json = json_encode($data);

    Route::get('basicResources.all', '/basicApiResources');

    $response = BasicResource::all(client: MockGuzzleClient::getClient(body: $json));

    expect($response)
        ->toBeInstanceOf(Collection::class)
        ->and($response)
        ->toContainOnlyInstancesOf(BasicResource::class);

});
