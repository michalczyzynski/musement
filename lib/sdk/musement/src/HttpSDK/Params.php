<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\HttpSDK;

use Musement\SDK\Musement\Assertion;
use Musement\SDK\Musement\ToStringInterface;

final class Params
{
    private $params = [];

    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            if ($value instanceof ToStringInterface) {
                $value = (string) $value;
            }

            Assertion::scalar($value, 'Only scalars and object implementing ToStringInterface are accepted.');

            $this->params[$key] = $value;
        }
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        Assertion::keyExists($this->params, $key, 'Missing "%s" parameter.');

        return $this->params[$key];
    }

    public function remove(string $key): self
    {
        Assertion::keyExists($this->params, $key, 'Parameter "%s" does not exist.');

        $newParams = $this->params;
        unset($newParams[$key]);

        return new self($newParams);
    }

    public function toQueryString(): string
    {
        return $this->empty()
            ? ''
            : '?' . \http_build_query($this->params);
    }

    public function all(): array
    {
        return $this->params;
    }

    private function empty(): bool
    {
        return 0 === \count($this->params);
    }
}
