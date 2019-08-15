<?php

declare(strict_types=1);

namespace Musement\Tests\Unit\Application\Mailer;

use Musement\Application\Mailer\Recipient;
use Musement\Application\Mailer\Recipients;
use Musement\Tests\Mother\Application\Mailer\RecipientMother;
use PHPUnit\Framework\TestCase;

class RecipientsTest extends TestCase
{
    public function test_is_empty(): void
    {
        self::assertTrue(
            (new Recipients())->empty()
        );

        self::assertFalse(
            (new Recipients(
                RecipientMother::random()
            ))
                ->empty()
        );
    }

    public function test_count(): void
    {
        self::assertEquals(
            0,
            (new Recipients())->count()
        );

        self::assertEquals(
            1,
            (new Recipients(
                RecipientMother::random()
            ))->count()
        );

        self::assertEquals(
            2,
            (new Recipients(
                RecipientMother::random(),
                RecipientMother::random()
            ))->count()
        );
    }

    public function test_filtering(): void
    {
        $base = new Recipients(
            $to1 = RecipientMother::to(),
            $cc1 = RecipientMother::cc(),
            $bcc1 = RecipientMother::bcc(),
            $to2 = RecipientMother::to(),
            $cc2 = RecipientMother::cc(),
            $bcc2 = RecipientMother::bcc(),
        );

        self::assertEquals(
            new Recipients($to1, $to2),
            $base->onlyTo()
        );

        self::assertEquals(
            new Recipients($cc1, $cc2),
            $base->onlyCc()
        );

        self::assertEquals(
            new Recipients($bcc1, $bcc2),
            $base->onlyBcc()
        );

        self::assertEquals(
            new Recipients($to1, $cc1),
            $base->filter(
                static function (Recipient $recipient) use ($to1, $cc1) {
                    return \in_array($recipient, [$to1, $cc1], $strict = true);
                }
            )
        );
    }

    public function test_each(): void
    {
        $base = new Recipients(
            $recipientA = RecipientMother::random(),
            $recipientB = RecipientMother::random(),
        );

        $calledFor = [];

        $base->each(
            static function (Recipient $recipient) use (&$calledFor) {
                $calledFor[] = $recipient;
            }
        );

        self::assertEquals(
            [$recipientA, $recipientB],
            $calledFor
        );
    }

    public function test_map(): void
    {
        $base = new Recipients(
            RecipientMother::withName($name1 = 'name-1'),
            RecipientMother::withName($name2 = 'name-2'),
            RecipientMother::withName($name3 = 'name-3'),
        );

        self::assertEquals(
            [$name1, $name2, $name3],
            $base->map(
                static function (Recipient $recipient) {
                    return $recipient->name();
                }
            )
        );
    }
}
