<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Model;

use Musement\SDK\Musement\ToStringInterface;

final class Locale implements ToStringInterface
{
    private $locale;

    private function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public static function italian(): self
    {
        return new self('it-IT');
    }

    public static function french(): self
    {
        return new self('fr-FR');
    }

    public static function spanish(): self
    {
        return new self('es-ES');
    }

    public function __toString(): string
    {
        return $this->locale;
    }
}
