<?php

declare(strict_types=1);

namespace Musement\Application\Mailer\File;

use Musement\Application\Assertion;
use Musement\Application\Mailer\FileInterface;

class InMemoryFile implements FileInterface
{
    private $name;
    private $content;

    public function __construct(string $name, string $content)
    {
        Assertion::notEmpty($name);

        $this->name = $name;
        $this->content = $content;
    }

    public function filename(): string
    {
        return $this->name;
    }

    public function content(): string
    {
        return $this->content;
    }
}
