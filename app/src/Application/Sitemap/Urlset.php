<?php

declare(strict_types=1);

namespace Musement\Application\Sitemap;

use Musement\Application\Assertion;

class Urlset
{
    private $urlset;

    public function __construct(string $urlset)
    {
        Assertion::notEmpty($urlset, 'Urlset cannot be empty.');

        $this->urlset = $urlset;
    }

    public function __toString(): string
    {
        return $this->urlset;
    }
}
