<?php

declare(strict_types=1);

namespace Musement\Application\Mailer;

interface FileInterface
{
    public function filename(): string;
    public function content(): string;
}
