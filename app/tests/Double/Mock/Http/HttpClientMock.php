<?php

declare(strict_types=1);

namespace Musement\Tests\Double\Mock\Http;

use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientMock implements ClientInterface
{
    /** @var ExpectedRequest[] */
    protected $expectedRequests = [];

    public function expects(string $method, string $url, ResponseInterface $response): self
    {
        $this->expectedRequests[] = new ExpectedRequest($method, $url, $response);

        return $this;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        foreach ($this->expectedRequests as $possibleRequest) {
            if ($possibleRequest->isEqual($request)) {
                return $possibleRequest->response();
            }
        }

        return new Response(501, [], \json_encode([
            'type' => 'http client mock error - response mock not found for request',
            'request' => [
                'method' => $request->getMethod(),
                'url' => (string) $request->getUri(),
            ],
        ]));
    }
}
