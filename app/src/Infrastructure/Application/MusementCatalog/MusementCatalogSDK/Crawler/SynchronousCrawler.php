<?php

declare(strict_types=1);

namespace Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK\Crawler;

use Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK\CrawlerInterface;
use Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK\InMemoryRepository;
use Musement\SDK\MusementCatalog\Model\Activities\Activity;
use Musement\SDK\MusementCatalog\Model\Cities\City;
use Musement\SDK\MusementCatalog\Model\Locale;
use Musement\SDK\MusementCatalog\SDKInterface;

class SynchronousCrawler implements CrawlerInterface
{
    private $SDK;

    public function __construct(SDKInterface $SDK)
    {
        $this->SDK = $SDK;
    }

    public function crawl(Locale $locale): InMemoryRepository
    {
        $repository = new InMemoryRepository();

        // If needed, could change implementation here to fetch all cities & activities
        $cities = $this->SDK->getCities($locale, 2);
        $cities->each(
            function (City $city) use ($repository) {
                $repository->addCity($city);
            }
        );
        $cities->each(
            function (City $city) use ($locale, $repository) {
                $this
                    ->SDK
                    ->getActivities($locale, $city->id(), 2)
                    ->each(function (Activity $activity) use ($repository) {
                        $repository->addActivity($activity);
                    });
            }
        );

        return $repository;
    }
}
