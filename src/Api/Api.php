<?php

namespace Acme\Api;

use Acme\Interfaces\Api\{IApi, IResponse, IRequest, IContentTypeAware, IHttpMethodAware};
use Acme\Api\Exceptions\ApiException;

use Acme\Api\Response;

class Api implements IApi
{
    private $url;
    private $ch;

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

    /**
     * @throw \Exception
     */
    public function call(IRequest $request): IResponse
    {
        $method = $request->getMethod();

        printf('%s %s'.PHP_EOL, $method, (string)$request);

        $ch = $this->getCh($this->getUrl() . (string)$request);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->getHeaders());
        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        if (
            $method !== IHttpMethodAware::METHOD_GET
            && $method !== IHttpMethodAware::METHOD_DELETE
        ) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getRequestBody($request));
        }

        $response = curl_exec($ch);

        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
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
}