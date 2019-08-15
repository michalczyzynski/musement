<?php

declare(strict_types=1);

namespace Musement\UserInterface\CLI\Command;

use Musement\Application\MusementCatalog\Facade;
use Musement\UserInterface\CLI\Command\Argument\LocaleArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpSitemapCommand extends CommandWithArguments
{
    public const NAME = 'dump-sitemap';

    private $localeArgument;
    private $facade;

    public function __construct(Facade $facade)
    {
        $this->facade = $facade;

        parent::__construct(
            $this->localeArgument = new LocaleArgument()
        );
    }

    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::NAME)
            ->setDescription('Dump sitemap to stdout.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            (string) $this->facade->generateSitemap(
                $this->localeArgument->value($input)
            )
        );

        return 0;
    }
}
