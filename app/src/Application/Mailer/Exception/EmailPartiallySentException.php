<?php

declare(strict_types=1);

namespace Musement\Application\Mailer\Exception;

use Musement\Application\Mailer\Email;
use Musement\Application\Mailer\Recipients;
use Throwable;

class EmailPartiallySentException extends EmailException
{
    private $failedRecipients;

    public function __construct(Email $email, Recipients $failedRecipients, string $message = "", Throwable $previous = null)
    {
        parent::__construct($email, $message, $previous);

        $this->failedRecipients = $failedRecipients;
    }

    public function failedRecipients(): Recipients
    {
        return $this->failedRecipients;
    }
}
