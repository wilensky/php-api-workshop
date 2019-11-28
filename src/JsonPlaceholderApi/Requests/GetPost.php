<?php

namespace Acme\JsonPlaceholderApi\Requests;

use Acme\Api\GetRequest;

/**
 * GET single post
 * 
 * @link https://jsonplaceholder.typicode.com/posts/1 Some description
 */
final class GetPost extends GetRequest
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