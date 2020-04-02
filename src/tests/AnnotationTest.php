<?php

namespace Acme\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class AnnotationTest extends TestCase
{
    /**
     * @before
     */
    protected function setUp1() {
        // echo 'setUp1'.PHP_EOL;
    }

    /**
     * @before
     */
    protected function setUp2() {
        // echo 'setUp2'.PHP_EOL;
    }

    /**
     * @after
     */
    protected function setUp3() {
        // echo 'setUp3'.PHP_EOL;
    }

    protected function setUp(): void
    {
        // echo PHP_EOL.'setUp'.PHP_EOL;
    }

    protected function tearDown(): void
    {
        // echo 'tearDown'.PHP_EOL;
    }

    /**
     * @group users
     */
    public function testSmth1()
    {
        $this->assertTrue(true);
        //echo 'TEST1'.PHP_EOL;
    }

    /**
     * @group users
     */
    public function testSmth2()
    {
        $this->assertTrue(true);
        //echo 'TEST2'.PHP_EOL;
    }

    /**
     * @group pots
     */
    public function testSmth3()
    {
        $this->assertTrue(true);
        //echo 'TEST3'.PHP_EOL;
    }

    /**
     * @group random
     * @group random2
     * @author myjaja
     * @ticket JIRA-123
     * @large @medium @small
     */
    public function testSmth4()
    {
        $this->assertTrue(true);
        //echo 'TEST4'.PHP_EOL;
    }

    /**
     * @test
     * @testdox My test description
     */
    public function myRandomN4m3()
    {
        $this->assertTrue(true, 'Failed asserting that phpunit is dumb');
        //echo 'RANDOM NAME TEST'.PHP_EOL;
    }

    /**
     * @testWith [1, "one"]
     *           [2, "two"]
     */
    public function testDataProvider(...$args)
    {
        //print_r($args);
        $this->assertTrue(true);
    }

    public function testCreateUser()
    {
        //echo 'CREATING USER'.PHP_EOL;
        // Creating user and getting back ID
        $userId = 123;
        $this->assertTrue(true);
        return $userId;
    }

    /**
     * @depends testCreateUser
     */
    public function testCreatePost(int $userId)
    {
        //echo 'Received User id for post creation: '.$userId.PHP_EOL;
        // Creating a post with user id
        $postId = 456;
        $this->assertTrue(true);
        return $postId;
    }

    /**
     * @depends testCreatePost
     */
    public function testSerializePost(int $postId)
    {
        //echo 'Received Post id for serialization: '.$postId.PHP_EOL;
        // Serializing existing post
        $this->assertTrue(true);
    }

    public function testMethodsConditions()
    {
        $this->assertTrue(true);
        $this->assertFalse(false);
        $this->assertEquals(123, '123'); // ==
        $this->assertSame(123, 123); // ===
        $this->assertGreaterThan(3, 5);
        $this->assertGreaterThanOrEqual(3, 5);
        $this->assertLessThan(5, 3);
        $this->assertLessThanOrEqual(5, 3);
    }

    public function testMethodsTypes()
    {
        $this->assertNull(null);
        $this->assertIsArray([]);
        $this->assertIsBool(true);
        $this->assertIsFloat(1.0);
        $this->assertIsInt(1);
        $this->assertIsNumeric('123');
        $this->assertIsString('this is a string');
        $this->assertIsObject((object)[1 => 2]);
        $this->assertIsScalar(123);
        $this->assertIsResource(curl_init());
        $this->assertIsCallable(function ($var) { });
    }

    public function testMethodsArray()
    {
        $a = ['my' => 1, 2 => 3];

        $this->assertArrayHasKey('my', $a); // isset()
        $this->assertCount(2, $a); // count($a) === X
        $this->assertContains(3, $a); // in_array
        $this->assertNotEmpty($a); // is_empty ~ count($a) === 0
        $this->assertEmpty([]); // is_empty ~ count($a) === 0
    }

    public function testMethodsString()
    {
        $s = 'test string 1234';

        $this->assertStringContainsString('test', $s);
        $this->assertRegExp('/\d+$/i', $s);
        $this->assertStringMatchesFormat('%s string %d', $s); // sprtinf('My %s string %d', $x, 124);
    }

    public function testMethodsFile()
    {
        $this->assertFileExists('./index.php');
        $this->assertFileIsReadable('./index.php');
        $this->assertFileIsWritable('./index.php');
    }
}
