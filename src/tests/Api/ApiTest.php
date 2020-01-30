<?php

namespace Acme\Tests\Api;

use PHPUnit\Framework\TestCase;

use Acme\Api\{Api, Request, HttpApiClient};
use Acme\Api\Exceptions\{ApiException, BadJsonException};
use Acme\Interfaces\Api\IResponse;

final class ApiTest extends TestCase
{
    private $api;
    private $request;

    private $httpHeaders = 'HTTP/1.1 200 OK
Date: Thu, 16 Jan 2020 09:16:44 GMT
Content-Type: application/json; charset=utf-8
Content-Length: 292
Connection: keep-alive
Set-Cookie: __cfduid=dbc6f19fe0c8cc3f02dcdc99c75ae0e981579166204; expires=Sat, 15-Feb-20 09:16:44 GMT; path=/; domain=.typicode.com; HttpOnly; SameSite=Lax
X-Powered-By: Express
Vary: Origin, Accept-Encoding
Access-Control-Allow-Credentials: true
Cache-Control: max-age=14400
Pragma: no-cache
Expires: -1
X-Content-Type-Options: nosniff
Etag: W/"124-yiKdLzqO5gfBrJFrcdJ8Yq0LGnU"
Via: 1.1 vegur
CF-Cache-Status: HIT
Age: 3338
Accept-Ranges: bytes
Server: cloudflare
CF-RAY: 555efe0c1e3bf2c8-WAW

';

    private $httpHeadersSize = 635;

    protected function getRequest(string $method = 'GET', string $url = 'some/url'): Request
    {
        if (!($this->request instanceof Request)) {
            $this->request = new Request($method, $url);
        }

        return $this->request;
    }

    protected function getApi(string $baseUrl = '', array $response = []): Api
    {
        $stub = $this->createMock(HttpApiClient::class);
        $stub->method('exec')->willReturn($response[0]);
        $stub->method('getHeaderSize')->willReturn($this->httpHeadersSize);
        $stub->method('getStatusCode')->willReturn($response[1]);

        if (!($this->api instanceof Api)) {
            $this->api = new Api($baseUrl, $stub);
        }

        return $this->api;
    }

    protected function tearDown(): void
    {
        $this->api = null;
        $this->request = null;
    }

    public function responsesDP(): array
    {
        return [
            [
                $this->httpHeaders.'{"userId": 1,"id": 1}',
                200,
                null
            ],
            [
                $this->httpHeaders.'{}',
                400,
                ApiException::class
            ],
            [
                $this->httpHeaders.'{soms33tyJS0n}',
                200,
                BadJsonException::class
            ],
            [
                $this->httpHeaders.'{soms33tyJS0n}',
                500,
                BadJsonException::class
            ]
        ];
    }

    /**
     * @dataProvider responsesDP
     */
    public function testTestRequest(string $response, int $code, string $e = null)
    {
        if ($e !== null) {
            $this->expectException($e);
        }

        $req = $this->getRequest('GET', '/posts/1');
        $api = $this->getApi('http://sgdfgjcfgj.com', [$response, $code]);

        /**
         * @var IResponse
         */
        $res = $api($req);

        $this->assertInstanceOf(IResponse::class, $res, 'Check intance');
        $this->assertEquals($code, $res->getStatusCode(), 'Check status code');
        $this->assertTrue(is_array($res->getHeaders()));
        $this->assertTrue(is_array($res->getData()));
    }
}
