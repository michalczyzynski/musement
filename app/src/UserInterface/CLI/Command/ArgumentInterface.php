<?php

declare(strict_types=1);

namespace Musement\UserInterface\CLI\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

interface ArgumentInterface
{
    public function configure(Command $command): void;

    /**
     * @throws \Exception
     */
    public function validate(InputInterface $input): void;

    /**
     * @return mixed
     */
    public function value(InputInterface $input);
}
