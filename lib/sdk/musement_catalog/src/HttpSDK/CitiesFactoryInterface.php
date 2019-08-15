<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\HttpSDK;

use Musement\Component\Accessor\AccessorInterface;
use Musement\SDK\MusementCatalog\Model\Cities;
use Musement\SDK\MusementCatalog\Model\Locale;

interface CitiesFactoryInterface
{
    public function create(Locale $locale, AccessorInterface $response): Cities;
}
