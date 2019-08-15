<?php

declare(strict_types=1);

namespace Musement\Application;

use Assert\Assertion as BaseAssertion;
use Musement\Application\Exception\AssertionException;

class Assertion extends BaseAssertion
{
    protected static $exceptionClass = AssertionException::class;
}
