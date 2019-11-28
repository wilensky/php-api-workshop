<?php

namespace Acme\Interfaces\Api;

interface IApi
{
    public function call(IRequest $request): IResponse;
}
