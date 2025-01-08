<?php

use Celoain\ApiWrapper\Api\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

describe('testing Response features', function () {
    beforeEach(closure: function () {

        $this->data = [
            'test' => 'abc',
        ];

        $this->json = json_encode($this->data);

        $this->response = new Response(new GuzzleResponse(
            200,
            [],
            $this->json,
            reason: 'OK'
        ));
    });
    it('gets the content', function () {
        expect($this->response->getContents())
            ->toBe($this->json);
    });
    it('gets json', function () {
        expect($this->response->json())
            ->toBe($this->data);
    });
    it('gets the protocol version', function () {
        $protocolVersion = '1.0';
        expect($this->response->getProtocolVersion())->toBe('1.1');
        $response = $this->response->withProtocolVersion($protocolVersion);

        expect($response->getProtocolVersion())->toBe($protocolVersion);

    });
    it('handles headers', function () {

        expect($this->response->getHeaders())
            ->toBe([])
            ->and($this->response->hasHeader('first-test-header'))
            ->toBeFalse()
            ->and($this->response->getHeaderLine('first-test-header'))
            ->toBe('');

        $response = $this->response->withHeader('first-test-header', 'testHeader');

        expect($response->hasHeader('first-test-header'))
            ->toBeTrue()
            ->and($response->getHeader('first-test-header'))
            ->toBe([0 => 'testHeader'])
            ->and($response->getHeaderLine('first-test-header'))
            ->toBe('testHeader');

        $response = $response->withAddedHeader('second-test-header', 'testHeader2');

        expect($response->hasHeader('first-test-header'))
            ->toBeTrue()
            ->and($response->hasHeader('second-test-header'))
            ->toBeTrue()
            ->and($response->getHeaders())
            ->toBe(['first-test-header' => [0 => 'testHeader'], 'second-test-header' => [0 => 'testHeader2']]);

        $response = $response->withoutHeader('first-test-header');

        expect($response->hasHeader('first-test-header'))
            ->toBeFalse()
            ->and($response->hasHeader('second-test-header'))
            ->toBeTrue()
            ->and($response->getHeaders())
            ->toBe(['second-test-header' => [0 => 'testHeader2']]);

    });

    it('handles status codes', function () {
        expect($this->response->getStatusCode())
            ->toBe(200)
            ->and($this->response->getReasonPhrase())
            ->toBe('OK');

        $response = $this->response->withStatus('404', 'Not Found');

        expect($response->getStatusCode())
            ->toBe(404)
            ->and($response->getReasonPhrase())
            ->toBe('Not Found');

    });

});
