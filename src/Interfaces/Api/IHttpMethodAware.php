<?php

namespace Acme\Interfaces\Api;

interface IHttpMethodAware
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_HEAD = 'HEAD';
    const METHOD_DELETE = 'DELETE';
}