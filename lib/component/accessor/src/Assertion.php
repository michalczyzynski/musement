<?php

declare(strict_types=1);

namespace Musement\Component\Accessor;

use Assert\Assertion as BaseAssertion;
use Musement\Component\Accessor\Exception\AssertionException;

final class Assertion extends BaseAssertion
{
    protected static $exceptionClass = AssertionException::class;
}
