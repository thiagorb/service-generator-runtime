<?php

namespace Thiagorb\ServiceGeneratorRuntime;

use Psr\Http\Message\RequestInterface;

interface RequestFactory
{
    /**
     * @return RequestInterface
     */
    public function create(string $method, string $url, array $data = null): RequestInterface;
}
