<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Path;

use Musement\Component\Accessor\Assertion;

/**
 * @Internal
 */
final class Node
{
    private $name;

    public function __construct(string $name)
    {
        Assertion::minLength(
            $name,
            $minLength = 1,
            'Node cannot be empty.'
        );

        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
