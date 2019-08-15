<?php

declare(strict_types=1);

namespace Musement\Application\MusementCatalog\SitemapMailer;

use Musement\Application\MusementCatalog\Locale;

class SubjectFactory
{
    public const PLACEHOLDER_LOCALE = '{locale}';
    private $subjectPattern;

    public function __construct(string $subjectPattern)
    {
        $this->subjectPattern = $subjectPattern;
    }

    public function createSubject(Locale $locale): string
    {
        return \strtr($this->subjectPattern, [
            self::PLACEHOLDER_LOCALE => (string) $locale,
        ]);
    }
}
