<?php

declare(strict_types=1);

namespace Musement\Application\Mailer;

class Recipients
{
    private $recipients;

    public function __construct(Recipient ...$recipients)
    {
        $this->recipients = $recipients;
    }

    public function count(): int
    {
        return \count($this->recipients);
    }

    public function empty(): bool
    {
        return 0 === $this->count();
    }

    /**
     * @param callable $callback Callback arguments: \Musement\Application\Mailer\Recipient
     */
    public function filter(callable $callback): self
    {
        return new self(
            ...\array_filter(
                $this->recipients,
                $callback
            )
        );
    }

    /**
     * @param callable $callback Callback arguments: \Musement\Application\Mailer\Recipient
     */
    public function each(callable $callback): void
    {
        \array_map(
            $callback,
            $this->recipients
        );
    }

    /**
     * @param callable $callback Callback arguments: \Musement\Application\Mailer\Recipient
     */
    public function map(callable $callback): array
    {
        return \array_map(
            $callback,
            $this->recipients
        );
    }

    public function onlyTo(): self
    {
        return $this->filter(
            static function (Recipient $recipient) {
                return $recipient->isTo();
            }
        );
    }

    public function onlyCc(): self
    {
        return $this->filter(
            static function (Recipient $recipient) {
                return $recipient->isCc();
            }
        );
    }

    public function onlyBcc(): self
    {
        return $this->filter(
            static function (Recipient $recipient) {
                return $recipient->isBcc();
            }
        );
    }
}
