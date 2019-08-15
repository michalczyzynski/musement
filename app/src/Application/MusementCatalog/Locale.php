<?php

declare(strict_types=1);

namespace Musement\Application\MusementCatalog;

class Locale
{
    private $locale;

    private function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public static function italian(): self
    {
        return new self('italian');
    }

    public static function french(): self
    {
        return new self('french');
    }

    public static function spanish(): self
    {
        return new self('spanish');
    }

    public function __toString(): string
    {
        return $this->locale;
    }

    public function isEqual(self $other): bool
    {
        return $this->locale === $other->locale;
    }
}
