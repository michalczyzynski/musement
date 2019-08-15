<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\HttpSDK;

use Musement\SDK\MusementCatalog\Assertion;
use Musement\SDK\MusementCatalog\ToStringInterface;

final class Url implements ToStringInterface
{
    private $url;

    public function __construct(string $url)
    {
        Assertion::url($url);

        $this->url = $url;
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
