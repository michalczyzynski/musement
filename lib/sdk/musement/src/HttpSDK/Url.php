<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\HttpSDK;

use Musement\SDK\Musement\Assertion;
use Musement\SDK\Musement\ToStringInterface;

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
