<?php

declare(strict_types=1);

namespace Musement\UserInterface\CLI\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandWithArguments extends Command
{
    private $arguments;

    public function __construct(ArgumentInterface ...$arguments)
    {
        $this->arguments = $arguments;
        parent::__construct();
    }

    protected function configure()
    {
        foreach ($this->arguments as $argument) {
            $argument->configure($this);
        }
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        foreach ($this->arguments as $argument) {
            $argument->validate($input);
        }
    }
}
