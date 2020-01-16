<?php

namespace Acme\Tests\Api;

use PHPUnit\Framework\TestCase;

use Acme\Api\Request;
use Acme\Interfaces\Api\IContentTypeAware as ICTA;

final class RequestTest extends TestCase
{
    private $instance;

    protected function getInstance($method = 'GET', $url = 'some/url'): Request
    {
        if (!($this->instance instanceof Request)) {
            $this->instance = new Request($method, $url);
        }

        return $this->instance;
    }

    protected function tearDown(): void
    {
        $this->instance = null;
    }

    public function testSetGetMethod()
    {
        $method = 'GET';
        $url = 'some/url';

        $req = $this->getInstance($method, $url);

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

    public function testSetHeader()
    {
        $req = $this->getInstance();

        $h = ['Header-Name', 'HaderValue'];
        $req->setHeader(...$h);

        $headers = $req->getHeaders();

        $this->assertArrayHasKey(
            $h[0],
            $headers,
            'Failed asserting that header exists in resulting array'
        );

        $this->assertEquals(
            $h[1],
            $headers[$h[0]],
            'Failed asserting header value correctly set'
        );
    }

    public function testSetContentType()
    {
        $req = $this->getInstance();

        $req->setContentType(ICTA::CONTENT_TYPE_TEXT);

        $headers = $req->getHeaders();

        $this->assertArrayHasKey(
            ICTA::HEADER_CONTENT_TYPE,
            $headers
        );

        $this->assertEquals(
            ICTA::CONTENT_TYPE_TEXT,
            $headers[ICTA::HEADER_CONTENT_TYPE]
        );
    }

    public function testSetData()
    {
        $req = $this->getInstance();

        $data = ['one' => 1, 'two' => 2, 'three' => 'three'];

        foreach ($data as $k => $v) {
            $req->setData($k, $v);
        }

        $res = $req();

        foreach ($data as $k => $v) {
            $this->assertArrayHasKey($k, $res);
            $this->assertEquals($v, $res[$k]);
        }
    }
}
