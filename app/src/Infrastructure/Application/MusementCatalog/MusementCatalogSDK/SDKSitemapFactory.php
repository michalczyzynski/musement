<?php

declare(strict_types=1);

namespace Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK;

use Musement\Application\MusementCatalog\Locale;
use Musement\Application\MusementCatalog\SitemapFactoryInterface;
use Musement\Application\Sitemap\Urlset;
use Musement\SDK\MusementCatalog\Model\Activities\Activity;
use Musement\SDK\MusementCatalog\Model\Cities\City;

class SDKSitemapFactory implements SitemapFactoryInterface
{
    private $localeFactory;
    private $crawler;
    private $urlsetFactory;
    private $cityPriority;
    private $activityPriority;

    public function __construct(
        LocaleFactoryInterface $localeFactory,
        CrawlerInterface $crawler,
        Urlset\UrlsetFactoryInterface $urlsetFactory,
        float $cityPriority,
        float $activityPriority
    ) {
        $this->localeFactory = $localeFactory;
        $this->crawler = $crawler;
        $this->urlsetFactory = $urlsetFactory;
        $this->cityPriority = $cityPriority;
        $this->activityPriority = $activityPriority;
    }

    public function create(Locale $locale): Urlset
    {
        $repository = $this->crawler->crawl(
            $this->localeFactory->toSDKLocale($locale)
        );

        return $this->urlsetFactory->create(
            ...$repository->mapCities(
                function (City $city) {
                    return new Urlset\Url(
                        $city->url(),
                        $this->cityPriority
                    );
                }
            ),
            ...$repository->mapActivities(
                function (Activity $activity) {
                    return new Urlset\Url(
                        $activity->url(),
                        $this->activityPriority
                    );
                }
            )
        );
    }
}
