<?php

namespace Celoain\ApiWrapper\Contracts\Api;

use Psr\Http\Message\StreamInterface;
use stdClass;

interface ResponseInterface
{
    public function getContents(): string;

    /**
     * @return stdClass|array<mixed>|null
     */
    public function json(bool $associative = true): stdClass|array|null;

    public function getProtocolVersion(): string;

    public function withProtocolVersion(string $version): self;

    /**
     * @return string[][]
     */
    public function getHeaders(): array;

    public function hasHeader(string $name): bool;

    /**
     * @return array<string, string>
     */
    public function getHeader(string $name): array;

    public function getHeaderLine(string $name): string;

    public function withHeader(string $name, string $value): self;

    public function withAddedHeader(string $name, string $value): self;

    public function withoutHeader(string $name): self;

    public function getBody(): StreamInterface;

    public function withBody(StreamInterface $body): self;

    public function getStatusCode(): int;

    public function withStatus(int $code, string $reasonPhrase = ''): self;

    public function getReasonPhrase(): string;
}
