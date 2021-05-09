<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\PromiseCollector;

use Guuzen\ResourceComposer\PromiseCollector;

final class CustomCollector implements PromiseCollector
{
    private $collector;

    /**
     * @param callable(mixed): \Guuzen\ResourceComposer\PromiseCollection\Promise[] $collector
     */
    public function __construct(callable $collector)
    {
        $this->collector = $collector;
    }

    public function collect(\ArrayObject $resource): array
    {
        return ($this->collector)($resource);
    }
}