<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Path;

use Musement\Component\Accessor\Exception\RuntimeException;

/**
 * @Internal
 */
final class Cursor
{
    private $iterator;

    public function __construct(Node ...$nodes)
    {
        $this->iterator = new \ArrayIterator($nodes);
    }

    public function next(): self
    {
        $instance = clone $this;
        $instance->iterator->next();

        if (!$instance->iterator->valid()) {
            throw new RuntimeException('Cursor points to a leaf node, cannot enter.');
        }

        return $instance;
    }

    public function prev(): self
    {
        $instance = clone $this;

        try {
            $instance->iterator->seek($instance->iterator->key() - 1);
        } catch (\OutOfBoundsException $e) {
            throw new RuntimeException('Cursor points to a root node, cannot leave.');
        }

        return $instance;
    }

    public function current(): Node
    {
        return $this->iterator->current();
    }

    public function __clone()
    {
        $position = $this->iterator->key();
        $this->iterator = new \ArrayIterator(
            $this->iterator->getArrayCopy()
        );
        $this->iterator->seek($position);
    }
}
