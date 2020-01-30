<?php

namespace Acme\Interfaces\Api;

interface IApiClient
{
    public function getStatusCode(): int;
    public function getHeaderSize(): int;
    public function setOpt($opt, $val): IApiClient;
    public function setUrl(): IApiClient;
    public function exec(): string;
}
