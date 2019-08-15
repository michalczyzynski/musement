<?php

declare(strict_types=1);

namespace Musement\Application\MusementCatalog;

use Musement\Application\Sitemap\Urlset;

interface SitemapFactoryInterface
{
    public function create(Locale $locale): Urlset;
}
