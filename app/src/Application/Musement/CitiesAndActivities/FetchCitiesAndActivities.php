<?php

declare(strict_types=1);

namespace Musement\Application\Musement\CitiesAndActivities;

use Musement\Application\Musement\Locale;
use Musement\SDK\Musement\Model\Activities\Activity;
use Musement\SDK\Musement\Model\Cities\City;
use Musement\SDK\Musement\SDKInterface;

final class FetchCitiesAndActivities
{
    private $sdk;

    public function __construct(SDKInterface $sdk)
    {
        $this->sdk = $sdk;
    }

    public function run(Locale $locale): InMemoryRepository
    {
        $repository = new InMemoryRepository();

        // Would iterate over more cities here, but 20 is the requirement.
        $this->sdk->getCities($locale->toSDKLocale(), 20)->each(
            function (City $city) use ($repository) {
                $repository->addCity($city);
            }
        );

        $repository->eachCity(
            function (City $city) use ($locale, $repository) {
                $this
                    ->sdk
                    ->getActivities($locale->toSDKLocale(), $city->id(), 20)
                    ->each(function (Activity $activity) use ($repository) {
                        $repository->addActivity($activity);
                    });
            }
        );

        return $repository;
    }
}
