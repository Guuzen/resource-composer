<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

/**
 * @template Resource of object
 * @template LoadedResource of object
 */
interface ResourceResolver
{
    /**
     * @param Resource $resource
     *
     * @return \Traversable<int, int|string|null>
     */
    public function extractIds(object $resource): \Traversable;

    /**
     * @param array<int, string|int> $ids
     *
     * @return array<int, LoadedResource>
     */
    public function load(array $ids): array;

    /**
     * @param Resource                   $resource
     * @param array<int, LoadedResource> $loadedResources
     */
    public function resolve(object $resource, array $loadedResources): void;

    /**
     * @return class-string<Resource>
     */
    public function resourceClass(): string;
}
