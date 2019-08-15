<?php

declare(strict_types=1);

namespace Musement\Tests\Unit\Application\Mailer;

use Musement\Application\Exception\RuntimeException;
use Musement\Application\Mailer\Recipient;
use PHPUnit\Framework\TestCase;

class RecipientTest extends TestCase
{
    /**
     * @dataProvider recipient_factory_provider
     */
    public function test_throws_exception_when_creating_with_empty_email(callable $recipientFactory): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Value "" was expected to be a valid e-mail address.');

        $recipientFactory('');
    }

    /**
     * @dataProvider recipient_factory_provider
     */
    public function test_throws_exception_when_creating_with_invalid_email(callable $recipientFactory): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Value "@not-valid-email@pl" was expected to be a valid e-mail address.');

        $recipientFactory('@not-valid-email@pl');
    }

    /**
     * @dataProvider recipient_factory_provider
     */
    public function test_throws_exception_on_too_long_name(callable $recipientFactory): void
    {
        $email = 'email@test.com';
        $name = 'name';

        $recipientFactory($email, \str_pad($name, 200));

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Name cannot be longer than 200 chars.');

        $recipientFactory($email, \str_pad($name, 201));
    }

    public function recipient_factory_provider(): iterable
    {
        yield [
            function ($email, $name = null) {
                return Recipient::to(
                    $email,
                    $name
                );
            }
        ];

        yield [
            function ($email, $name = null) {
                return Recipient::cc(
                    $email,
                    $name
                );
            }
        ];

        yield [
            function ($email, $name = null) {
                return Recipient::bcc(
                    $email,
                    $name
                );
            }
        ];
    }

    public function test_recipient_to_type(): void
    {
        $to = Recipient::to('email@test.com');

        self::assertTrue($to->isTo());
        self::assertFalse($to->isCc());
        self::assertFalse($to->isBcc());
    }

    public function test_recipient_cc_type(): void
    {
        $cc = Recipient::cc('email@test.com');

        self::assertFalse($cc->isTo());
        self::assertTrue($cc->isCc());
        self::assertFalse($cc->isBcc());
    }

    public function test_recipient_bcc_type(): void
    {
        $bcc = Recipient::bcc('email@test.com');

        self::assertFalse($bcc->isTo());
        self::assertFalse($bcc->isCc());
        self::assertTrue($bcc->isBcc());
    }
}
