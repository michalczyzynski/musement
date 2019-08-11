<?php

declare(strict_types=1);

namespace Musement\Component\Accessor\Tests\Unit\Path;

use Musement\Component\Accessor\Exception\RuntimeException;
use Musement\Component\Accessor\Exception\OutOfBoundsException;
use Musement\Component\Accessor\Path\Cursor;
use Musement\Component\Accessor\Path\Node;
use PHPUnit\Framework\TestCase;

final class CursorTest extends TestCase
{
    public function test_throws_exception_when_creating_empty_cursor(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Cannot create empty cursor.');

        new Cursor();
    }

    public function test_throws_exception_when_passing_same_node_twice(): void
    {
        $nodes = [
            $node = new Node('node 1'),
            new Node('node 2'),
            $node
        ];

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Nodes are not unique.');

        new Cursor(...$nodes);
    }

    public function test_upon_creation_points_for_first_node(): void
    {
        $cursor = new Cursor(
            $node = new Node('a')
        );

        self::assertEquals($node, $cursor->current());

        $anotherCursor = new Cursor(
            $node,
            new Node('b'),
            new Node('c')
        );

        self::assertEquals($node, $cursor->current());
    }

    public function test_throws_exception_when_moving_cursor_before_first_element(): void
    {
        $cursor = new Cursor(
            new Node('a'),
            new Node('b'),
            new Node('c'),
        );

        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Cursor points to a root node, cannot leave.');

        $cursor->prev();
    }

    public function test_throws_exception_when_moving_cursor_beyond_last_element(): void
    {
        $cursor = new Cursor(
            new Node('a')
        );

        self::expectException(OutOfBoundsException::class);
        self::expectExceptionMessage('Cursor points to a leaf node, cannot enter.');

        $cursor->next();
    }

    public function test_cursor_is_immutable_on_traversal(): void
    {
        $cursor = new Cursor(
            $nodeA = new Node('a'),
            $nodeB = new Node('b'),
            $nodeC = new Node('c'),
        );

        self::assertEquals($nodeA, $cursor->current());

        $otherCursor = $cursor->next()->next();

        self::assertEquals($nodeA, $cursor->current());
        self::assertEquals($nodeC, $otherCursor->current());

        $yetAnotherOne = $otherCursor->prev();

        self::assertEquals($nodeA, $cursor->current());
        self::assertEquals($nodeC, $otherCursor->current());
        self::assertEquals($nodeB, $yetAnotherOne->current());
    }

    public function test_seek_throws_exception_when_passing_node_not_from_cursor(): void
    {
        $cursor = new Cursor(
            new Node('a'),
            new Node('b'),
            new Node('c')
        );
        
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Node "new" is not part of this cursor.');
        
        $cursor->seek(new Node('new'));
    }

    public function test_seek_is_immutable(): void
    {
        $cursor = new Cursor(
            $nodeA = new Node('a'),
            $nodeB = new Node('b'),
            $nodeC = new Node('c'),
            );

        self::assertEquals($nodeA, $cursor->current());

        $otherCursor = $cursor->seek($nodeC);

        self::assertEquals($nodeA, $cursor->current());
        self::assertEquals($nodeC, $otherCursor->current());
    }
}
