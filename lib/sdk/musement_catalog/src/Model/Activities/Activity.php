<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\Model\Activities;

use Musement\SDK\MusementCatalog\Assertion;
use Musement\SDK\MusementCatalog\Model\Cities\City;
use Musement\SDK\MusementCatalog\Model\Locale;

final class Activity
{
    private $city;
    private $uuid;
    private $title;
    private $url;
    private $locale;

    public function __construct(Locale $locale, City $city, string $uuid, string $title, string $url)
    {
        Assertion::uuid($uuid);
        Assertion::url($url);

        $this->locale = $locale;
        $this->city = $city;
        $this->uuid = $uuid;
        $this->title = $title;
        $this->url = $url;
    }

    public function city(): City
    {
        return $this->city;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function url(): string
    {
        return $this->url;
    }
}
