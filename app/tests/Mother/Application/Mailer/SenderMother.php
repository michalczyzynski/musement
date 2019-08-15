<?php

declare(strict_types=1);

namespace Musement\Tests\Mother\Application\Mailer;

use Musement\Application\Mailer\Sender;

class SenderMother
{
    public static function random(): Sender
    {
        $paramSets = [
            ['email@test.com', 'Random guy'],
            ['just@email.com']
        ];

        $params = $paramSets[\array_rand($paramSets)];

        return new Sender(...$params);
    }
}
