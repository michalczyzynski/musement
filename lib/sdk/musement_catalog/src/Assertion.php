<?php

declare(strict_types=1);

namespace Musement\SDK\MusementCatalog;

use Assert\Assertion as BaseAssertion;
use Musement\SDK\MusementCatalog\Exception\AssertionException;

final class Assertion extends BaseAssertion
{
    protected static $exceptionClass = AssertionException::class;
}
