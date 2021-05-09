<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\Link\OneToMany;
use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;

final class WriterHasNoBooksTest extends TestCase
{
    public function test(): void
    {
        $writerId = '10';
        $writer   = [
            'id' => $writerId,
        ];

        $this->composer->registerRelation(
            new MainResource('writer', new SimpleCollector('id', 'books')),
            new OneToMany(),
            new RelatedResource('book', 'writerId', new StubResourceDataLoader([])),
        );

        $resources = $this->composer->composeOne($writer, 'writer');

        self::assertEquals(['id' => $writerId, 'books' => []], $resources);
    }
}
