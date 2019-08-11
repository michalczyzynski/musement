<?php

declare(strict_types=1);

namespace Musement\Component\Accessor;

use Musement\Component\Accessor\Exception\AssertionException;
use Musement\Component\Accessor\Exception\RuntimeException;
use Musement\Component\Accessor\Path\Cursor;
use Musement\Component\Accessor\Path\Node;

final class Path implements ToStringInterface
{
    private const NODE_DELIMITER = '.';

    private $path;
    private $nodes;
    private $nodesCursor;

    private function __construct(Node ...$nodes)
    {
        $path = \implode(self::NODE_DELIMITER, $nodes);
        try {
            Assertion::between(
                \count($nodes),
                $minCount = 1,
                $maxCount = 50,
                'Path can contain between 1 and 50 elements.'
            );

            $this->path = $path;
            $this->nodes = $nodes;
            $this->nodesCursor = new Cursor(...$nodes);
        } catch (AssertionException $e) {
            throw new RuntimeException(
                sprintf('Path "%s": %s', $path, $e->getMessage()),
                $e
            );
        }
    }

    public static function fromString(string $path): self
    {
        try {
            Assertion::minLength(
                $path,
                $minLength = 1,
                'Path cannot be empty.'
            );

            return new self(
                ...\array_map(
                    function (string $name) {
                        return new Node($name);
                    },
                    \explode(self::NODE_DELIMITER, $path)
                )
            );
        } catch (AssertionException $e) {
            throw new RuntimeException(
                sprintf('Path "%s": %s', $path, $e->getMessage()),
                $e
            );
        }
    }

    public function canEnter(): bool
    {
        try {
            $this->nodesCursor->next();

            return true;
        } catch (RuntimeException $e) {
            return false;
        }
    }

    public function enter(): self
    {
        $instance = clone $this;
        $instance->nodesCursor = $instance->nodesCursor->next();

        return $instance;
    }

    public function canLeave(): bool
    {
        try {
            $this->nodesCursor->prev();

            return true;
        } catch (RuntimeException $e) {
            return false;
        }
    }

    public function leave(): self
    {
        $instance = clone $this;
        $instance->nodesCursor = $instance->nodesCursor->prev();

        return $instance;
    }

    public function extend(string $path): self
    {
        $extension = self::fromString($path);
        $instance = new self(
            ...$this->nodes,
            ...$extension->nodes
        );
        $instance->nodesCursor = $instance->nodesCursor->seek(
            \current($extension->nodes)
        );

        return $instance;
    }

    public function node(): string
    {
        return (string) $this->nodesCursor->current();
    }

    public function pathToCurrentNode(): self
    {
        $cursor = $this->nodesCursor;
        $nodes = [];

        while (true) {
            $nodes[] = $cursor->current();

            try {
                $cursor = $cursor->prev();
            } catch (RuntimeException $e) {
                break;
            }
        }

        $instance = new self(...\array_reverse($nodes));

        while ($this->nodesCursor->current() !== $instance->nodesCursor->current()) {
            $instance = $instance->enter();
        }

        return $instance;
    }

    public function __toString(): string
    {
        return $this->path;
    }

    public function __clone()
    {
        $this->nodesCursor = clone $this->nodesCursor;
    }

    public function isEqual(self $other): bool
    {
        return $this->path === $other->path;
    }
}
