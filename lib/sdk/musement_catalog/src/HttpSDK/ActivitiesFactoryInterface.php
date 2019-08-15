<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\HttpSDK;

use Musement\Component\Accessor\AccessorInterface;
use Musement\SDK\MusementCatalog\Model\Activities;
use Musement\SDK\MusementCatalog\Model\Locale;

interface ActivitiesFactoryInterface
{
    public function create(Locale $locale, AccessorInterface $response): Activities;
}
