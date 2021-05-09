<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\Unit;

use Guuzen\ResourceComposer\PromiseCollector\ArrayCollector;
use Guuzen\ResourceComposer\PromiseCollector\PromiseCollectionError;
use PHPUnit\Framework\TestCase;

final class ArrayCollectorTest extends TestCase
{
    public function testArrayOfKeysNotExists(): void
    {
        $this->expectException(PromiseCollectionError::class);

        $resource  = new \ArrayObject(['id' => '1']);
        $collector = new ArrayCollector('not-exists-key', 'foo');

        $collector->collect($resource);
    }

    public function testArrayOfKeysIsNotArray(): void
    {
        $this->expectException(PromiseCollectionError::class);

        $resource  = new \ArrayObject(['id' => '1', 'keys' => 1]);
        $collector = new ArrayCollector('keys', 'nonsense');

        $collector->collect($resource);
    }

    public function testWriteKeyIsNotArray(): void
    {
        $this->expectException(PromiseCollectionError::class);

        $resource = new \ArrayObject(
            [
                'id'        => '1',
                'write-key' => 1,
                'foo'       => ['1'],
            ]
        );

        $collector = new ArrayCollector('foo', 'write-key');
        $promises  = $collector->collect($resource);
        $promise   = $promises[0];

        $promise->resolve('some value');
    }

    public function testWriteKeyIsNull(): void
    {
        $resource = new \ArrayObject(
            [
                'id'        => '1',
                'write-key' => null,
                'foo'       => ['1'],
            ]
        );

        $collector = new ArrayCollector('foo', 'write-key');
        $promises  = $collector->collect($resource);
        $promise   = $promises[0];

        $promise->resolve('some value');

        self::assertEquals(
            new \ArrayObject(
                [
                    'id'        => '1',
                    'write-key' => ['some value'],
                    'foo'       => ['1'],
                ]
            ),
            $resource,
        );
    }

    public function testWriteKeyIsNotExist(): void
    {
        $resource = new \ArrayObject(
            [
                'id'  => '1',
                'foo' => ['1'],
            ]
        );

        $collector = new ArrayCollector('foo', 'write-key');
        $promises  = $collector->collect($resource);
        $promise   = $promises[0];

        $promise->resolve('some value');

        self::assertEquals(
            new \ArrayObject(
                [
                    'id'        => '1',
                    'write-key' => ['some value'],
                    'foo'       => ['1'],
                ]
            ),
            $resource,
        );
    }
}
