<?php

namespace Acme\Api;

use Acme\Interfaces\Api\IRequest;
use Acme\Interfaces\Api\IContentTypeAware;
use Acme\Interfaces\Api\IHttpMethodAware;

class Request implements IRequest, IContentTypeAware, IHttpMethodAware
{
    private $method;
    private $url;
    private $headers = [];
    private $data = [];
    private $query = [];

    public function __construct(string $method, string $url)
    {
        $this->setMethod($method)->setUrl($url);
    }

    protected function setMethod(string $method): IRequest
    {
        $this->method = strtoupper($method);
        return $this;
    }

    protected function setUrl(string $url): IRequest
    {
        $this->url = $url;
        return $this;
    }

    public function setHeader(string $name, string $value): IRequest
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContentType(string $type): IRequest
    {
        return $this->setHeader(self::HEADER_CONTENT_TYPE, $type);
    }

    public function setContentTypeJson(): IRequest
    {
        return $this->setContentType(self::CONTENT_TYPE_JSON);
    }

    public function setData(string $key, string $value): IRequest
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function setQueryParam(string $key, string $value): IRequest
    {
        $this->query[$key] = $value;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getQueryParams(): array
    {
        return $this->query;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function __invoke(): array
    {
        return $this->getData();
    }

    public function __toString(): string
    {
        return $this->getUrl();
    }
}
