<?php

declare(strict_types=1);

namespace Musement\SDK\Tests\Double\Mock;

use Musement\SDK\Musement\Cities\Model\City;
use Musement\SDK\Musement\Model\Activities;
use Musement\SDK\Musement\Model\Cities;
use Musement\SDK\Musement\Model\Locale;
use Musement\SDK\Musement\SDKInterface;

final class SDKMock implements SDKInterface
{
    private $cities;
    private $activities;

    public function __construct()
    {
        $this->cities = new Cities();
        $this->activities = new Activities();
    }

    public function willReturnCities(Cities $cities): self
    {
        $this->cities = $cities;

        return $this;
    }

    public function getCities(Locale $locale): Cities
    {
        return $this->cities;
    }

    public function willReturnActivities(Activities $activities): self
    {
        $this->activities = $activities;

        return $this;
    }

    public function getActivities(Locale $locale, City $city): Activities
    {
        return $this->activities;
    }
}
