<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

final class OneToMany implements ResourceResolver
{
    /**
     * @var array<array-key, array<int, object>>
     */
    private array $grouped = [];

    public function __construct(private string $byResourceId, private string $groupBy, private string $writeTo)
    {
    }

    public function extractIds(object $resource): iterable
    {
        return [$this->extractResourceId($resource)];
    }

    public function load(array $ids, ResourceLoader $loader): iterable
    {
        return $loader->load($ids, $this->groupBy);
    }

    public function group(iterable $loadedResources): void
    {
        if ($this->grouped !== []) {
            return;
        }

        foreach ($loadedResources as $resource) {
            $groupKey = $this->extractGroupKey($resource);
            $this->grouped[$groupKey][] = $resource;
        }
    }

    public function resolve(object $resource): void
    {
        $groupKey = $this->extractResourceId($resource);
        if ($groupKey === null) {
            return;
        }
        $writeTo = $this->writeTo;
        $resource->$writeTo = $this->grouped[$groupKey];
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    private function extractResourceId(object $resource): int|string|null
    {
        $byResourceId = $this->byResourceId;

        return $resource->$byResourceId;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    private function extractGroupKey(object $resource): int|string
    {
        $groupBy = $this->groupBy;

        return $resource->$groupBy;
    }
}
