<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\HttpSDK\V3;

use Musement\Component\Accessor\AccessorInterface;
use Musement\SDK\MusementCatalog\HttpSDK\CitiesFactoryInterface;
use Musement\SDK\MusementCatalog\Model\Cities;
use Musement\SDK\MusementCatalog\Model\Locale;

final class CitiesFactory implements CitiesFactoryInterface
{
    public function create(Locale $locale, AccessorInterface $response): Cities
    {
        return new Cities(
            ...$response->map(
                static function (AccessorInterface $row) use ($locale) {
                    return new Cities\City(
                        $locale,
                        $row->at('id')->integer(),
                        $row->at('name')->string(),
                        $row->at('url')->string()
                    );
                }
            )
        );
    }
}
