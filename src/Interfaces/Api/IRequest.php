<?php

namespace Acme\Interfaces\Api;

interface IRequest
{
    //@TODO: getResponseFQCN()
    public function setHeader(string $name, string $value): IRequest;
    public function setData(string $key, string $value): IRequest;
    public function setQueryParam(string $key, string $value): IRequest;

    public function getHeaders(): array;
    public function getData(): array;
    public function getQueryParams(): array;

    public function getMethod(): string;
    public function getUrl(): string;

    public function __toString();
    public function __invoke();
}
