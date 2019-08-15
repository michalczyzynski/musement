<?php

declare(strict_types=1);

namespace Musement\Application\MusementCatalog;

use Musement\Application\Exception\RuntimeException;
use Musement\Application\Mailer\Recipient;
use Musement\Application\Mailer\Recipients;
use Musement\Application\Sitemap\Urlset;
use Psr\Log\LoggerInterface;

/**
 * Entry point for UI
 *
 * If it grows bigger, it could be replaced with a command handler
 */
class Facade
{
    private $sitemapFactory;
    private $sitemapMailer;
    private $logger;

    public function __construct(
        SitemapFactoryInterface $sitemapFactory,
        SitemapMailer $sitemapMailer,
        LoggerInterface $logger
    ) {
        $this->sitemapFactory = $sitemapFactory;
        $this->sitemapMailer = $sitemapMailer;
        $this->logger = $logger;
    }

    public function generateSitemap(Locale $locale): Urlset
    {
        try {
            return $this->sitemapFactory->create($locale);
        } catch (RuntimeException $e) {
            $this->logger->error(
                'Error generating sitemap: ' . $e->getMessage(),
                [
                    'locale' => (string) $locale,
                ]
            );

            throw $e;
        } catch (\Throwable $e) {
            $this->logFatalError($e);

            throw $e;
        }
    }

    public function generateAndSendInEmail(Recipients $recipients, Locale $locale): void
    {
        try {
            $this->sitemapMailer->sendTo($recipients, $locale);
        } catch (RuntimeException $e) {
            $this->logger->error(
                'Error sending sitemap over email: ' . $e->getMessage(),
                [
                    'locale' => (string) $locale,
                    'recipients' => $recipients->map(
                        static function (Recipient $recipient) {
                            return (string) $recipient;
                        }
                    ),
                ]
            );

            throw $e;
        } catch (\Throwable $e) {
            $this->logFatalError($e);

            throw $e;
        }
    }

    private function logFatalError(\Throwable $e): void
    {
        $message = 'Unexpected error occurred: ' . $e->getMessage();
        $stack = [];

        do {
            $stack[] = [
                'class' => get_class($e),
                'exception' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ];
        } while ($e = $e->getPrevious());


        $this->logger->critical($message, ['stack' => $stack]);
    }
}
