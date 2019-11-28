<?php
namespace Acme\Api;

class GetRequest extends Request {
    public function __construct(string $url)
    {
        parent::__construct(self::METHOD_GET, $url);
    }
}