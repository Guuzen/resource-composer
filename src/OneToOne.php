<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

final class OneToOne
{
    private array $grouped = [];

    /**
     * @template T
     *
     * @param array<int, T>          $resources
     * @param callable(T): array-key $extract
     *
     * @return array<array-key, T>
     */
    public function group(array $resources, callable $extract): array
    {
        if ($this->grouped === []) {
            foreach ($resources as $resource) {
                $groupKey = $extract($resource);
                $this->grouped[$groupKey] = $resource;
            }
        }

        return $this->grouped;
    }
}
