<?php

namespace Celoain\ApiWrapper\Api;

use Celoain\ApiWrapper\Contracts\Api\EndpointInterface;
use Celoain\ApiWrapper\Contracts\Api\RequestInterface;
use Celoain\ApiWrapper\Contracts\Api\ResponseInterface;
use Celoain\ApiWrapper\Contracts\Api\RouteInterface;
use Celoain\ApiWrapper\Exceptions\EndpointNotDefinedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Request implements RequestInterface
{
    protected Client $client;

    /**
     * @var array<string>
     */
    protected array $pathParams = [];

    /**
     * @var string[]
     */
    protected array $options = [];

    /**
     * @var class-string<RouteInterface>
     */
    protected static string $routeHandler = Route::class;

    public function __construct(protected EndpointInterface $endpoint, ?Client $client = null)
    {
        $this->client = $client ?? new Client;
    }

    /**
     * @param  array<mixed>  $pathParams
     */
    public function pathParams(array $pathParams): self
    {
        $this->pathParams = array_merge($this->pathParams, $pathParams);

        return $this;
    }

    /**
     * @param  string[]|string  $auth
     * @return $this
     */
    public function auth(array|string $auth): self
    {
        $this->options['auth'] = $auth;

        return $this;
    }

    /**
     * @param  array<string, string>  $headers
     */
    public function headers(array $headers): self
    {
        $this->options['headers'] = array_merge($this->options['headers'] ?? [], $headers);

        return $this;
    }

    /**
     * @param  array<string|array<string>>  $queryParams
     */
    public function queryParams(array $queryParams): Request
    {
        $this->options['query'] = array_merge($this->options['query'] ?? [], $queryParams);

        return $this;
    }

    public function body(string $body): self
    {
        $this->options['body'] = $body;

        return $this;
    }

    /**
     * @param  array<mixed>  $data
     */
    public function json(array $data): self
    {
        $this->options['json'] = array_merge($this->options['json'] ?? [], $data);

        return $this;
    }

    /**
     * @param  string[][]  $data
     */
    public function formParams(array $data): self
    {
        $this->options['form_params'] = array_merge($this->options['form_params'] ?? [], $data);

        return $this;
    }

    /**
     * @param  string[]  $data
     */
    public function multipart(array $data): self
    {
        $this->options['multipart'] = array_merge($this->options['multipart'] ?? [], $data);

        return $this;
    }

    /**
     * @param  string[]  $options
     */
    public function options(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function getMethod(): string
    {
        return $this->endpoint->getMethod()->value;
    }

    public function getUrl(): string
    {
        $url = $this->endpoint->getUrl();

        foreach ($this->pathParams as $key => $value) {
            $url = str_replace("{{$key}}", $value, $url);
        }

        return $url;
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @throws GuzzleException
     */
    public function send(): ResponseInterface
    {

        $callback = function (Request $request): ResponseInterface {
            return new Response($this->client->request(
                $request->getMethod(),
                $request->getUrl(),
                $request->getOptions()
            ));
        };

        foreach ($this->endpoint->getProcessors() as $processor) {
            $callback = function (RequestInterface $request) use ($callback, $processor): ResponseInterface {
                return $processor::handle($request, $callback);
            };
        }

        return $callback($this);
    }

    /**
     * @throws EndpointNotDefinedException
     */
    public static function route(string $name, ?Client $client = null): RequestInterface
    {
        return new self(self::$routeHandler::find($name), $client);
    }
}
