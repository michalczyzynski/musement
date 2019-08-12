<?php

declare(strict_types=1);

namespace Musement\Application\Musement;

final class Sitemap
{
    private $sitemap;
    private $locale;

    public function __construct(Locale $locale, string $sitemap)
    {
        $this->locale = $locale;
        $this->sitemap = $sitemap;
    }

    public function locale(): Locale
    {
        return $this->locale;
    }

    public function __toString(): string
    {
        return $this->sitemap;
    }
}
