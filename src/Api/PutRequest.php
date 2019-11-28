<?php
namespace Acme\Api;

class PutRequest extends Request {
    public function __construct(string $url)
    {
        parent::__construct(self::METHOD_PUT, $url);
    }
}