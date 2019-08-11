<?php

declare(strict_types=1);

namespace Musement\SDK\Musement;

use Http\Message\RequestFactory;
use Musement\Component\Accessor\JsonAccessor;
use Musement\SDK\Musement\Exception\ApiException;
use Musement\SDK\Musement\Exception\RuntimeException;
use Musement\SDK\Musement\HttpSDK\ActivitiesFactoryInterface;
use Musement\SDK\Musement\HttpSDK\ApiMapInterface;
use Musement\SDK\Musement\HttpSDK\CitiesFactoryInterface;
use Musement\SDK\Musement\HttpSDK\Endpoint;
use Musement\SDK\Musement\HttpSDK\Params;
use Musement\SDK\Musement\Model\Activities;
use Musement\SDK\Musement\Model\Cities;
use Musement\SDK\Musement\Model\Locale;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

final class HttpSDK implements SDKInterface
{
    private $httpClient;
    private $apiMap;
    private $citiesFactory;
    private $activitiesFactory;
    private $logger;
    private $requestFactory;

    public function __construct(
        ClientInterface $httpClient,
        RequestFactory $requestFactory,
        ApiMapInterface $apiMap,
        CitiesFactoryInterface $citiesFactory,
        ActivitiesFactoryInterface $activitiesFactory,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->apiMap = $apiMap;
        $this->citiesFactory = $citiesFactory;
        $this->activitiesFactory = $activitiesFactory;
        $this->logger = $logger;
        $this->requestFactory = $requestFactory;
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
        return $this->requestFactory->createRequest(
            $this->apiMap->method($endpoint),
            $this->apiMap->url($endpoint, $params),
            $this->apiMap->headers($locale)
        );
    }
}
