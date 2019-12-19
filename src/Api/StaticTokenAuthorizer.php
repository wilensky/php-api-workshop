<?php

namespace Acme\Api;

use Acme\Interfaces\Api\IAuthorizer;
use Acme\Interfaces\Api\IRequest;

final class StaticTokenAuthorizer implements IAuthorizer
{
    private $token;

    public function __construct(string $token)
    {
        $this->setToken($token);
    }

    private function setToken(string $token): StaticTokenAuthorizer
    {
        $this->token = $token;
        return $this;
    }

    private function getToken(): string
    {
        return $this->token;
    }

    public function __invoke(IRequest $request): IRequest
    {
        return $request->setHeader('Authorization', 'Bearer '.$this->getToken());
    }
}