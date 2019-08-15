<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\HttpSDK;

use Musement\SDK\MusementCatalog\Model\Locale;

interface ApiMapInterface
{
    public function headers(Locale $locale): array;
    public function method(Endpoint $endpoint): string;
    public function url(Endpoint $endpoint, Params $parameters = null): string;
}
