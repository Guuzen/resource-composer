<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\Join\JoinOne;

final class JoinOneTest extends \PHPUnit\Framework\TestCase
{
    public function testZeroLoadIdsWhenThereIsNoJoinBy(): void
    {
        $resource = new \ArrayObject(['id' => '1']);
        $join     = new JoinOne('foo', 'joinTo');

        self::assertEquals([], $join->loadIds($resource));
    }

    public function testResolveWithDefaultValueWhenThereIsNoJoinBy(): void
    {
        $id           = 'someId';
        $resource     = new \ArrayObject(['id' => $id]);
        $defaultValue = null;
        $join         = new JoinOne('foo', 'joinTo', $defaultValue);
        $groups       = [];
        $join->resolve($resource, $groups);

        self::assertEquals(new \ArrayObject(['id' => $id, 'joinTo' => $defaultValue]), $resource);
    }

    public function testResolveWithDefaultValueWhenNoGroupForId(): void
    {
        $id           = 'someId';
        $resource     = new \ArrayObject(['id' => $id]);
        $defaultValue = null;
        $join         = new JoinOne('id', 'joinTo', $defaultValue);
        $groups       = [];
        $join->resolve($resource, $groups);

        self::assertEquals(new \ArrayObject(['id' => $id, 'joinTo' => $defaultValue]), $resource);
    }
}
