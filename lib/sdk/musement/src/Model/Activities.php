<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Model;

use Musement\SDK\Musement\Model\Activities\Activity;

final class Activities
{
    private $totalCount;
    private $activities;

    public function __construct(int $totalCount, Activity ...$activities)
    {
        $this->totalCount = $totalCount;
        $this->activities = $activities;
    }

    public function totalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @param callable $callback Callback args: \Musement\SDK\Musement\Activities\Model\Activity
     */
    public function each(callable $callback): void
    {
        \array_map($callback, $this->activities);
    }
}
