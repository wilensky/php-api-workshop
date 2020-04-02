<?php

namespace Acme\Api;

use Acme\Interfaces\Api\{IApi, IApiClient, IAuthorizedRequest, IResponse, IRequest, IContentTypeAware, IHttpMethodAware, IAuthorizer};
use Acme\Api\Exceptions\{ApiException, AuthorizerNeededException, BadJsonException};

use Acme\Api\Response;

class Api implements IApi
{
    private $url;
    private $authorizer;

    public function __construct(string $baseUrl, IApiClient $client)
    {
        $this->url = $baseUrl;
        $this->setClient($client);
    }

    private function setClient(IApiClient $client): IApi
    {
        $this->client = $client;
        return $this;
    }

    private function getClient(): IApiClient
    {
        return $this->client;
    }

    /**
     * @throw \Exception
     */
    private function getRequestBody(IRequest $req): ?string
    {
        $ctype = $req->getHeaders()['Content-Type'] ?: null;

        switch ($ctype) {
            case IContentTypeAware::CONTENT_TYPE_JSON:
                return json_encode($req());
                break;
            default:
                throw new \Exception('Unsupported Content-Type', 1010);
        }
    }

    /**
     * @throw \Exception
     */
    public function call(IRequest $request): IResponse
    {
        $method = $request->getMethod();

        //printf('%s %s'.PHP_EOL, $method, (string)$request);

        if ($request instanceof IAuthorizedRequest && !$this->hasAuthorizer()) {
            throw new AuthorizerNeededException('Authorizer needed', 1010);
        }

        if ($request instanceof IAuthorizedRequest) {
            $this->getAuthorizer()($request);
        }

        $client = $this->getClient();
        $client->setUrl($this->getUrl() . (string)$request);
        $client->setOpt(CURLOPT_CUSTOMREQUEST, $method);
        $client->setOpt(CURLOPT_RETURNTRANSFER, 1);
        $client->setOpt(CURLOPT_HTTPHEADER, $request->getHeaders());
        //$client->setOpt(CURLOPT_VERBOSE, 1);
        $client->setOpt(CURLOPT_HEADER, 1);

        if (
            $method !== IHttpMethodAware::METHOD_GET
            && $method !== IHttpMethodAware::METHOD_DELETE
        ) {
            $client->setOpt(CURLOPT_POST, 1);
            $client->setOpt(CURLOPT_POSTFIELDS, $this->getRequestBody($request));
        }

        $response = $client->exec();

        $code = $client->getStatusCode();
        $headerSize = $client->getHeaderSize();
        $body = substr($response, $headerSize);
        $headers = explode("\r\n", trim(substr($response, 0, $headerSize)));

        $hdrs = [];

        array_walk($headers, function (string $h) use (&$hdrs) {
            list($name, $val) = explode(': ', $h, 2);
            $hdrs[$name] = $val;
        });

        $json = json_decode($body, true);

        if ($json === null) {
            $err = json_last_error();
            $msg = json_last_error_msg();

            if ($msg) {
                throw new BadJsonException($msg, $err, $headers);
            }
        }

        if ($code >= 400) {
            throw new ApiException($json, $headers, $code);
        }

        return new Response($json, $hdrs, $code);
    }

    private function getUrl(): string
    {
        return $this->url;
    }

    protected function setAuthorizer(IAuthorizer $auth): IApi
    {
        $this->authorizer = $auth;
        return $this;
    }

    private function getAuthorizer(): ?IAuthorizer
    {
        return $this->authorizer;
    }

    private function hasAuthorizer(): bool
    {
        return $this->getAuthorizer() instanceof IAuthorizer;
    }

    public function __invoke(IRequest $req): IResponse
    {
        return $this->call($req);
    }
}