<?php

declare(strict_types=1);

namespace Musement\Tests\Mother\Application\Mailer;

use Musement\Application\Mailer\Recipient;

class RecipientMother
{
    public static function random(): Recipient
    {
        $factories = [
            static function () {
                return self::to();
            },
            static function () {
                return self::cc();
            },
            static function () {
                return self::bcc();
            },
        ];


        $factory = $factories[\array_rand($factories)];

        return $factory();
    }

    public static function withName(string $name): Recipient
    {
        return Recipient::to('email@test.com', $name);
    }

    public static function to(): Recipient
    {
        return Recipient::to(
            ...self::getRandomParams()
        );
    }

    public static function cc(): Recipient
    {
        return Recipient::cc(
            ...self::getRandomParams()
        );
    }

    public static function bcc(): Recipient
    {
        return Recipient::bcc(
            ...self::getRandomParams()
        );
    }

    private static function getRandomParams(): array
    {
        $paramSets = [
            ['email@test.com', 'Random guy'],
            ['just@email.com']
        ];

        return $paramSets[\array_rand($paramSets)];
    }
}
