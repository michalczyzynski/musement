<?php

declare(strict_types=1);

namespace Musement\Application\Musement\Sitemap;

use Musement\Application\Musement\CitiesAndActivities\FetchCitiesAndActivities;
use Musement\Application\Musement\Locale;
use Musement\Application\Musement\Sitemap;
use Musement\SDK\Musement\Model\Activities\Activity;
use Musement\SDK\Musement\Model\Cities\City;
use Thepixeldeveloper\Sitemap\Drivers\XmlWriterDriver;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

final class CitiesAndActivitiesSitemap
{
    private $fetchCitiesAndActivities;
    private $siteUrl;
    private $cityPriority;
    private $activityPriority;

    public function __construct(
        FetchCitiesAndActivities $fetchCitiesAndActivities,
        string $siteUrl,
        float $cityPriority,
        float $activityPriority
    ) {
        $this->fetchCitiesAndActivities = $fetchCitiesAndActivities;
        $this->siteUrl = $siteUrl;
        $this->cityPriority = $cityPriority;
        $this->activityPriority = $activityPriority;
    }

    public function createSitemap(Locale $locale): Sitemap
    {
        $repository = $this->fetchCitiesAndActivities->run($locale);
        $urlset = new Urlset();

        $repository->eachCity(
            function (City $city) use ($urlset) {
                $url = new Url($city->url());
                $url->setPriority((string) $this->cityPriority);

                $urlset->add($url);
            }
        );

        $repository->eachActivity(
            function (Activity $activity) use ($urlset) {
                $url = new Url($activity->url());
                $url->setPriority((string) $this->activityPriority);

                $urlset->add($url);
            }
        );

        $driver = new XmlWriterDriver();
        $urlset->accept($driver);

        return new Sitemap($locale, $driver->output());
    }
}
