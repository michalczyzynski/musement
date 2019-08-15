<?php

declare(strict_types=1);

namespace Musement\UserInterface\CLI\Command\Argument;

use Musement\Application\MusementCatalog\Locale;
use Musement\UserInterface\CLI\Command\ArgumentInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class LocaleArgument implements ArgumentInterface
{
    private const LOCALE_SPANISH = 'es';
    private const LOCALE_ITALIAN = 'it';
    private const LOCALE_FRENCH = 'fr';
    private const LOCALES = [
        self::LOCALE_SPANISH,
        self::LOCALE_ITALIAN,
        self::LOCALE_FRENCH,
    ];

    public function configure(Command $command): void
    {
        $command->addArgument('locale', InputArgument::REQUIRED, 'Locale for the sitemap, one of: ' . implode(', ', self::LOCALES));
    }

    public function validate(InputInterface $input): void
    {
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

    public function value(InputInterface $input): Locale
    {
        switch ($locale = $input->getArgument('locale')) {
            case self::LOCALE_SPANISH:
                return Locale::spanish();

                break;

            case self::LOCALE_ITALIAN:
                return Locale::italian();

                break;

            case self::LOCALE_FRENCH:
                return Locale::french();

                break;

            default:
                throw new \Exception(sprintf(
                    'Invalid locale, got "%s", expected one of %s.',
                    $locale,
                    implode(', ', self::LOCALES)
                ));
        }
    }

}
