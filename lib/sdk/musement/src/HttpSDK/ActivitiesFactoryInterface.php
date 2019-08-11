<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\HttpSDK;

use Musement\Component\Accessor\AccessorInterface;
use Musement\SDK\Musement\Model\Activities;
use Musement\SDK\Musement\Model\Locale;

interface ActivitiesFactoryInterface
{
    public function create(Locale $locale, AccessorInterface $response): Activities;
}
