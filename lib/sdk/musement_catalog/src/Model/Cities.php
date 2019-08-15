<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\Model;

use Musement\SDK\MusementCatalog\Model\Cities\City;

final class Cities
{
    private $cities;

    public function __construct(City ...$cities)
    {
        $this->cities = $cities;
    }

    /**
     * @param callable $callback Callback args: \Musement\SDK\MusementCatalog\Cities\Model\City
     */
    public function each(callable $callback): void
    {
        \array_map($callback, $this->cities);
    }
}
