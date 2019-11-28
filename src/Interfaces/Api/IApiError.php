<?php

namespace Acme\Interfaces\Api;

interface IApiError extends IResponse
{
    public function getMessage(): string;
    
}