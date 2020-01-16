<?php

namespace Acme\Api;

use Acme\Interfaces\Api\{IApi, IAuthorizedRequest, IResponse, IRequest, IContentTypeAware, IHttpMethodAware, IAuthorizer};
use Acme\Api\Exceptions\ApiException;

use Acme\Api\Response;

class Api implements IApi
{
    private $url;
    private $ch;
    private $authorizer;

    public function __construct(string $baseUrl)
    {
        $this->url = $baseUrl;
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

    private function setOpt(string $opt, $val): Api
    {
        curl_setopt($this->getCh(), $opt, $val);
        return $this;
    }

    private function getInfo(string $param)
    {
        return curl_getinfo($this->getCh(), $param);
    }

    public function getStatusCode(): int
    {
        return (int)$this->getInfo(CURLINFO_HTTP_CODE);
    }

    public function getHeaderSize(): int
    {
        return (int)$this->getInfo(CURLINFO_HEADER_SIZE);
    }

    public function exec(): string
    {
        return curl_exec($this->getCh());
    }

    /**
     * @throw \Exception
     */
    public function call(IRequest $request): IResponse
    {
        $method = $request->getMethod();

        printf('%s %s'.PHP_EOL, $method, (string)$request);

        if ($request instanceof IAuthorizedRequest && !$this->hasAuthorizer()) {
            throw new \Exception('Authorizer needed', 1010);
        }

        if ($request instanceof IAuthorizedRequest) {
            $this->getAuthorizer()($request);
        }

        $ch = $this->getCh($this->getUrl() . (string)$request);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, $method);
        $this->setOpt(CURLOPT_RETURNTRANSFER, 1);
        $this->setOpt(CURLOPT_HTTPHEADER, $request->getHeaders());
        //$this->setOpt(CURLOPT_VERBOSE, 1);
        $this->setOpt(CURLOPT_HEADER, 1);

        if (
            $method !== IHttpMethodAware::METHOD_GET
            && $method !== IHttpMethodAware::METHOD_DELETE
        ) {
            $this->setOpt(CURLOPT_POST, 1);
            $this->setOpt(CURLOPT_POSTFIELDS, $this->getRequestBody($request));
        }

        $response = $this->exec();

        $code = $this->getStatusCode();
        $headerSize = $this->getHeaderSize();
        $body = substr($response, $headerSize);
        $headers = explode("\r\n", trim(substr($response, 0, $headerSize)));

        $hdrs = [];

        array_walk($headers, function (string $h) use (&$hdrs) {
            list($name, $val) = explode(': ', $h, 2);
            $hdrs[$name] = $val;
        });

        $json = json_decode($body, true);

        if ($code >= 400) {
            throw new ApiException($json, $headers, $code);
        }

        return new Response($json, $hdrs, $code);
    }

    private function getCh(string $url = null)
    {
        if (is_resource($this->ch)) {
            return $this->ch;
        }

        $this->ch = curl_init($url);

        return $this->ch;
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