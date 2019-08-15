<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\HttpSDK\V3;

use Musement\Component\Accessor\AccessorInterface;
use Musement\SDK\MusementCatalog\HttpSDK\ActivitiesFactoryInterface;
use Musement\SDK\MusementCatalog\Model\Activities;
use Musement\SDK\MusementCatalog\Model\Cities\City;
use Musement\SDK\MusementCatalog\Model\Locale;

final class ActivitiesFactory implements ActivitiesFactoryInterface
{
    public function create(Locale $locale, AccessorInterface $response): Activities
    {
        return new Activities(
            $response->at('meta.count')->integer(),
            ...$response->at('data')->map(
                static function (AccessorInterface $row) use ($locale) {
                    return new Activities\Activity(
                        $locale,
                        new City(
                            $locale,
                            $row->at('city.id')->integer(),
                            $row->at('city.name')->string(),
                            $row->at('city.url')->string()
                        ),
                        $row->at('uuid')->string(),
                        $row->at('title')->string(),
                        $row->at('url')->string()
                    );
                }
            )
        );
    }
}
