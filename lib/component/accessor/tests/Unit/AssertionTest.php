<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Tests\Unit;

use Musement\Component\Accessor\Assertion;
use Musement\Component\Accessor\Exception\AssertionException;
use PHPUnit\Framework\TestCase;

final class AssertionTest extends TestCase
{
    public function test_unique_assertion_passes(): void
    {
        $instanceA = $this->createInstance();
        $instanceB = $this->createInstance();

        Assertion::uniqueInstances([$instanceA, $instanceB]);

        self::assertTrue(true, 'Passed');
    }

    public function test_unique_assertion_fails(): void
    {
        $instanceA = $this->createInstance();
        $instanceB = $this->createInstance();

        self::expectException(AssertionException::class);
        self::expectExceptionMessage('Objects are not unique.');

        Assertion::uniqueInstances([$instanceA, $instanceB, $instanceA]);
    }

    private function createInstance(): object
    {
        return new class {
            public function __toString()
            {
                return 'a';
            }
        };
    }
}
