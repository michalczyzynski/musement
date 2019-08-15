<?php

declare(strict_types=1);

namespace Musement\Application\Mailer;

use Musement\Application\Mailer\Exception\EmailException;
use Musement\Application\Mailer\Exception\EmailPartiallySentException;

interface MailClientInterface
{
    /**
     * @throws EmailPartiallySentException
     * @throws EmailException
     */
    public function send(Email $email): void;
}
