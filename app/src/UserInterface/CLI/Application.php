<?php

declare(strict_types=1);

namespace Musement\UserInterface\CLI;

use Symfony\Bundle\FrameworkBundle\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    private $commandsRegistered = false;

    protected function registerCommands()
    {
        if ($this->commandsRegistered) {
            return;
        }

        $this->commandsRegistered = true;

        $this->getKernel()->boot();
        $container = $this->getKernel()->getContainer();

        $this->setCommandLoader($container->get('musement.console.command_loader'));
    }
}
