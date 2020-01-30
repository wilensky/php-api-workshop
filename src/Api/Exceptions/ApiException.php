<?php

namespace Acme\Api\Exceptions;

class ApiException extends \Exception
{
    private $body;
    private $headers;

    public function __construct(array $body = [], array $headers = [], int $code = 0, string $msg = 'API error')
    {
        parent::__construct($msg, $code);

        $this->setBody($body)->setHeaders($headers);
    }

    private function setBody(array $body = []): ApiException
    {
        $this->body = $body;
        return $this;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    private function setHeaders(array $headers = []): ApiException
    {
        $this->headers = $headers;
        return $this;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }
}