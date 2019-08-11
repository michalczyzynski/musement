<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Tests\Unit\HttpSDK\V3;

use Musement\Component\Accessor\JsonAccessor;
use Musement\SDK\Musement\HttpSDK\V3\ActivitiesFactory;
use Musement\SDK\Musement\Model\Activities;
use Musement\SDK\Musement\Model\Cities\City;
use Musement\SDK\Musement\Model\Locale;
use PHPUnit\Framework\TestCase;

final class ActivitiesFactoryTest extends TestCase
{
    public function test_creates_from_json()
    {
        $factory = new ActivitiesFactory();
        $locale = Locale::italian();

        self::assertEquals(
            new Activities(
                163,
                new Activities\Activity(
                    $locale,
                    new City(
                        $locale,
                        57,
                        'Amsterdam',
                        'https://www.musement.com/uk/amsterdam/'
                    ),
                    '0f5ca2e8-2046-11e7-9cc9-06a7e332783f',
                    'Van Gogh Museum skip-the-line tickets',
                    'https://www.musement.com/uk/amsterdam/van-gogh-museum-skip-the-line-tickets-651/'
                ),
                new Activities\Activity(
                    $locale,
                    new City(
                        $locale,
                        57,
                        'Amsterdam',
                        'https://www.musement.com/uk/amsterdam/'
                    ),
                    '1028682a-2046-11e7-9cc9-06a7e332783f',
                    'Heineken Experience tickets',
                    'https://www.musement.com/uk/amsterdam/heineken-experience-tickets-2224/'
                ),
                new Activities\Activity(
                    $locale,
                    new City(
                        $locale,
                        57,
                        'Amsterdam',
                        'https://www.musement.com/uk/amsterdam/'
                    ),
                    '40be5981-27be-4103-873b-26cb90901069',
                    'Van Gogh Museum entrance and Amsterdam canal cruise',
                    'https://www.musement.com/uk/amsterdam/van-gogh-museum-entrance-and-amsterdam-canal-cruise-27941/'
                ),
            ),
            $factory->create(
                $locale,
                new JsonAccessor(
                    file_get_contents(__DIR__ . '/../../../Resource/v3/activities.json')
                )
            )
        );
    }
}
