<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Tests\Unit;

use Musement\Component\Accessor\AccessorInterface;
use Musement\Component\Accessor\Exception\JsonDecodeException;
use Musement\Component\Accessor\Exception\RuntimeException;
use Musement\Component\Accessor\JsonAccessor;
use Musement\Component\Accessor\Tests\Double\Exception\CustomException;
use PHPUnit\Framework\TestCase;

final class JsonAccessorTest extends TestCase
{
    /**
     * @dataProvider invalid_json_provider
     */
    public function test_throws_exception_on_invalid_json(string $json): void
    {
        self::expectException(JsonDecodeException::class);
        self::expectExceptionMessage('Syntax error');

        new JsonAccessor($json);
    }

    public function invalid_json_provider(): \Generator
    {
        yield [''];
        yield ["{'wrong_escape' = 'error'}"];
        yield ['just a string'];
    }

    /**
     * @dataProvider empty_json_data_provider
     */
    public function test_create_empty_accessor(string $json): void
    {
        $accessor = new JsonAccessor($json);

        self::assertEquals([], $accessor->value());
        self::assertFalse($accessor->has('anything'));
    }

    public function empty_json_data_provider(): \Generator
    {
        yield ['{}'];
        yield ['[]'];
    }

    public function test_can_fetch_root(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/small.json')
        );

