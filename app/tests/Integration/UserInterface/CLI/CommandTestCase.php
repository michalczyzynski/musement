<?php

declare(strict_types=1);

namespace Musement\Tests\Integration\UserInterface\CLI;

use Musement\UserInterface\CLI\Application;
use Musement\UserInterface\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected static function getKernelClass()
    {
        return Kernel::class;
    }

    protected function get(string $service): object
    {
        return self::$kernel->getContainer()->get($service);
    }

    protected function executeCommand(string $commandName, array $input = []) : CommandTester
    {
        $application = new Application(self::$kernel);
        $command = $application->find($commandName);

        $commandTester = new CommandTester($command);
        $commandTester->execute($input);

        return $commandTester;
    }
}
