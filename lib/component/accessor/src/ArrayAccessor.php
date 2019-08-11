<?php

declare(strict_types=1);

namespace Musement\Component\Accessor;

use Musement\Component\Accessor\Exception\RuntimeException;

final class ArrayAccessor implements AccessorInterface
{
    private $data;
    /** @var Path|null */
    private $path;

    private function __construct($data)
    {
        $this->data = $data;
        $this->path = null;
    }

    public static function fromArray(array $array): self
    {
        return new self($array);
    }

    private static function enter($data, Path $path): self
    {
        $instance = new self($data);
        $instance->path = $path;

        return $instance;
    }

    public function at(string $path, string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): AccessorInterface
    {
        $path = $this->path ? $this->path->extend($path) : Path::fromString($path);
        $at =& $this->data;

        do {
            if (!is_array($at)) {
                throw $path->canLeave()
                    ? new $exceptionClass(
                        $exceptionMessage ?: sprintf(
                            'Data at %s is not an array.',
                            $path->leave()->pathToCurrentNode()
                        )
                    )
                    : new $exceptionClass($exceptionMessage ?: 'Data is not an array.');
            }

            if (!array_key_exists($path->node(), $at)) {
                switch (true) {
                    case null !== $exceptionMessage:
                        throw new $exceptionClass($exceptionMessage);

                    case $path->isEqual($path->pathToCurrentNode()):
                        throw new $exceptionClass(sprintf(
                            'Path %s does not exist.',
                            $path->pathToCurrentNode()
                        ));

                    default:
                        throw new $exceptionClass(sprintf(
                            'Path %s (part of %s) does not exist.',
                            $path->pathToCurrentNode(),
                            $path
                        ));
                }
            }

            $at =& $at[$path->node()];
        } while ($path->canEnter() && $path = $path->enter());

        return self::enter($at, $path);
    }

    public function has(string $path): bool
    {
        try {
            $this->at($path);

            return true;
        } catch (RuntimeException $e) {
            return false;
        }
    }

    public function value()
    {
        return $this->data;
    }

    public function string(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): string
    {
        if (!is_string($this->data)) {
            throw new $exceptionClass($exceptionMessage ?: $this->defaultErrorMessage('string'));
        }

        return (string) $this->data;
    }

    public function integer(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): int
    {
        if (!is_int($this->data)) {
            throw new $exceptionClass($exceptionMessage ?: $this->defaultErrorMessage('integer'));
        }

        return (int) $this->data;
    }

    public function float(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): float
    {
        if (!is_float($this->data)) {
            throw new $exceptionClass($exceptionMessage ?: $this->defaultErrorMessage('float'));
        }

        return (float) $this->data;
    }

    public function boolean(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): bool
    {
        if (!is_bool($this->data)) {
            throw new $exceptionClass($exceptionMessage ?: $this->defaultErrorMessage('boolean'));
        }

        return (bool) $this->data;
    }

    public function array(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): array
    {
        if (!is_array($this->data)) {
            throw new $exceptionClass($exceptionMessage ?: $this->defaultErrorMessage('array'));
        }

        return (array) $this->data;
    }

    public function map(callable $callback, string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): array
    {
        return \array_map(
            function (string $path) use ($callback) {
                return $callback(
                    $this->at($path)
                );
            },
            array_keys($this->array())
        );
    }

    private function defaultErrorMessage(string $expectedType): string
    {
        return sprintf(
            'Expected %s, got %s.',
            $expectedType,
            is_object($this->data)
                ? get_class($this->data)
                : gettype($this->data)
        );
    }
}
