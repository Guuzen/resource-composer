<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface ResourceResolver
{
    /**
     * @return iterable<int, int|string|null>
     */
    public function extractIds(object $resource): iterable;

    /**
     * @param array<int, string|int> $ids
     *
     * @return iterable<int, object>
     */
    public function load(array $ids, ResourceLoader $loader): iterable;

    /**
     * @param iterable<array-key, object> $loadedResources
     */
    public function group(iterable $loadedResources): void;

    public function resolve(object $resource): void;
}
