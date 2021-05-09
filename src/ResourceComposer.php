<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\PromiseCollection\Promise;
use Guuzen\ResourceComposer\PromiseCollection\PromiseCollection;

/**
 * @psalm-type Config=array{0: MainResource, 1: Link, 2: RelatedResource}
 */
final class ResourceComposer
{
    /**
     * @var PromiseCollection
     */
    private $promises;

    /**
     * @var array<int, Config>
     */
    private $configs = [];

    public function __construct()
    {
        $this->promises = new PromiseCollection();
    }

    public function registerRelation(MainResource $mainResource, Link $link, RelatedResource $relatedResource): void
    {
        $this->configs[] = [$mainResource, $link, $relatedResource];
    }

    /**
     * @param array<array-key, array> $resources
     *
     * @return array<array-key, array>
     */
    public function compose(array $resources, string $resourceType): array
    {
        $collectors = $this->resourceCollectors($resourceType);
        $result     = self::denormalize($resources);
        $this->promises->remember($result, $collectors);

        $this->processResources();

        return self::normalize($result);
    }

    public function composeOne(array $resource, string $resourceType): array
    {
        return $this->compose([$resource], $resourceType)[0];
    }

    private function processResources(): void
    {
        $promiseGroups = $this->promises->release();
        if (0 === \count($promiseGroups)) {
            return;
        }

        foreach ($promiseGroups as $configId => $promiseGroup) {
            $this->resolvePromises($promiseGroup, $configId);
        }

        $this->processResources();
    }

    /**
     * @param array<int, Promise> $promises
     */
    private function resolvePromises(array $promises, int $configId): void
    {
        /**
         * @var Link $link
         * @var RelatedResource $relatedResource
         */
        [1 => $link, 2 => $relatedResource] = $this->configs[$configId];

        $ids = [];
        foreach ($promises as $promise) {
            $id = $promise->id();
            if ($id === null) {
                continue;
            }
            $ids[] = $id;
        }

        /** @psalm-suppress MixedArgumentTypeCoercion TODO update psalm */
        $loadedResources = $relatedResource->loader->load(\array_unique($ids));

        $collectors            = $this->resourceCollectors($relatedResource->name);
        $denormalizedResources = self::denormalize($loadedResources);
        $this->promises->remember($denormalizedResources, $collectors);

        $groupedResources = $link->group($denormalizedResources, $relatedResource->linkKey);

        foreach ($promises as $promise) {
            $promiseId = $promise->id();
            if ($promiseId === null) {
                $promise->resolve($link->defaultEmptyValue());
            } else {
                $promise->resolve($groupedResources[$promiseId] ?? $link->defaultEmptyValue());
            }
        }
    }

    /**
     * @return array<int, PromiseCollector>
     */
    private function resourceCollectors(string $resourceType): array
    {
        $collectors = [];
        foreach ($this->configs as $configId => $config) {
            /**
             * @psalm-suppress UnnecessaryVarAnnotation
             * @var MainResource $mainResource
             */
            $mainResource = $config[0];
            if ($mainResource->name === $resourceType) {
                $collectors[$configId] = $mainResource->collector;
            }
        }

        return $collectors;
    }

    /**
     * @param array<array-key, array> $resources
     *
     * @return array<array-key, \ArrayObject>
     */
    private static function denormalize(array $resources): array
    {
        $arrayObjects = [];
        foreach ($resources as $key => $resource) {
            $arrayObjects[$key] = new \ArrayObject($resource);
        }

        return $arrayObjects;
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     *
     * @return array<array-key, array>
     */
    private static function normalize(iterable $iterable): array
    {
        $result = [];
        /**
         * @psalm-var mixed $item
         * @var array-key   $key
         */
        foreach ($iterable as $key => $item) {
            if (\is_iterable($item) === true) {
                $result[$key] = self::normalize($item);
            } else {
                /** @psalm-suppress MixedAssignment */
                $result[$key] = $item;
            }
        }

        return $result;
    }
}
