<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Exception;

use Throwable;

class Exception extends \Exception
{
    public function __construct(string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
