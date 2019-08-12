<?php

declare(strict_types=1);

namespace Musement\Application\Musement\CitiesAndActivities;

use Musement\SDK\Musement\Model\Activities\Activity;
use Musement\SDK\Musement\Model\Cities\City;

final class InMemoryRepository
{
    private $cities = [];
    private $activities = [];

    public function addCity(City $city): void
    {
        $this->cities[] = $city;
    }

    public function addActivity(Activity $activity): void
    {
        $this->activities[] = $activity;
    }

    /**
     * @param callable $callback Callback arguments: \Musement\SDK\Musement\Model\Cities\City
     */
    public function eachCity(callable $callback): void
    {
        \array_map(
            $callback,
            $this->cities
        );
    }

    /**
     * @param callable $callback Callback arguments: \Musement\SDK\Musement\Model\Activities\Activity
     */
    public function eachActivity(callable $callback): void
    {
        \array_map(
            $callback,
            $this->activities
        );
    }
}
