<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Path;

use Musement\Component\Accessor\Assertion;
use Musement\Component\Accessor\Exception\RuntimeException;
use Musement\Component\Accessor\Exception\OutOfBoundsException;

/**
 * @Internal
 */
final class Cursor
{
    private $iterator;

    public function __construct(Node ...$nodes)
    {
        Assertion::minCount($nodes, 1, 'Cannot create empty cursor.');
        Assertion::uniqueInstances($nodes, 'Nodes are not unique.');

        $this->iterator = new \ArrayIterator($nodes);
    }

    public function next(): self
    {
        $instance = clone $this;
        $instance->iterator->next();

        if (!$instance->iterator->valid()) {
            throw new OutOfBoundsException('Cursor points to a leaf node, cannot enter.');
        }

        return $instance;
    }

    public function prev(): self
    {
        $instance = clone $this;

        try {
            $instance->iterator->seek($instance->iterator->key() - 1);
        } catch (\OutOfBoundsException $e) {
            throw new OutOfBoundsException('Cursor points to a root node, cannot leave.');
        }

        return $instance;
    }

    public function seek(Node $node): self
    {
        $instance = clone $this;
        $key = array_search($node, $instance->iterator->getArrayCopy(), $strict = true);

        if (false === $key) {
            throw new RuntimeException(sprintf(
                'Node "%s" is not part of this cursor.',
                $node
            ));
        }

        $instance->iterator->seek($key);

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
