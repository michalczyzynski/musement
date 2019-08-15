<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\Tests\Double\Mock;

use Musement\SDK\MusementCatalog\Model\Activities;
use Musement\SDK\MusementCatalog\Model\Cities;
use Musement\SDK\MusementCatalog\Model\Locale;
use Musement\SDK\MusementCatalog\SDKInterface;

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
