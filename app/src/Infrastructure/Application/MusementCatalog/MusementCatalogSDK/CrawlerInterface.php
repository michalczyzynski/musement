<?php

declare(strict_types=1);

namespace Musement\Infrastructure\Application\MusementCatalog\MusementCatalogSDK;

use Musement\SDK\MusementCatalog\Model\Locale;

interface CrawlerInterface
{
    public function crawl(Locale $locale): InMemoryRepository;
}
