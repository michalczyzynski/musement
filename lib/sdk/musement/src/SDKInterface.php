<?php

declare(strict_types=1);

namespace Musement\SDK\Musement;

use Musement\SDK\Musement\Model\Activities;
use Musement\SDK\Musement\Model\Cities;
use Musement\SDK\Musement\Model\Locale;

interface SDKInterface
{
    public function getCities(Locale $locale, int $limit = 20, int $offset = 0): Cities;
    public function getActivities(Locale $locale, int $cityId, int $limit = 20, int $offset = 0): Activities;
}
