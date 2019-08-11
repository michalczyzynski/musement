<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Exception;

class AssertionException extends RuntimeException
{
    public function __construct($message, $code, $propertyPath, $value, array $constraints = [])
    {
        parent::__construct($message);
    }
}
