<?php

declare(strict_types=1);

namespace Musement\Infrastructure\Application\Mailer\SwiftMailer;

use Musement\Application\Mailer\Email;
use Musement\Application\Mailer\Exception\EmailException;
use Musement\Application\Mailer\Exception\EmailPartiallySentException;
use Musement\Application\Mailer\FileInterface;
use Musement\Application\Mailer\MailClientInterface;
use Musement\Application\Mailer\Recipient;

class SwiftMailerClient implements MailClientInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(Email $email): void
    {
        $vendorEmail = (new \Swift_Message())
            ->setFrom($email->sender()->email(), $email->sender()->name())
            ->setSubject($email->subject())
            ->setBody($email->body());

        $email->recipients()->onlyTo()->each(
            static function (Recipient $recipient) use ($vendorEmail) {
                $vendorEmail->addTo($recipient->email(), $recipient->name());
            }
        );

        $email->recipients()->onlyCc()->each(
            static function (Recipient $recipient) use ($vendorEmail) {
                $vendorEmail->addCc($recipient->email(), $recipient->name());
            }
        );

        $email->recipients()->onlyBcc()->each(
            static function (Recipient $recipient) use ($vendorEmail) {
                $vendorEmail->addBcc($recipient->email(), $recipient->name());
            }
        );

        $email->eachAttachment(
            static function (FileInterface $file) use ($vendorEmail) {
                $vendorEmail->attach(
                    new \Swift_Attachment(
                        $file->content(),
                        $file->filename()
                    )
                );
            }
        );

        $failedEmails = [];

        try {
            $result = $this->mailer->send($vendorEmail, $failedEmails);
        } catch (\Throwable $e) {
            throw new EmailException(
                $email,
                'Failed trying to send an email: ' . $e->getMessage(),
                $e
            );
        }

        if (0 !== $result) {
            return;
        }

        throw new EmailPartiallySentException(
            $email,
            $failedRecipients = $email->recipients()->filter(
                static function (Recipient $recipient) use ($failedEmails) {
                    return \in_array($recipient->email(), $failedEmails);
                }
            ),
            sprintf(
                'Email could not be sent to %d out of %d recipients.',
                $failedRecipients->count(),
                $email->recipients()->count()
            )
        );
    }
}
