<?php

namespace Acme\JsonPlaceholderApi;

use Acme\Api\Api as BaseApi;
use Acme\Api\StaticTokenAuthorizer;
use Acme\Api\HttpApiClient;

final class Api extends BaseApi
{
    public function __construct(string $url, StaticTokenAuthorizer $auth, HttpApiClient $client)
    {
        parent::__construct($url, $client);
        $this->setAuthorizer($auth);
    }
}