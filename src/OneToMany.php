<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

final class OneToMany
{
    private array $grouped = [];

    /**
     * @template T
     *
     * @param array<int, T>          $resources
     * @param callable(T): array-key $extract
     *
     * @return array<array-key, array<int, T>>
     */
    public function group(array $resources, callable $extract): array
    {
        if ($this->grouped !== []) {
            /** @var array<array-key, array<int, T>> $grouped */
            $grouped = $this->grouped;

            return $grouped;
        }

        foreach ($resources as $resource) {
            $groupKey = $extract($resource);
            $this->grouped[$groupKey][] = $resource;
        }

        return $this->grouped;
    }
}
