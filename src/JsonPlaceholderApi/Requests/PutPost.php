<?php

namespace Acme\JsonPlaceholderApi\Requests;

use Acme\Api\PutRequest;

/**
 * PUT single post
 * 
 * @link https://jsonplaceholder.typicode.com/posts/1 Some description
 */
final class PutPost extends PutRequest
{
    const URL = '/posts/';

    /**
     * @var int $id Post id
     */
    public function __construct(int $id)
    {
        parent::__construct($this->processUrl($id));
        $this->setContentTypeJson();
    }

    private function processUrl(int $id)
    {
        return self::URL . $id;
    }


    public function setTitle(string $title): PutPost
    {
        $this->setData('title', $title);
        return $this;
    }

    public function setText(string $text): PutPost
    {
        $this->setData('text', $text);
        return $this;
    }

    public function setCategory(string $cat): PutPost
    {
        $this->setData('category', $cat);
        return $this;
    }
}
