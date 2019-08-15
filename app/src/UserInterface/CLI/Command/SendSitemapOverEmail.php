<?php

declare(strict_types=1);

namespace Musement\UserInterface\CLI\Command;

use Musement\Application\MusementCatalog\Facade;
use Musement\UserInterface\CLI\Command\Argument\LocaleArgument;
use Musement\UserInterface\CLI\Command\Argument\RecipientsArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendSitemapOverEmail extends CommandWithArguments
{
    public const NAME = 'email-sitemap';

    private $facade;
    private $localeArgument;
    private $recipientsArgument;

    public function __construct(Facade $facade)
    {
        $this->facade = $facade;

        parent::__construct(
            $this->localeArgument = new LocaleArgument(),
            $this->recipientsArgument = new RecipientsArgument(),
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
        $recipients = $this->recipientsArgument->value($input);

        $this->facade->generateAndSendInEmail(
            $recipients,
            $this->localeArgument->value($input)
        );

        $output->writeln(sprintf('Sent sitemap to %d emails.', $recipients->count()));

        return 0;
    }
}
