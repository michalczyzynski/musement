<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\HttpSDK;

use Musement\Component\Accessor\AccessorInterface;
use Musement\SDK\Musement\Model\Cities;
use Musement\SDK\Musement\Model\Locale;

interface CitiesFactoryInterface
{
    public function create(Locale $locale, AccessorInterface $response): Cities;
}
