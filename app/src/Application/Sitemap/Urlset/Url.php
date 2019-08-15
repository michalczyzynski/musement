<?php

declare(strict_types=1);

namespace Musement\Application\Sitemap\Urlset;

use Musement\Application\Assertion;

class Url
{
    private $url;
    private $priority;

    public function __construct(string $url, float $priority)
    {
        Assertion::url($url, sprintf(
            '"%s" is not a valid url.',
            $url
        ));

        Assertion::between($priority, 0, 1, 'Priority has to be between 0.0 and 1.0 inclusive.');

        $this->url = $url;
        $this->priority = $priority;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function priority(): float
    {
        return $this->priority;
    }
}
