<?php

namespace Acme\Interfaces\Api;

interface IResponse
{
    public function getHeaders(): array;
    public function getHeader(string $name): ?string;
    public function getData(): array;
    public function getStatusCode(): int;
}
