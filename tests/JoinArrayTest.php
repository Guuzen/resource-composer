<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\Join\JoinArray;

final class JoinArrayTest extends \PHPUnit\Framework\TestCase
{
    public function testZeroLoadIdsWhenThereIsNoJoinBy(): void
    {
        $resource = new \ArrayObject(['id' => '1']);
        $join     = new JoinArray('foo', 'joinTo');

        self::assertEquals([], $join->loadIds($resource));
    }

    public function testResolveWithEmptyArrayWhenThereIsNoJoinBy(): void
    {
        $id       = 'someId';
        $resource = new \ArrayObject(['id' => $id]);
        $join     = new JoinArray('foo', 'joinTo');
        $groups   = [];

        $join->resolve($resource, $groups);

        self::assertEquals(new \ArrayObject(['id' => $id, 'joinTo' => []]), $resource);
    }

    public function testNotResolveNullValuesInArray(): void
    {
        $id       = 'someId';
        $resource = new \ArrayObject(['ids' => [$id, null]]);
        $join     = new JoinArray('ids', 'joinTo');
        $groups   = [$id => 'value'];

        $join->resolve($resource, $groups);

        self::assertEquals(
            new \ArrayObject(
                [
                    'ids'    => [$id, null],
                    'joinTo' => ['value'],
                ]
            ),
            $resource
        );
    }

    public function testNotResolveWhenNoGroupsForId(): void
    {
        $id       = 'someId';
        $resource = new \ArrayObject(['ids' => [$id]]);
        $join     = new JoinArray('ids', 'joinTo');
        $groups   = ['anotherId' => 'value'];

        $join->resolve($resource, $groups);

        self::assertEquals(
            new \ArrayObject(
                [
                    'ids'    => [$id],
                    'joinTo' => [],
                ]
            ),
            $resource
        );
    }

}
