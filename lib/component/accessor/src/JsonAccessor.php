<?php

declare(strict_types=1);

namespace Musement\Component\Accessor;

use Musement\Component\Accessor\Exception\JsonDecodeException;
use Musement\Component\Accessor\Exception\RuntimeException;

final class JsonAccessor implements AccessorInterface
{
    private $json;
    private $arrayAccessor;

    public function __construct(string $json)
    {
        $this->json = $json;

        try {
            $this->arrayAccessor = ArrayAccessor::fromArray(
                \json_decode($json, true, $depth = 512, JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $e) {
            throw new JsonDecodeException($e->getMessage(), $e);
        }
    }

    public function has(string $path): bool
    {
        return $this->arrayAccessor->has($path);
    }

    public function at(string $path, string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): AccessorInterface
    {
        return $this->arrayAccessor->at($path, $exceptionClass, $exceptionMessage);
    }

    public function value()
    {
        return $this->arrayAccessor->value();
    }

    public function string(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): string
    {
        return $this->arrayAccessor->string($exceptionClass, $exceptionMessage);
    }

    public function integer(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): int
    {
        return $this->arrayAccessor->integer($exceptionClass, $exceptionMessage);
    }

    public function float(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): float
    {
        return $this->arrayAccessor->float($exceptionClass, $exceptionMessage);
    }

    public function boolean(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): bool
    {
        return $this->arrayAccessor->boolean($exceptionClass, $exceptionMessage);
    }

    public function array(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): array
    {
        return $this->arrayAccessor->array($exceptionClass, $exceptionMessage);
    }

    public function map(callable $callback, string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): array
    {
        return $this->arrayAccessor->map($callback);
    }
}
