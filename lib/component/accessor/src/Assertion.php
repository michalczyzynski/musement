<?php

declare(strict_types=1);

namespace Musement\Component\Accessor;

use Assert\Assertion as BaseAssertion;
use Musement\Component\Accessor\Exception\AssertionException;

final class Assertion extends BaseAssertion
{
    private const NOT_UNIQUE = 230;

    protected static $exceptionClass = AssertionException::class;

    public static function uniqueInstances(array $objects, $message = null, $propertyPath = null): void
    {
        $past = [];

        foreach ($objects as $element) {
            if (in_array($element, $past, true)) {
                throw static::createException(
                    $objects,
                    static::generateMessage($message ?: 'Objects are not unique.'),
                    static::NOT_UNIQUE,
                    $propertyPath
                );
            }

            $past[] = $element;
        }
    }
}
