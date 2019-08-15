<?php

declare(strict_types=1);

namespace Musement\Tests\Unit\Application\Mailer;

use Musement\Application\Exception\RuntimeException;
use Musement\Application\Mailer\Email;
use Musement\Application\Mailer\Recipients;
use Musement\Tests\Mother\Application\Mailer\SenderMother;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_email_requires_recipients(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Email requires at least 1 recipient.');

        new Email(
            SenderMother::random(),
            new Recipients(),
            'subject',
            'body'
        );
    }
}
