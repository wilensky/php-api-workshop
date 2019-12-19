<?php

namespace Acme\JsonPlaceholderApi\Requests;

use Acme\Api\GetRequest;
use Acme\Interfaces\Api\IAuthorizedRequest;

/**
 * GET single post
 * 
 * @link https://jsonplaceholder.typicode.com/posts/1 Some description
 */
final class SomeAuthorizedRequest extends GetRequest implements IAuthorizedRequest
{
    const URL = '/posts/';

    /**
     * @var int $id Post id
     */
    public function __construct(int $id)
    {
        parent::__construct($this->processUrl($id));
    }

    private function processUrl(int $id)
    {
        return self::URL . $id;
    }
}