<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Tests\Mother\Model\Cities;

use Musement\SDK\Musement\Model\Cities\City;
use Musement\SDK\Musement\Model\Locale;

final class CityMother
{
    public static function random(): City
    {
        return self::forLocale(Locale::italian());
    }

    public static function forLocale(Locale $locale): City
    {
        return new City(
            $locale,
            57,
            'Amsterdam',
            'https://www.musement.com/uk/amsterdam/'
        );
    }
}
