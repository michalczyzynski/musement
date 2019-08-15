<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\HttpSDK;

use Musement\SDK\MusementCatalog\ToStringInterface;

final class Endpoint implements ToStringInterface
{
    private $endpoint;

    private function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public static function cities(): self
    {
        return new self('cities');
    }

    public static function activities(): self
    {
        return new self('activities');
    }

    public function __toString(): string
    {
        return $this->endpoint;
    }

    public function isEqual(self $other): bool
    {
        return $this->endpoint === $other->endpoint;
    }
}
