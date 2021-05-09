<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\PromiseCollector;

use Guuzen\ResourceComposer\PromiseCollector;

final class MergeCollector implements PromiseCollector
{
    private $collectors;

    /**
     * @param PromiseCollector[] $collectors
     */
    public function __construct(array $collectors)
    {
        $this->collectors = $collectors;
    }

    public function collect(\ArrayObject $resource): array
    {
        $promises = [];
        foreach ($this->collectors as $collector) {
            $promises = \array_merge($promises, $collector->collect($resource));
        }

        return $promises;
    }
}
