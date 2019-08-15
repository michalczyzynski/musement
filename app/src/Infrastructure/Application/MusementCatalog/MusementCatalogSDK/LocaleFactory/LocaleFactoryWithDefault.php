<?php

declare(strict_types=1);

namespace Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK\LocaleFactory;

use Musement\Application\MusementCatalog\Locale;
use Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK\LocaleFactoryInterface;
use Musement\SDK\MusementCatalog\Model\Locale as SDKLocale;

class LocaleFactoryWithDefault implements LocaleFactoryInterface
{
    private $defaultLocale;

    public function __construct(SDKLocale $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function toSDKLocale(Locale $locale): SDKLocale
    {
        switch (true) {
            case $locale->isEqual(Locale::italian()):
                return SDKLocale::italian();

            case $locale->isEqual(Locale::french()):
                return SDKLocale::french();

            case $locale->isEqual(Locale::spanish()):
                return SDKLocale::spanish();

            default:
                return $this->defaultLocale;
        }
    }
}
