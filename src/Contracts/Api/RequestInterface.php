<?php

namespace Celoain\ApiWrapper\Contracts\Api;

use GuzzleHttp\Client;

interface RequestInterface
{
    /**
     * @param  array<mixed>  $pathParams
     */
    public function pathParams(array $pathParams): self;

    /**
     * @param  string[]|string  $auth
     */
    public function auth(array|string $auth): self;

    /**
     * @param  array<string, string>  $headers
     */
    public function headers(array $headers): self;

    /**
     * @param  array<string|array<string>>  $queryParams
     */
    public function queryParams(array $queryParams): RequestInterface;

    public function body(string $body): self;

    /**
     * @param  array<mixed>  $data
     */
    public function json(array $data): self;

    /**
     * @param  string[][]  $data
     */
    public function formParams(array $data): self;

    /**
     * @param  string[]  $data
     */
    public function multipart(array $data): self;

    /**
     * @param  string[]  $options
     */
    public function options(array $options): self;

    public function getMethod(): string;

    public function getUrl(): string;

    /**
     * @return string[]
     */
    public function getOptions(): array;

    public function send(): ResponseInterface;

    public static function route(string $name, ?Client $client = null): RequestInterface;
}
