<?php

declare(strict_types=1);

namespace Musement\SDK\Musement;

use Musement\SDK\Musement\Cities\Model\City;
use Musement\SDK\Musement\Model\Activities;
use Musement\SDK\Musement\Model\Cities;
use Musement\SDK\Musement\Model\Locale;

interface SDKInterface
{
    public function getCities(Locale $locale): Cities;
    public function getActivities(Locale $locale, City $city): Activities;
}
