<?php

declare(strict_types=1);

namespace Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK;

use Musement\SDK\MusementCatalog\Model\Activities\Activity;
use Musement\SDK\MusementCatalog\Model\Cities\City;

class InMemoryRepository
{
    private $cities;
    private $activities;

    public function __construct()
    {
        $this->cities = [];
        $this->activities = [];
    }

    public function addCity(City $city): void
    {
        $this->cities[] = $city;
    }

    public function addActivity(Activity $activity): void
    {
        $this->activities[] = $activity;
    }

    /**
     * @param callable $callback Callback arguments: \Musement\SDK\MusementCatalog\Model\Cities\City
     */
    public function mapCities(callable $callback): array
    {
        return \array_map(
            $callback,
            $this->cities
        );
    }

    /**
     * @param callable $callback Callback arguments: \Musement\SDK\MusementCatalog\Model\Activities\Activity
     */
    public function mapActivities(callable $callback): array
    {
        return \array_map(
            $callback,
            $this->activities
        );
    }
}
