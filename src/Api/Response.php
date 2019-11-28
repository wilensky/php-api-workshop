<?php

namespace Acme\Api;

use Acme\Interfaces\Api\IResponse;
use Acme\Interfaces\Api\IContentTypeAware;

class Response implements IResponse, IContentTypeAware
{
    private $statusCode;
    private $headers = [];
    private $data = [];

    public function __construct(array $data, array $headers = [], int $code = 0)
    {
        $this->setData($data)
            ->setHeaders($headers)
            ->setStatusCode($code);
    }

    protected function setData(array $data): IResponse
    {
        $this->data = $data;
        return $this;
    }

    protected function setHeaders(array $headers): IResponse
    {
        $this->headers = $headers;
        return $this;
    }

    protected function setStatusCode(int $code): IResponse
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    public function getData(): array
    {
        return $this->data ?: [];
    }

    public function getStatusCode(): int
    {
        return (int)$this->statusCode;
    }
}