<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Cities\Model;

use Musement\SDK\Musement\Model\Locale;

final class City
{
    private $locale;
    private $id;
    private $name;
    private $url;

    public function __construct(Locale $locale, int $id, string $name, string $url)
    {
        $this->locale = $locale;
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
    }

    public function locale(): Locale
    {
        return $this->locale;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function url(): string
    {
        return $this->url;
    }
}
