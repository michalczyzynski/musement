<?php

declare(strict_types=1);

namespace Musement\Application\Mailer;

use Musement\Application\Assertion;

class Sender
{
    private $email;
    private $name;

    public function __construct(string $email, ?string $name = null)
    {
        Assertion::email($email);

        if ($name) {
            Assertion::maxLength($name, 200, 'Name cannot be longer than 200 chars.');
        }

        $this->email = $email;
        $this->name = $name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): ?string
    {
        return $this->name;
    }
}
