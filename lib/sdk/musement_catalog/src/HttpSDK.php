<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog;

use Musement\Component\Accessor\JsonAccessor;
use Musement\SDK\MusementCatalog\Exception\ApiException;
use Musement\SDK\MusementCatalog\Exception\RuntimeException;
use Musement\SDK\MusementCatalog\HttpSDK\ActivitiesFactoryInterface;
use Musement\SDK\MusementCatalog\HttpSDK\ApiMapInterface;
use Musement\SDK\MusementCatalog\HttpSDK\CitiesFactoryInterface;
use Musement\SDK\MusementCatalog\HttpSDK\Endpoint;
use Musement\SDK\MusementCatalog\HttpSDK\Params;
use Musement\SDK\MusementCatalog\Model\Activities;
use Musement\SDK\MusementCatalog\Model\Cities;
use Musement\SDK\MusementCatalog\Model\Locale;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

final class HttpSDK implements SDKInterface
{
    private $httpClient;
    private $requestFactory;
    private $apiMap;
    private $citiesFactory;
    private $activitiesFactory;
    private $logger;

    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        ApiMapInterface $apiMap,
        CitiesFactoryInterface $citiesFactory,
        ActivitiesFactoryInterface $activitiesFactory,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->apiMap = $apiMap;
        $this->citiesFactory = $citiesFactory;
        $this->activitiesFactory = $activitiesFactory;
        $this->logger = $logger;
    }

    public function getCities(Locale $locale, int $limit = 20, int $offset = 0): Cities
    {
        $requestId = \uniqid();

        $request = $this->createRequest(Endpoint::cities(), $locale, new Params([
            'limit' => $limit,
            'offset' => $offset,
        ]));

        $this->logger->debug('Fetch cities pre-request.', [
            'requestId' => $requestId,
            'headers' => $request->getHeaders(),
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
        ]);

        try {
            $response = $this->httpClient->sendRequest($request);

            if ($response->getStatusCode() !== 200) {
                throw new ApiException(
                    $response->getStatusCode(),
                    (string) $response->getBody()
                );
            }

            $this->logger->debug('Fetch cities post-request.', [
                'requestId' => $requestId,
                'statusCode' => $response->getStatusCode(),
                'body' => (string)$response->getBody(),
            ]);

            return $this->citiesFactory->create(
                $locale,
                new JsonAccessor(
                    (string)$response->getBody()
                )
            );
        } catch (ApiException $e) {
            $this->logger->warning('Error while trying to fetch cities.', [
                'requestId' => $requestId,
                'headers' => $request->getHeaders(),
                'method' => $request->getMethod(),
                'url' => $request->getUri(),
                'status' => $e->statusCode(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        } catch (\Throwable $e) {
            $this->logger->warning('Fatal error while trying to fetch cities.', [
                'requestId' => $requestId,
                'headers' => $request->getHeaders(),
                'method' => $request->getMethod(),
                'url' => $request->getUri(),
                'exception' => $e->getMessage(),
                'class' => get_class($e),
            ]);

            throw new RuntimeException($e->getMessage(), $e);
        }
    }

    public function getActivities(Locale $locale, int $cityId, int $limit = 20, int $offset = 0): Activities
    {
        $requestId = \uniqid();

        $request = $this->createRequest(Endpoint::activities(), $locale, new Params([
            'limit' => $limit,
            'offset' => $offset,
            'cityId' => $cityId,
        ]));

        $this->logger->debug('Fetch activities pre-request.', [
            'requestId' => $requestId,
            'headers' => $request->getHeaders(),
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
        ]);

        try {
            $response = $this->httpClient->sendRequest($request);

            if ($response->getStatusCode() !== 200) {
                throw new ApiException(
                    $response->getStatusCode(),
                    (string) $response->getBody()
                );
            }

            $this->logger->debug('Fetch activities post-request.', [
                'requestId' => $requestId,
                'statusCode' => $response->getStatusCode(),
                'body' => (string)$response->getBody(),
            ]);

            return $this->activitiesFactory->create(
                $locale,
                new JsonAccessor(
                    (string)$response->getBody()
                )
            );
        } catch (ApiException $e) {
            $this->logger->warning('Error while trying to fetch activities.', [
                'requestId' => $requestId,
                'headers' => $request->getHeaders(),
                'method' => $request->getMethod(),
                'url' => $request->getUri(),
                'status' => $e->statusCode(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        } catch (\Throwable $e) {
            $this->logger->warning('Fatal error while trying to fetch activities.', [
                'requestId' => $requestId,
                'headers' => $request->getHeaders(),
                'method' => $request->getMethod(),
                'url' => $request->getUri(),
                'exception' => $e->getMessage(),
                'class' => get_class($e),
            ]);

            throw new RuntimeException($e->getMessage(), $e);
        }
    }

    private function createRequest(Endpoint $endpoint, Locale $locale, Params $params): RequestInterface
    {
        $request = $this->requestFactory->createRequest(
            $this->apiMap->method($endpoint),
            $this->apiMap->url($endpoint, $params)
        );

        foreach ($this->apiMap->headers($locale) as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        return $request;
    }
}
