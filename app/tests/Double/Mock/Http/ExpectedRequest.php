<?php

declare(strict_types=1);

namespace Musement\Tests\Double\Mock\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExpectedRequest
{
    private $method;
    private $url;
    private $response;

    public function __construct(string $method, string $url, ResponseInterface $response)
    {
        $this->method = \strtoupper($method);
        $this->url = $url;
        $this->response = $response;
    }

    public function response(): ResponseInterface
    {
        return $this->response;
    }

    public function isEqual(RequestInterface $request): bool
    {
        return $this->method === $request->getMethod()
            && $this->url === (string) $request->getUri();
    }
}
