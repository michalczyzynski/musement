<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Tests\Unit;

use Musement\Component\Accessor\Exception\RuntimeException;
use Musement\Component\Accessor\Path;
use PHPUnit\Framework\TestCase;

final class PathTest extends TestCase
{
    /**
     * @dataProvider invalid_paths_data_provider
     */
    public function test_create_from_string_validation(string $path, string $expectedMessage): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage($expectedMessage);

        Path::fromString($path);
    }

    public function invalid_paths_data_provider(): \Generator
    {
        yield [
            $path = '',
            $expectedError = 'Path "": Path cannot be empty.',
        ];

        yield [
            $path = '.',
            $expectedError = 'Path ".": Node cannot be empty.',
        ];


        yield [
            $path = 'path..path',
            $expectedError = 'Path "path..path": Node cannot be empty.',
        ];


        yield [
            $path = 'path.path.path..',
            $expectedError = 'Path "path.path.path..": Node cannot be empty.',
        ];

        yield [
            $path = \implode('.', \range(1, 51)),
            $expectedError = 'Path "1.2.3.4.5.6.7.8.9.10.11.12.13.14.15.16.17.18.19.20.21.22.23.24.25.26.27.28.29.30.31.32.33.34.35.36.37.38.39.40.41.42.43.44.45.46.47.48.49.50.51": Path can contain between 1 and 50 elements.',
        ];
    }

    /**
     * @dataProvider valid_paths_data_provider
     */
    public function test_creates_nodes(string $path, array $expectedNodes): void
    {
        $path = Path::fromString($path);
        $nodes = [];

        do {
            $nodes[] = $path->node();
        } while ($path->canEnter() && $path = $path->enter());

        self::assertEquals($expectedNodes, $nodes);
    }

    public function valid_paths_data_provider(): \Generator
    {
        yield [
            $path = 'a',
            $expectedNodes = ['a'],
        ];

        yield [
            $path = 'a.b.c',
            $expectedNodes = ['a', 'b', 'c'],
        ];

        yield [
            $path = \implode('.', $elements = range(1, 50)), // 50 elements joined together
            $expectedNodes = $elements,
        ];

        yield [
            $path = '0',
            $expectedNodes = ['0']
        ];

        yield [
            $path = ' . ',
            $expectedNodes = [' ', ' ']
        ];
    }

    public function test_path_is_immutable(): void
    {
        $path = Path::fromString('a.b.c.d.e');
        $newPath = $path->enter()->enter();

        self::assertEquals('a', $path->node());
        self::assertEquals('c', $newPath->node());

        $newestPath = $newPath->leave();

        self::assertEquals('a', $path->node());
        self::assertEquals('c', $newPath->node());
        self::assertEquals('b', $newestPath->node());
    }

    public function test_can_enter(): void
    {
        $path = Path::fromString('a.b.c');

        self::assertEquals('a', $path->node());
        self::assertTrue($path->canEnter());

        $path = $path->enter();

        self::assertEquals('b', $path->node());
        self::assertTrue($path->canEnter());

        $path = $path->enter();

        self::assertEquals('c', $path->node());
        self::assertFalse($path->canEnter());
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Cursor points to a leaf node, cannot enter.');

        $path->enter();
    }

    public function test_can_leave(): void
    {
        $path = Path::fromString('a.b.c');
        $path = $path->enter()->enter();

        self::assertEquals('c', $path->node());
        self::assertTrue($path->canLeave());

        $path = $path->leave();

        self::assertEquals('b', $path->node());
        self::assertTrue($path->canLeave());

        $path = $path->leave();

        self::assertEquals('a', $path->node());
        self::assertFalse($path->canLeave());
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Cursor points to a root node, cannot leave.');

        $path->leave();
    }

    public function test_can_enter_when_single_node_present():void
    {
        $path = Path::fromString('abc');

        self::assertFalse($path->canEnter());
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Cursor points to a leaf node, cannot enter.');

        $path->enter();
    }

    public function test_can_leave_when_single_node_present():void
    {
        $path = Path::fromString('abc');

        self::assertFalse($path->canLeave());
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Cursor points to a root node, cannot leave.');

        $path->leave();
    }

    public function test_get_path_to_current_node(): void
    {
        $path = Path::fromString('abc.def.ghi.jkl');

        $currentPath = $path->pathToCurrentNode();

        self::assertEquals('abc', (string) $currentPath);
        self::assertFalse($currentPath->canEnter());
        self::assertFalse($currentPath->canLeave());

        $currentPath = $path->enter()->enter()->pathToCurrentNode();

        self::assertEquals('abc.def.ghi', (string) $currentPath);
        self::assertEquals('ghi', $currentPath->node());
        self::assertTrue($currentPath->canLeave());
    }

    public function test_extend_path(): void
    {
        $path = Path::fromString('abc.def.ghi');
        $newPath = $path->enter();

        self::assertEquals('def', $newPath->node());

        $extendedPath = $newPath->extend('jkl.mno.prs');

        self::assertEquals('def', $newPath->node());
        self::assertEquals('jkl', $extendedPath->node());

        self::assertEquals('abc.def.ghi', $newPath);
        self::assertEquals('abc.def.ghi.jkl.mno.prs', $extendedPath);
    }
}
