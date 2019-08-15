<?php

declare(strict_types=1);

namespace Musement\UserInterface\CLI\Command\Argument;

use Musement\Application\Mailer\Recipient;
use Musement\Application\Mailer\Recipients;
use Musement\UserInterface\CLI\Command\ArgumentInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class RecipientsArgument implements ArgumentInterface
{

    public function configure(Command $command): void
    {
        $command->addArgument('recipients', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Email recipients');
    }

    public function validate(InputInterface $input): void
    {
        $recipients = $input->getArgument('recipients');

        if (0 === \count($recipients)) {
            throw new \Exception('Missing recipients\' emails.');
        }
    }

    public function value(InputInterface $input): Recipients
    {
        return new Recipients(
            ...\array_map(
                function (string $email) {
                    return Recipient::to($email);
                },
                $input->getArgument('recipients')
            )
        );
    }
}
