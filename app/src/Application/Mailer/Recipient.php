<?php

declare(strict_types=1);

namespace Musement\Application\Mailer;

use Musement\Application\Assertion;

class Recipient
{
    private const TYPE_TO = 'to';
    private const TYPE_CC = 'cc';
    private const TYPE_BCC = 'bcc';

    private $type;
    private $email;
    private $name;

    private function __construct(string $type, string $email, ?string $name)
    {
        Assertion::inArray($type, [self::TYPE_TO, self::TYPE_CC, self::TYPE_BCC]);
        Assertion::email($email);

        if (null !== $name) {
            Assertion::maxLength($name, 200, 'Name cannot be longer than 200 chars.');
        }

        $this->type = $type;
        $this->email = $email;
        $this->name = $name;
    }

    public static function to(string $email, ?string $name = null): self
    {
        return new self(self::TYPE_TO, $email, $name);
    }

    public static function cc(string $email, ?string $name = null): self
    {
        return new self(self::TYPE_CC, $email, $name);
    }

    public static function bcc(string $email, ?string $name = null): self
    {
        return new self(self::TYPE_BCC, $email, $name);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function isTo(): bool
    {
        return self::TYPE_TO === $this->type;
    }

    public function isCc(): bool
    {
        return self::TYPE_CC === $this->type;
    }

    public function isBcc(): bool
    {
        return self::TYPE_BCC === $this->type;
    }

    public function __toString(): string
    {
        return sprintf('%s<%s>', $this->name, $this->email);
    }
}
