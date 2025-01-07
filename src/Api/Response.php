<?php

namespace Celoain\ApiWrapper\Api;

use Celoain\ApiWrapper\Contracts\Api\ResponseInterface as ApiResponseInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use stdClass;

class Response implements ApiResponseInterface
{
    public function __construct(protected ResponseInterface $response) {}

    public function getContents(): string
    {
        $body = $this->getBody();
        $body->rewind();

        return $body->getContents();
    }

    public function json(bool $associative = true): stdClass|array|null
    {
        return json_decode($this->getContents(), $associative);
    }

    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion();
    }

    public function withProtocolVersion(string $version): self
    {
        return new self($this->response->withProtocolVersion($version));
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    public function hasHeader(string $name): bool
    {
        return $this->response->hasHeader($name);
    }

    public function getHeader(string $name): array
    {
        return $this->response->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->response->getHeaderLine($name);
    }

    public function withHeader(string $name, string $value): self
    {
        return new self($this->response->withHeader($name, $value));
    }

    public function withAddedHeader(string $name, string $value): self
    {
        return new self($this->response->withAddedHeader($name, $value));
    }

    public function withoutHeader(string $name): self
    {
        return new self($this->response->withoutHeader($name));
    }

    public function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }

    public function withBody(StreamInterface $body): self
    {
        return new self($this->response->withBody($body));
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function withStatus(int|string $code, string $reasonPhrase = ''): self
    {
        return new self($this->response->withStatus($code, $reasonPhrase));
    }

    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }
}
