<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\PromiseCollection;

use Guuzen\ResourceComposer\PromiseCollector;

final class PromiseCollection
{
    /**
     * @var array<int, array<int, Promise>>
     */
    private $promisesGroups = [];

    /**
     * @param array<array-key, \ArrayObject> $resources
     * @param array<int, PromiseCollector>   $collectors
     */
    public function remember(array $resources, array $collectors): void
    {
        foreach ($resources as $resource) {
            foreach ($collectors as $configId => $collector) {
                $promises = $collector->collect($resource);
                foreach ($promises as $promise) {
                    $this->promisesGroups[$configId][] = $promise;
                }
            }
        }
    }

    /**
     * @return array<int, array<int, Promise>>
     */
    public function release(): array
    {
        [$promisesGroups, $this->promisesGroups] = [$this->promisesGroups, []];

        return $promisesGroups;
    }
}
