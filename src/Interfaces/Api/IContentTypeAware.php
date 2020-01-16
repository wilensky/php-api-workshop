<?php

namespace Acme\Interfaces\Api;

interface IContentTypeAware
{
    const HEADER_CONTENT_TYPE = 'Content-Type';

    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_XML = 'application/xml';
    const CONTENT_TYPE_HTML = 'application/html';
    const CONTENT_TYPE_TEXT = 'text/plain';
}