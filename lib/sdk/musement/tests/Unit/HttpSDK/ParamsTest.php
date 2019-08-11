<?php

declare(strict_types=1);

namespace Musement\SDK\Musement\Tests\Unit\HttpSDK;

use Musement\SDK\Musement\Exception\RuntimeException;
use Musement\SDK\Musement\HttpSDK\Params;
use Musement\SDK\Musement\ToStringInterface;
use PHPUnit\Framework\TestCase;

final class ParamsTest extends TestCase
{
    public function test_accepts_scalars(): void
    {
        $params = new Params($rawParams = [
            'string' => 'abc',
            'empty string' => '',
            'int' => 10,
            'another_int' => 0,
            'float' => 6.6,
            'bool' => true,
            'anonymous class' => new class implements ToStringInterface {
                public function __toString(): string
                {
                    return 'totally random';
                }
            }
        ]);

        self::assertEquals(
            $rawParams,
            $params->all()
        );
    }

    /**
     * @dataProvider invalid_params_provider
     */
    public function test_fails_on_invalid_params($rawParam): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Only scalars and object implementing ToStringInterface are accepted.');

        new Params(['key' => $rawParam]);
    }

    public function invalid_params_provider(): \Generator
    {
        yield [null];
        yield [[]];
        yield [[1,2,3]];
        yield [new \stdClass()];
    }

    public function test_throws_exception_on_missing_param(): void
    {
        $params = new Params(['a' => 123]);

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Missing "b" parameter.');

        $params->get('b');
    }

    public function test_get_param(): void
    {
        $params = new Params([$key = 'a' => $value = 123]);

        self::assertEquals(
            $value,
            $params->get($key)
        );
    }

    public function test_removing_non_existent_param_throws_exception(): void
    {
        $params = new Params(['a' => 123]);

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Parameter "b" does not exist.');

        $params->remove('b');
    }

    public function test_removing_param(): void
    {
        $params = new Params($rawParams = [
            'a' => 123,
            'b' => 'yep',
            'c' => 'still here',
        ]);
        $newParams = $params->remove('b');
        $emptyParams = $newParams->remove('a')->remove('c');

        self::assertEquals(
            $rawParams,
            $params->all()
        );
        self::assertEquals(
            [
                'a' => 123,
                'c' => 'still here',
            ],
            $newParams->all()
        );
        self::assertEquals(
            [],
            $emptyParams->all()
        );
    }

    /**
     * @dataProvider query_string_provider
     */
    public function test_build_query_string(array $rawParams, string $expectedQueryString): void
    {
        $params = new Params($rawParams);

        self::assertEquals(
            $expectedQueryString,
            $params->toQueryString()
        );
    }

    public function query_string_provider(): \Generator
    {
        yield [
            $rawParams = [],
            $queryString = '',
        ];

        yield [
            $rawParams = [
                'string' => 'abc',
                'int' => 10,
                'float' => 6.6,
                'bool' => true,
                'anonymous class' => new class implements ToStringInterface {
                    public function __toString(): string
                    {
                        return 'random';
                    }
                }
            ],
            $queryString = '?string=abc&int=10&float=6.6&bool=1&anonymous+class=random',
        ];
    }
}
