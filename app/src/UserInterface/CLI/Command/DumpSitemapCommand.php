<?php

declare(strict_types=1);

namespace Musement\UserInterface\CLI\Command;

use Musement\Application\Musement\Locale;
use Musement\Application\Musement\Sitemap\CitiesAndActivitiesSitemap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DumpSitemapCommand extends Command
{
    public const NAME = 'dump-sitemap';

    private const LOCALES = [
        self::LOCALE_SPANISH,
        self::LOCALE_ITALIAN,
        self::LOCALE_FRENCH,
    ];

    private const LOCALE_SPANISH = 'es';
    private const LOCALE_ITALIAN = 'it';
    private const LOCALE_FRENCH = 'fr';

    private $citiesAndActivitiesSitemap;

    public function __construct(CitiesAndActivitiesSitemap $citiesAndActivitiesSitemap)
    {
        parent::__construct();

        $this->citiesAndActivitiesSitemap = $citiesAndActivitiesSitemap;
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Dump sitemap to file or stdout.')
            ->addArgument('locale', InputArgument::REQUIRED, 'Locale for the sitemap, one of: ' . implode(', ', self::LOCALES));
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $locale = $input->getArgument('locale');

        if (empty($locale)) {
            throw new \Exception(sprintf(
                'Missing locale - pass one of %s.',
                implode(', ', self::LOCALES)
            ));
        }

        if (!in_array($locale, self::LOCALES)) {
            throw new \Exception(sprintf(
                'Invalid locale, got "%s", expected one of %s.',
                $locale,
                implode(', ', self::LOCALES)
            ));
        }
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($locale = $input->getArgument('locale')) {
            case self::LOCALE_SPANISH:
                $locale = Locale::spanish();

                break;

            case self::LOCALE_ITALIAN:
                $locale = Locale::italian();

                break;

            case self::LOCALE_FRENCH:
                $locale = Locale::french();

                break;

            default:
                throw new \Exception(sprintf(
                    'Invalid locale, got "%s", expected one of %s.',
                    $locale,
                    implode(', ', self::LOCALES)
                ));
        }
        $io = new \Symfony\Component\Console\Style\SymfonyStyle($input, $output);

        $io->title('Building sitemap');

        $sitemap = $this->citiesAndActivitiesSitemap->createSitemap($locale);

        $io->writeln('');
        $io->writeln($sitemap);
    }
}
