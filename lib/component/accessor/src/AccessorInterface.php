<?php

declare(strict_types=1);

namespace Musement\Component\Accessor;

use Musement\Component\Accessor\Exception\RuntimeException;

interface AccessorInterface
{
    public function has(string $path): bool;
    public function at(string $path, string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): self;
    /** @return mixed */
    public function value();
    public function string(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): string;
    public function integer(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): int;
    public function float(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): float;
    public function boolean(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): bool;
    public function array(string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): array;
    /**
     * @param callable $callback Callback arguments: \Musement\Component\Accessor\AccessorInterface
     */
    public function map(callable $callback, string $exceptionClass = RuntimeException::class, string $exceptionMessage = null): array;
}
