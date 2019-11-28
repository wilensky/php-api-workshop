<?php
namespace Acme\Api;

class PostRequest extends Request {
    public function __construct(string $url)
    {
        parent::__construct(self::METHOD_POST, $url);
    }
}