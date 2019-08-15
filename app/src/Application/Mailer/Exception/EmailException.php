<?php

declare(strict_types=1);

namespace Musement\Application\Mailer\Exception;

use Musement\Application\Exception\RuntimeException;
use Musement\Application\Mailer\Email;
use Throwable;

class EmailException extends RuntimeException
{
    private $email;

    public function __construct(Email $email, string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, $previous);

        $this->email = $email;
    }

    public function email(): Email
    {
        return $this->email;
    }
}