        self::assertEquals(
            $expectedStructure = [
                'this' => [
                    'is' => ['rather', 'small']
                ]
            ],
            $accessor->value()
        );
    }

    public function test_can_fetch_node(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/random_collection.json')
        );

        self::assertTrue($accessor->has($path = '1.meta'));
        self::assertEquals(
            [
                'weight' => 19,
                'description' => 'Here be description.',
                'type' => 'node',
                'sink' => [
                    'hole' => 'oh my',
                ]
            ],
            $accessor->at($path)->value()
        );
    }

    public function test_can_fetch_leaf(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/random_collection.json')
        );

        self::assertTrue($accessor->has($path = '0.top'));
        self::assertEquals(false, $accessor->at($path)->value());
    }

    public function test_can_enter_multiple_times(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/random_collection.json')
        );

        self::assertEquals(
            'oh my',
            $accessor->at('1.meta')->at('sink.hole')->string()
        );
    }

    public function test_fetch_typed_string(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $dataAtPath = $accessor->at('config.0.types.string');

        self::assertEquals(
            'this is string',
            $dataAtPath->value()
        );
        self::assertEquals(
            $dataAtPath->value(),
            $dataAtPath->string()
        );
    }

    public function test_fetch_typed_int(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $dataAtPath = $accessor->at('config.0.types.integer');

        self::assertEquals(
            10,
            $dataAtPath->value()
        );
        self::assertEquals(
            $dataAtPath->value(),
            $dataAtPath->integer()
        );
    }

    public function test_fetch_typed_float(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $dataAtPath = $accessor->at('config.0.types.float');

        self::assertEquals(
            15.33,
            $dataAtPath->value()
        );
        self::assertEquals(
            $dataAtPath->value(),
            $dataAtPath->float()
        );
    }

    public function test_fetch_typed_bool(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $dataAtPath = $accessor->at('config.0.types.boolean');

        self::assertEquals(
            true,
            $dataAtPath->value()
        );
        self::assertEquals(
            $dataAtPath->value(),
            $dataAtPath->boolean()
        );
    }

    public function test_fetch_typed_array(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $dataAtPath = $accessor->at('config.0.types.array');

        self::assertEquals(
            ['a', 2, 'c'],
            $dataAtPath->value()
        );
        self::assertEquals(
            $dataAtPath->value(),
            $dataAtPath->array()
        );
    }

    /**
     * @dataProvider invalid_types_data_provider
     */
    public function test_throws_exception_on_invalid_data_type(string $path, callable $callback, string $expectedMessage, string $expectedException = RuntimeException::class): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $dataAtPath = $accessor->at($path);

        self::expectException($expectedException);
        self::expectExceptionMessage($expectedMessage);

        $callback($dataAtPath);
    }

    public function invalid_types_data_provider(): \Generator
    {
        $exceptionMessage = 'This is test.';

        $prefix = 'config.0.types.';
        $types = [
            $string = 'string',
            $integer = 'integer',
            $float = 'float',
            $bool = 'boolean',
            $array = 'array',
        ];

        $typeNamesToReplace = [
            $float => 'double'
        ];

        foreach ($types as $currentType) {
            foreach (array_diff($types, [$currentType]) as $otherType) {
                yield [
                    $path = $prefix . $otherType,
                    $callback = function (AccessorInterface $accessor) use ($currentType) {
                        $accessor->$currentType();
                    },
                    $expectedMessage = sprintf('Expected %s, got %s.', $currentType, $typeNamesToReplace[$otherType] ?? $otherType),
                ];

                yield [
                    $path,
                    $callback = function (AccessorInterface $accessor) use ($currentType, $exceptionMessage) {
                        $accessor->$currentType(CustomException::class, $exceptionMessage);
                    },
                    $exceptionMessage,
                    CustomException::class,
                ];
            }
        }
    }

    /**
     * @dataProvider invalid_paths_data_provider
     */
    public function test_cannot_get_to_path(string $path, string $expectedMessage, string $expectedException = RuntimeException::class): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        self::expectException($expectedException);
        self::expectExceptionMessage($expectedMessage);

        $accessor->at($path);
    }

    /**
     * @dataProvider invalid_paths_data_provider
     */
    public function test_cannot_get_to_path_custom_exceptions(string $path): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $expectedException = CustomException::class;
        $expectedMessage = 'This is test.';

        self::expectException($expectedException);
        self::expectExceptionMessage($expectedMessage);

        $accessor->at($path, $expectedException, $expectedMessage);
    }

    public function invalid_paths_data_provider(): \Generator
    {
        // Last node of path doesn't exist.
        yield [
            'config.0.types.lasagna',
            'Path config.0.types.lasagna does not exist.'
        ];

        // Middle node of path doesn't exist.
        yield [
            'config.0.credentials.aws.username',
            'Path config.0.credentials (part of config.0.credentials.aws.username) does not exist.'
        ];

        // One of the nodes in the path is not an array
        yield [
            'config.0.types.string.lowercased.length',
            'Data at config.0.types.string is not an array.'
        ];
    }

    /**
     * @dataProvider invalid_paths_on_second_traversal_data_provider
     */
    public function test_invalid_path_on_second_traversal(string $path, string $expectedMessage, string $expectedException = RuntimeException::class): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $accessor = $accessor->at('config.0');

        self::expectException($expectedException);
        self::expectExceptionMessage($expectedMessage);

        $accessor->at($path);
    }

    /**
     * @dataProvider invalid_paths_on_second_traversal_data_provider
     */
    public function test_invalid_path_on_second_traversal_custom_exceptions(string $path): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $expectedException = CustomException::class;
        $expectedMessage = 'This is test.';

        self::expectException($expectedException);
        self::expectExceptionMessage($expectedMessage);

        $accessor->at($path, $expectedException, $expectedMessage);
    }

    public function invalid_paths_on_second_traversal_data_provider(): \Generator
    {
        // Last node of path doesn't exist.
        yield [
            'types.lasagna',
            'Path config.0.types.lasagna does not exist.'
        ];

        // Middle node of path doesn't exist.
        yield [
            'credentials.aws.username',
            'Path config.0.credentials (part of config.0.credentials.aws.username) does not exist.'
        ];

        // One of the nodes in the path is not an array
        yield [
            'types.string.lowercased.length',
            'Data at config.0.types.string is not an array.'
        ];
    }

    public function test_data_after_traversal_is_not_an_array_to_traverse_again(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $accessor = $accessor->at('config.0.types.string');

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Data at config.0.types.string is not an array.');

        $accessor->at('test');
    }

    public function test_successful_map(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $accessor = $accessor->at('config.0.types');

        self::assertEquals(
            [
                'this is string',
                10,
                15.33,
                true,
                ['a', 2, 'c'],
            ],
            $accessor->map(
                function (AccessorInterface $accessor) {
                    return $accessor->value();
                }
            )
        );
    }

    public function test_map_fails_when_target_is_not_an_array(): void
    {
        $accessor = new JsonAccessor(
            file_get_contents(__DIR__ . '/../Resource/types.json')
        );

        $accessor = $accessor->at('config.0.types.string');

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Expected array, got string.');

        $accessor->map(function() {});
    }
}
