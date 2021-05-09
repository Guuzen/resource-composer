<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\Unit;

use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;
use PHPUnit\Framework\TestCase;

final class SimpleCollectorTest extends TestCase
{
    public function testIdExists(): void
    {
        $resource  = new \ArrayObject(['foo' => 1]);
        $collector = new SimpleCollector('foo', 'bar');

        $promises = $collector->collect($resource);
        $promise = $promises[0];

        self::assertEquals(1, $promise->id());
    }

    public function testIdNull(): void
    {
        $resource  = new \ArrayObject(['foo' => null]);
        $collector = new SimpleCollector('foo', 'bar');

        $promises = $collector->collect($resource);
        $promise = $promises[0];

        self::assertEquals(null, $promise->id());
    }

    public function testIdNotExists(): void
    {
        $resource  = new \ArrayObject([]);
        $collector = new SimpleCollector('foo', 'bar');

        $promises = $collector->collect($resource);
        $promise = $promises[0];

        self::assertEquals(null, $promise->id());
    }
}
