<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\HttpSDK\V3;

use Musement\SDK\MusementCatalog\Exception\RuntimeException;
use Musement\SDK\MusementCatalog\HttpSDK\ApiMapInterface;
use Musement\SDK\MusementCatalog\HttpSDK\Endpoint;
use Musement\SDK\MusementCatalog\HttpSDK\Params;
use Musement\SDK\MusementCatalog\HttpSDK\Url;
use Musement\SDK\MusementCatalog\Model\Locale;

final class ApiMap implements ApiMapInterface
{
    private $baseUrl;

    public function __construct(Url $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function headers(Locale $locale): array
    {
        return [
            'Accept-Language' => (string) $locale,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function method(Endpoint $endpoint): string
    {
        return 'GET';
    }

    public function url(Endpoint $endpoint, Params $parameters = null): string
    {
        $parameters = $parameters ?: new Params();

        switch (true) {
            case $endpoint->isEqual(Endpoint::cities()):
                return $this->createUrl('/api/v3/cities', $parameters);

            case $endpoint->isEqual(Endpoint::activities()):
                return $this->createUrl(
                    sprintf('/api/v3/cities/%d/activities', $parameters->get($cityKey = 'cityId')),
                    $parameters->remove($cityKey)
                );

            default:
                throw new RuntimeException('Invalid endpoint ' . $endpoint);

        }
    }

    private function createUrl(string $path, Params $parameters): string
    {
        return $this->baseUrl . $path . $parameters->toQueryString();
    }
}
