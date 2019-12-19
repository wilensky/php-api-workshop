<?php

namespace Acme\JsonPlaceholderApi;

use Acme\Api\Api as BaseApi;
use Acme\Api\StaticTokenAuthorizer;

final class Api extends BaseApi
{
    public function __construct(string $url, StaticTokenAuthorizer $auth)
    {
        parent::__construct($url);
        $this->setAuthorizer($auth);
    }
}