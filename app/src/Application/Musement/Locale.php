<?php

declare(strict_types=1);

namespace Musement\Application\Musement;

use Musement\SDK\Musement\Model\Locale as SDKLocale;

final class Locale
{
    private $locale;

    private function __construct(SDKLocale $locale)
    {
        $this->locale = $locale;
    }

    public static function italian(): self
    {
        return new self(SDKLocale::italian());
    }

    public static function french(): self
    {
        return new self(SDKLocale::french());
    }

    public static function spanish(): self
    {
        return new self(SDKLocale::spanish());
    }

    public function toSDKLocale(): SDKLocale
    {
        return $this->locale;
    }
}
