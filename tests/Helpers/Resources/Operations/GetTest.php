<?php

use Celoain\ApiWrapper\Api\Route;
use Tests\Helpers\Api\MockGuzzleClient;
use Tests\Helpers\Resources\BasicResource;

describe('get actions', function () {

    beforeEach(function () {
        $this->freshData = [
            'id' => 1,
            'title' => 'Lorem',
        ];

        Route::get('basicResources.get', '/basicApiResources');

    });

    it('gets a resource', function () {

        $response = BasicResource::get(
            1,
            client: MockGuzzleClient::getClient(
                body: json_encode($this->freshData)
            )
        );

        expect($response)
            ->toBeInstanceOf(BasicResource::class);
    });

    it('refresh the resource', function () {

        $response = BasicResource::get(
            1,
            client: MockGuzzleClient::getClient(
                body: json_encode($this->freshData)
            )
        );

        $data = $this->freshData;
        $data['title'] = 'Ipsum';

        $response = $response->refresh(
            client: MockGuzzleClient::getClient(
                body: json_encode($data)
            )
        );

        expect($response->title)
            ->toBe($data['title']);

        $data['title'] = 'Dolor';

        $response = $response->fresh(
            client: MockGuzzleClient::getClient(
                body: json_encode($data)
            )
        );

        expect($response->title)
            ->toBe($data['title']);
    });

});
