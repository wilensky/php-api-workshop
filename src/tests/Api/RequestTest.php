<?php

namespace Acme\Tests\Api;

use PHPUnit\Framework\TestCase;

use Acme\Api\Request;

final class RequestTest extends TestCase
{
    public function testSetGetMethod()
    {
        $method = 'GET';
        $url = 'some/url';

        $req = new Request($method, $url);

        $this->assertEquals(
            $method,
            $req->getMethod(),
            'Failed asserting that HTTP method was set correctly'
        );

        $this->assertEquals(
            $url,
            (string)$req,
            'Failed asserting that URL was set correctly'
        );
    }
}
