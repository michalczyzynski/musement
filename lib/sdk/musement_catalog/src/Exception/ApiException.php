<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog\Exception;

use Throwable;

final class ApiException extends RuntimeException
{
    private $statusCode;

    public function __construct(int $statusCode, string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, $previous);
        $this->statusCode = $statusCode;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}
