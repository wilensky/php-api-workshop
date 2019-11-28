<?php

namespace Acme\Interfaces\Api;

interface IAuthorizer
{
    public function __invoke(IRequest $request): IRequest;
}
