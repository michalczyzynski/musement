<?php

declare(strict_types=1);

namespace Musement\Tests\Unit\Application\Mailer;

use Musement\Application\Exception\RuntimeException;
use Musement\Application\Mailer\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function test_throws_exception_when_creating_with_empty_email(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Value "" was expected to be a valid e-mail address.');

        new Sender('');
    }

    public function test_throws_exception_when_creating_with_invalid_email(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Value "@not-valid-email@pl" was expected to be a valid e-mail address.');

        new Sender('@not-valid-email@pl');
    }

    public function test_throws_exception_on_too_long_name(): void
    {
        $email = 'email@test.com';
        $name = 'name';

        new Sender($email, \str_pad($name, 200));

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Name cannot be longer than 200 chars.');

        new Sender($email, \str_pad($name, 201));
    }
}
