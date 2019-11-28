<?php

namespace Acme\JsonPlaceholderApi\Requests;

use Acme\Api\PostRequest;

/**
 * POST single post
 * 
 * @link https://jsonplaceholder.typicode.com/posts Some description
 */
final class PostPosts extends PostRequest
{
    const URL = '/posts';

    public function __construct($data = [])
    {
        parent::__construct(self::URL);
        $this->setContentTypeJson();
        //@TODO: Set data
    }

    public function setTitle(string $title): PostPosts
    {
        $this->setData('title', $title);
        return $this;
    }

    public function setText(string $text): PostPosts
    {
        $this->setData('text', $text);
        return $this;
    }

    public function setCategory(string $cat): PostPosts
    {
        $this->setData('category', $cat);
        return $this;
    }
}
