<?php

declare(strict_types=1);

namespace Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK;

use Musement\Application\MusementCatalog\Locale;
use Musement\SDK\MusementCatalog\Model\Locale as SDKLocale;

interface LocaleFactoryInterface
{
    public function toSDKLocale(Locale $locale): SDKLocale;
}
