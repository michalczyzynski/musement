<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Model;

use Musement\SDK\Musement\Activities\Model\Activity;

final class Activities
{
    private $activities;

    public function __construct(Activity ...$activities)
    {
        $this->activities = $activities;
    }

    /**
     * @param callable $callback Callback args: \Musement\SDK\Musement\Activities\Model\Activity
     */
    public function map(callable $callback): array
    {
        return \array_map($callback, $this->activities);
    }
}
