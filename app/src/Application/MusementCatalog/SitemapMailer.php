<?php

declare(strict_types=1);

namespace Musement\Application\MusementCatalog;

use Musement\Application\Mailer\Email;
use Musement\Application\Mailer\File\InMemoryFile;
use Musement\Application\Mailer\MailClientInterface;
use Musement\Application\Mailer\Recipients;
use Musement\Application\Mailer\Sender;
use Musement\Application\MusementCatalog\SitemapMailer\SubjectFactory;

class SitemapMailer
{
    private $sitemapFactory;
    private $mailClient;
    private $sender;
    private $subjectFactory;

    public function __construct(
        SitemapFactoryInterface $sitemapFactory,
        MailClientInterface $mailClient,
        Sender $sender,
        SubjectFactory $subjectFactory
    ) {
        $this->sitemapFactory = $sitemapFactory;
        $this->mailClient = $mailClient;
        $this->sender = $sender;
        $this->subjectFactory = $subjectFactory;
    }

    public function sendTo(Recipients $recipients, Locale $locale): void
    {
        $this->mailClient->send(
            Email::withAttachments(
                $this->sender,
                $recipients,
                $this->subjectFactory->createSubject($locale),
                '',
                new InMemoryFile(
                    'sitemap.xml',
                    (string) $this->sitemapFactory->create($locale)
                )
            )
        );
    }
}
