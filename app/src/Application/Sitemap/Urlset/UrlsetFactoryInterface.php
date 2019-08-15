<?php

declare(strict_types=1);

namespace Musement\Application\Sitemap\Urlset;

use Musement\Application\Sitemap\Urlset;

interface UrlsetFactoryInterface
{
    public function create(Url ...$urls): Urlset;
}
