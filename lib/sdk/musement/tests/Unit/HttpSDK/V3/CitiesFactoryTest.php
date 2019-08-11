<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Tests\Unit\HttpSDK\V3;

use Musement\Component\Accessor\JsonAccessor;
use Musement\SDK\Musement\HttpSDK\V3\CitiesFactory;
use Musement\SDK\Musement\Model\Cities;
use Musement\SDK\Musement\Model\Locale;
use PHPUnit\Framework\TestCase;

final class CitiesFactoryTest extends TestCase
{
    public function test_creates_from_json()
    {
        $factory = new CitiesFactory();
        $locale = Locale::italian();

        self::assertEquals(
            new Cities(
                new Cities\City(
                    $locale,
                    57,
                    'Amsterdam',
                    'https://www.musement.com/uk/amsterdam/'
                ),
                new Cities\City(
                    $locale,
                    40,
                    'Paris',
                    'https://www.musement.com/uk/paris/'
                ),
                new Cities\City(
                    $locale,
                    2,
                    'Rome',
                    'https://www.musement.com/uk/rome/'
                )
            ),
            $factory->create(
                $locale,
                new JsonAccessor(
                    file_get_contents(__DIR__ . '/../../../Resource/v3/cities.json')
                )
            )
        );
    }
}
