<?php

// jsonplaceholder.typicode.com

require('./vendor/autoload.php');

use Acme\Api\{Request, Response, GetRequest, PostRequest};
use Acme\Api\Exceptions\ApiException;
use Acme\Api\StaticTokenAuthorizer;

use Acme\JsonPlaceholderApi\Api as JPApi;
use Acme\JsonPlaceholderApi\Requests\{
    GetPost, PostPosts, PutPost, SomeAuthorizedRequest
};

$api = new JPApi(
    'http://jsonplaceholder.typicode.com',
    new StaticTokenAuthorizer('s0m3st4tIkt0k3n')
);

$getPost = new GetPost(1);
$postPosts = (new PostPosts())->setTitle('Some title')->setText('My text')->setCategory('Tra-la-la');
$putPosts = (new PutPost(1))->setTitle('Some title')->setText('My text')->setCategory('Tra-la-la');
$authR = (new SomeAuthorizedRequest(1));
//print_r($api->call($getPost));

try {
    $resp = $api->call($authR);
    //print_r($resp);
} catch (\PDOException $e) {
    // $logger->error();
    if (strstr($e->getMessage(), 'connection closed')) {
        exit(123);
    }
} catch (ApiException $e) {
    $code = $e->getCode();
    $body = $e->getBody();
    $headers = $e->getHeaders();
    echo printf('API error: file %s:%d'.PHP_EOL, $e->getFile(), $e->getLine());
} catch (\Exception $e) {
    echo printf('Generic error: %s %d'.PHP_EOL, $e->getMessage(), $e->getCode());
} catch (\ParseError $e) {
    echo printf('Error loading file: %s'.PHP_EOL, $e->getFile());
} catch (\Throwable $e) {
    // E_ERROR  E_RECOVERABLE_ERROR
    // TypeError ParseError
    // Throwable (Exception implements Throwable)
    // Error (implements Throwable)
    print_r($e);
    echo '> '.get_class($e).PHP_EOL;
    echo 'Caught Throwable'.PHP_EOL;
} finally {
    echo 'API request finished';
}

echo PHP_EOL;
