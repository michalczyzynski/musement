<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Activities\Model;

use Musement\SDK\Musement\Cities\Model\City;

final class Activity
{
    private $city;
    private $id;
    private $title;
    private $url;

    public function __construct(City $city, int $id, string $title, string $url)
    {
        $this->city = $city;
        $this->id = $id;
        $this->title = $title;
        $this->url = $url;
    }

    public function city(): City
    {
        return $this->city;
    }

    public function id(): int
    {
        return $this->id;
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
