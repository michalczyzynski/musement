<?php

declare(strict_types=1);

namespace Musement\Application\Mailer;

use Musement\Application\Assertion;

class Email
{
    private $sender;
    private $recipients;
    private $subject;
    private $body;
    private $attachments;

    public function __construct(Sender $sender, Recipients $recipients, string $subject, string $body)
    {
        Assertion::false($recipients->empty(), 'Email requires at least 1 recipient.');

        $this->sender = $sender;
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = [];
    }

    public static function withAttachments(Sender $sender, Recipients $recipients, string $title, string $body, FileInterface ...$attachments): self
    {
        $email = new self(
            $sender,
            $recipients,
            $title,
            $body
        );
        $email->attachments = $attachments;

        return $email;
    }

    public function sender(): Sender
    {
        return $this->sender;
    }

    public function recipients(): Recipients
    {
        return $this->recipients;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function attachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param callable $callable Callback args: \Musement\Application\Mailer\FileInterface
     */
    public function eachAttachment(callable $callable): void
    {
        \array_map(
            $callable,
            $this->attachments
        );
    }
}
