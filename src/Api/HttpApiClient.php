<?php

namespace Acme\Api;

use Acme\Interfaces\Api\{IApi, IApiClient};

class HttpApiClient implements IApiClient
{
    private $url;
    private $ch;

    public function __construct()
    {
    }

    public function setUrl(string $url = null): IApiClient
    {
        if (is_resource($this->ch)) {
            return $this->ch;
        }

        $this->ch = curl_init($url);

        return $this;
    }

    private function getCh()
    {
        return $this->ch;
    }

    public function setOpt($opt, $val): IApiClient
    {
        curl_setopt($this->getCh(), $opt, $val);
        return $this;
    }

    public function getInfo(string $param)
    {
        return curl_getinfo($this->getCh(), $param);
    }

    public function getStatusCode(): int
    {
        return (int)$this->getInfo(CURLINFO_HTTP_CODE);
    }

    public function getHeaderSize(): int
    {
        return (int)$this->getInfo(CURLINFO_HEADER_SIZE);
    }

    public function exec(): string
    {
        return curl_exec($this->getCh());
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function __invoke(): string
    {
        return $this->exec();
    }
}