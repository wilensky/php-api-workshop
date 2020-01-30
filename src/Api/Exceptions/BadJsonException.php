<?php

namespace Acme\Api\Exceptions;

class BadJsonException extends ApiException
{
    public function __construct(string $msg, int $code = 200, array $headers = [])
    {
        parent::__construct([], $headers, $code, $msg);
    }
}
