<?php

declare(strict_types=1);

namespace Musement\SDK\Musement;

use Assert\Assertion as BaseAssertion;
use Musement\SDK\Musement\Exception\AssertionException;

final class Assertion extends BaseAssertion
{
    protected static $exceptionClass = AssertionException::class;
}
