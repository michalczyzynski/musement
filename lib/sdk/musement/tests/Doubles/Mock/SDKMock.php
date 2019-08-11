<?php

declare(strict_types=1);

namespace Musement\SDK\Tests\Double\Mock;

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
        $this->activities = new Activities(0);
    }

    public function willReturnCities(Cities $cities): self
    {
        $this->cities = $cities;

        return $this;
    }

    public function getCities(Locale $locale, int $limit = 20, int $offset = 0): Cities
    {
        return $this->cities;
    }

    public function willReturnActivities(Activities $activities): self
    {
        $this->activities = $activities;

        return $this;
    }

    public function getActivities(Locale $locale, int $cityId, int $limit = 20, int $offset = 0): Activities
    {
        return $this->activities;
    }
}
