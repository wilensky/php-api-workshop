<?php

namespace Acme\Tests\Api\Exceptions;

use PHPUnit\Framework\TestCase;

use Acme\Api\Exceptions\ApiException;

final class ApiExceptionTest extends TestCase
{
    public function testDefault()
    {
        $ex = new ApiException();

        try {
            throw $ex;
        } catch (ApiException $e) {
            $this->assertEquals([], $e->getBody());
            $this->assertEquals([], $e->getHeaders());
            $this->assertEquals(0, $e->getCode());
            $this->assertEquals('API error', $e->getMessage());
        }
    }

    public function testNonDefault()
    {
        $args = [
            ['some' => 'body'],
            ['hea' => 'ders'],
            404
        ];

        $ex = new ApiException(...$args);

        try {
            throw $ex;
        } catch (ApiException $e) {
            $this->assertEquals($args[0], $e->getBody());
            $this->assertEquals($args[1], $e->getHeaders());
            $this->assertEquals($args[2], $e->getCode());
            $this->assertEquals('API error', $e->getMessage());
        }
    }
}
