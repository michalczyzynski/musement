<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog;

use Musement\SDK\MusementCatalog\Model\Activities;
use Musement\SDK\MusementCatalog\Model\Cities;
use Musement\SDK\MusementCatalog\Model\Locale;

interface SDKInterface
{
    public function getCities(Locale $locale, int $limit = 20, int $offset = 0): Cities;
    public function getActivities(Locale $locale, int $cityId, int $limit = 20, int $offset = 0): Activities;
}
