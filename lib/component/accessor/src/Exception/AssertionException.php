<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Exception;

class AssertionException extends RuntimeException
{
    public function __construct($message, $code, $propertyPath, $value, array $constraints = [])
    {
        parent::__construct($message);
    }
}
